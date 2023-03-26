<?php /** @noinspection PhpUnusedParameterInspection */

namespace app\plugins;

use app\components\ExternalPasswordAuthenticatorInterface;
use app\models\db\{Amendment, Consultation, Motion, Site, User, Vote, VotingBlock};
use app\components\LoginProviderInterface;
use app\models\layoutHooks\Hooks;
use app\models\settings\{IMotionStatus, Layout, Privilege, VotingData};
use app\models\siteSpecificBehavior\DefaultBehavior;
use yii\base\{Action, Module};
use yii\web\{AssetBundle, Controller, View};

class ModuleBase extends Module
{
    public function init(): void
    {
        parent::init();

        if (\Yii::$app instanceof \yii\console\Application) {
            $ref                       = new \ReflectionClass($this);
            $this->controllerNamespace = $ref->getNamespaceName() . '\\commands';
        }
    }

    /**
     * @return AssetBundle[]
     */
    public static function getActiveAssetBundles(Controller $controller): array
    {
        return [];
    }

    protected static function getMotionUrlRoutes(): array
    {
        return [];
    }

    protected static function getAmendmentUrlRoutes(): array
    {
        return [];
    }

    public static function getManagerUrlRoutes(string $domainPlain): array
    {
        return [];
    }

    public static function getDefaultRouteOverride(): ?string
    {
        return null;
    }

    public static function getGeneratedRoute(array $routeParts, string $originallyGeneratedRoute): ?string
    {
        return null;
    }

    public static function getAllUrlRoutes(array $urls, string $dom, string $dommotion, string $dommotionOld, string $domamend, string $domamendOld): array
    {
        foreach (static::getMotionUrlRoutes() as $url => $route) {
            $urls[$dommotion . '/' . $url]    = $route;
            $urls[$dommotionOld . '/' . $url] = $route;
        }
        foreach (static::getAmendmentUrlRoutes() as $url => $route) {
            $urls[$domamend . '/' . $url]    = $route;
            $urls[$domamendOld . '/' . $url] = $route;
        }

        return $urls;
    }

    public static function getPermissionsClass(): ?string
    {
        return null;
    }

    /**
     * @param Privilege[] $origPrivileges
     * @return Privilege[]
     */
    public static function addCustomPrivileges(Consultation $consultation, array $origPrivileges): array
    {
        return $origPrivileges;
    }

    /**
     * Return values:
     * - null does not change default behavior
     * - true grants access, even if no access by default. (It does NOT however grant permissions to perform any actions)
     * - false denies access, even if the user has access by default. (It does NOT remove the permissions to perform any actions in the motion administration)
     */
    public static function canSeeFullMotionList(Consultation $consultation, User $user): ?bool
    {
        return null;
    }

    /**
     * @phpstan-ignore-next-line
     * @return null|DefaultBehavior|string
     */
    public static function getSiteSpecificBehavior(Site $site): ?string
    {
        return null;
    }

    /**
     * @phpstan-ignore-next-line
     * @return string|\app\models\settings\Consultation|null
     */
    public static function getConsultationSettingsClass(Consultation $consultation): ?string
    {
        return null;
    }

    public static function getConsultationExtraSettingsForm(Consultation $consultation): string
    {
        return '';
    }

    /**
     * @phpstan-ignore-next-line
     * @return string|\app\models\settings\Site|null
     */
    public static function getSiteSettingsClass(Site $site): ?string
    {
        return null;
    }

    public static function getProvidedLayouts(?View $view = null): array
    {
        return [];
    }

    public static function overridesDefaultLayout(): ?string
    {
        return null;
    }

    public static function getProvidedPdfLayouts(array $default): array
    {
        return $default;
    }

    public static function getProvidedTranslations(): array
    {
        return [];
    }

    public static function getCustomMotionExports(Motion $motion): array
    {
        return [];
    }

    /**
     * @return Hooks[]
     */
    public static function getForcedLayoutHooks(Layout $layoutSettings, ?Consultation $consultation): array
    {
        return [];
    }

    public static function getCustomSiteCreateView(): ?string
    {
        return null;
    }

    public static function getCustomEmailTemplate(): ?string
    {
        return null;
    }

    public static function getRobotsIndexOverride(?Consultation $consultation, Action $action, bool $default): ?bool
    {
        return null;
    }

    public static function getDefaultLogo(): ?array
    {
        return null;
    }

    public static function getExternalPasswordAuthenticator(): ?ExternalPasswordAuthenticatorInterface
    {
        return null;
    }

    public static function getDedicatedLoginProvider(): ?LoginProviderInterface
    {
        return null;
    }

    /**
     * @param Vote[] $votes
     */
    public static function calculateVoteResultsForApi(VotingBlock $voting, array $votes): ?array
    {
        return null;
    }

    /**
     * @return string|VotingData
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public static function getVotingDataClass(Consultation $consultation): ?string
    {
        return null;
    }

    public static function userIsRelevantForQuorum(VotingBlock $votingBlock, User $user): ?bool
    {
        return null;
    }

    public static function getRelevantEligibleVotersCount(VotingBlock $votingBlock): ?int
    {
        return null;
    }

    public static function getMotionExtraSettingsForm(Motion $motion): string
    {
        return '';
    }

    public static function setMotionExtraSettingsFromForm(Motion $motion, array $post): void
    {
    }

    public static function getAmendmentExtraSettingsForm(Amendment $amendment): string
    {
        return '';
    }

    public static function setAmendmentExtraSettingsFromForm(Amendment $amendment, array $post): void
    {
    }

    /**
     * @return IMotionStatus[]
     */
    public static function getAdditionalIMotionStatuses(): array
    {
        return [];
    }

    public static function getMotionVersions(Consultation $consultation): ?array
    {
        return null;
    }
}
