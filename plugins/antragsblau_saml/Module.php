<?php

declare(strict_types=1);

namespace app\plugins\antragsblau_saml;

use app\components\LoginProviderInterface;
use app\models\db\User;
use app\plugins\ModuleBase;
use Exception;
use SimpleSAML\Auth\Simple;

class Module extends ModuleBase
{

    public const AUTH_KEY_GROUPS = 'nabu';

    public const AUTH_KEY_USERS = 'openid';

    // This key is legacy from the time when we used OpenID as provider but wanted to keep the user accounts when switching to SAML.

    private static ?LoginProviderInterface $loginProvider = null;

    public static function getManagerUrlRoutes(string $domainPlain): array
    {
        return [
            $domainPlain . '/nabu-login' => '/antragsblau_saml/login/login',
        ];
    }

    public static function getAllUrlRoutes(
        array $urls,
        string $dom,
        string $dommotion,
        string $dommotionOld,
        string $domamend,
        string $domamendOld
    ): array {
        return array_merge(
            [
                $dom . 'nabu-login' => '/antragsblau_saml/login/login',
            ],
            parent::getAllUrlRoutes($urls, $dom, $dommotion, $dommotionOld, $domamend, $domamendOld)
        );
    }

    /**
     * @throws Exception
     */
    public function init(): void
    {
        parent::init();

        $login = self::getDedicatedLoginProvider();
        $user = User::getCurrentUser();
        $samlClient = new Simple('nabu-sp');

        if (! is_null($user) && ! $samlClient->isAuthenticated()) {
            $login->logoutCurrentUserIfRelevant('/');
        }
    }

    public static function getDedicatedLoginProvider(): ?LoginProviderInterface
    {
        if (self::$loginProvider === null) {
            self::$loginProvider = new SamlLogin();
        }

        return self::$loginProvider;
    }


}
