<?php

namespace app\plugins\antragsblau_saml;

use app\components\{LoginProviderInterface, RequestContext, UrlHelper};
use app\models\db\ConsultationUserGroup;
use app\models\db\User;
use app\models\settings\AntragsgruenApp;
use app\models\settings\Site as SiteSettings;
use Exception;
use SimpleSAML\Auth\Simple;
use Yii;

class SamlLogin implements LoginProviderInterface
{

    private const PARAM_EMAIL = 'gmnMail';
    private const PARAM_GROUPS = 'groups';
    private const PARAM_USERNAME = 'uid';
    private const PARAM_GIVEN_NAME = 'givenName';
    private const PARAM_FAMILY_NAME = 'sn';
    private const PARAM_ORGANIZATION = 'membershipOrganizationKey';

    public function getId(): string
    {
        return (string)SiteSettings::LOGIN_GRUENES_NETZ;
    }

    public function getName(): string
    {
        return Yii::t('antragsblau_saml', 'login_title');
    }

    public function renderLoginForm(string $backUrl, bool $active): string
    {
        return Yii::$app->controller->renderPartial('@app/plugins/antragsblau_saml/views/login', [
            'loginActive' => $active,
            'backUrl'     => $backUrl,
        ]);
    }

    public function isCurrentUserAuthenticated(): bool
    {
        $samlClient = new Simple('nabu-sp');

        return $samlClient->isAuthenticated();
    }

    public function performLoginAndReturnUser(): User
    {
        $samlClient = new Simple('nabu-sp');

        $samlClient->requireAuth([]);
        if (! $samlClient->isAuthenticated()) {
            throw new Exception('SimpleSaml: Something went wrong on requireAuth');
        }
        $params = $samlClient->getAttributes();

        $user = $this->getOrCreateUser($params);
        RequestContext::getUser()->login($user, AntragsgruenApp::getInstance()->autoLoginDuration);

        $user->dateLastLogin = date('Y-m-d H:i:s');
        $user->save();

        return $user;
    }

    /**
     * @throws Exception
     */
    public function getOrCreateUser(array $params): User
    {
        $email = $params[self::PARAM_EMAIL][0];
        $givenname = (isset($params[self::PARAM_GIVEN_NAME]) ? $params[self::PARAM_GIVEN_NAME][0] : '');
        $familyname = (isset($params[self::PARAM_FAMILY_NAME]) ? $params[self::PARAM_FAMILY_NAME][0] : '');
        $username = $params[self::PARAM_USERNAME][0];
        $groups = $params[self::PARAM_GROUPS];
        $auth = $this->usernameToAuth($username);

        /** @var User|null $user */
        $user = User::findOne(['auth' => $auth]);
        if (! $user) {
            $user = new User();
        }

        $user->name = $givenname . ' ' . $familyname;
        $user->nameGiven = $givenname;
        $user->nameFamily = $familyname;
        $user->email = $email;
        $user->emailConfirmed = 1;
        $user->fixedData = User::FIXED_NAME;
        $user->auth = $auth;
        $user->status = User::STATUS_CONFIRMED;
        $user->organization = $user->organization ?? '';
        if (! $user->save()) {
            throw new Exception('Could not create user');
        }

        $this->syncUserGroups($user, $groups);

        return $user;
    }

    public function usernameToAuth(string $username): string
    {
        return Module::AUTH_KEY_USERS . ':https://login.nabu.de/saml/sp/' . $username;
    }

    private function syncUserGroups(User $user, array $groups): void
    {
        $user->unlinkAll('userGroups', true);

        foreach ($groups as $group) {
            $userGroup = ConsultationUserGroup::findOne(['title' => $group]);
            if ($userGroup) {
                $user->link('userGroups', $userGroup);
            }
        }

        $user->save();
    }

    /**
     * @return ConsultationUserGroup[]|null
     */
    public function getSelectableUserOrganizations(User $user): ?array
    {
        $orgas = [];
        foreach ($user->userGroups as $userGroup) {
            if ($userGroup->externalId && strpos($userGroup->externalId, Module::AUTH_KEY_GROUPS . ':') === 0) {
                $orgas[] = $userGroup;
            }
        }

        return $orgas;
    }

    public function logoutCurrentUserIfRelevant(string $backUrl): ?string
    {
        $backSubdomain = UrlHelper::getSubdomain($backUrl);
        $currDomain = ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ($_SERVER['REQUEST_SCHEME'] ?? 'http')) . '://' . $_SERVER['HTTP_HOST'];
        $currSubdomain = UrlHelper::getSubdomain($currDomain);

        if ($currSubdomain) {
            $user = User::getCurrentUser();
            if (! $this->userWasLoggedInWithProvider($user)) {
                return null;
            }

            // First step on the subdomain: logout and redirect to the main domain
            RequestContext::getUser()->logout();
            $backParts = parse_url($backUrl);
            if ($backParts === false || ! isset($backParts['host'])) {
                $backUrl = ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ($_SERVER['REQUEST_SCHEME'] ?? 'http')) . '://' . $_SERVER['HTTP_HOST'] . $backUrl;
            }

            $backUrl = AntragsgruenApp::getInstance()->domainPlain . 'user/logout?backUrl=' . urlencode($backUrl);
        } elseif ($backSubdomain) {
            // Second step: we are on the main domain. Logout and redirect to the subdomain
            // Here, there might not be a user object (NULL), so we will proceed anyway
            self::logout();
        } else {
            $user = User::getCurrentUser();
            if (! $this->userWasLoggedInWithProvider($user)) {
                return null;
            }

            // No subdomain is involved, local logout on the main domain
            self::logout();
        }

        return $backUrl;
    }

    public function userWasLoggedInWithProvider(?User $user): bool
    {
        if (! $user || ! $user->auth) {
            return false;
        }
        $authParts = explode(':', $user->auth);

        return $authParts[0] === Module::AUTH_KEY_USERS;
    }

    public static function logout(): void
    {
        $samlClient = new Simple('nabu-sp');

        if ($samlClient->isAuthenticated()) {
            $samlClient->logout();
        }
        RequestContext::getUser()->logout();
    }

    public function renderAddMultipleUsersForm(): ?string
    {
        return Yii::$app->controller->renderPartial('@app/plugins/antragsblau_saml/views/users_add_multiple', []);
    }
}
