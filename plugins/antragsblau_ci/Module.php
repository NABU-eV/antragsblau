<?php

namespace app\plugins\antragsblau_ci;

use app\plugins\ModuleBase;
use yii\web\View;

class Module extends ModuleBase
{
    public static function getProvidedLayouts(?View $view = null): array
    {
        if ($view) {
            $asset = ThumbnailAssets::register($view);
            $thumbBase = $asset->baseUrl;
        } else {
            $thumbBase = '/';
        }

        return [
            'std' => [
                'title'       => 'Antragsblau CI',
                'preview'     => $thumbBase . '/layout-preview-green.png',
                'bundle'      => Assets::class,
                'hooks'       => LayoutHooks::class,
                // 'odtTemplate' => __DIR__ . '/OpenOffice-Template-Gruen.odt',
            ]
        ];
    }
}
