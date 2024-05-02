<?php

namespace app\plugins\antragsblau_ci;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{

    public $sourcePath = '@app/plugins/antragsblau_ci/assets/';

    public $css = [
        'layout-antragsblau_ci.css',
    ];

    public $js = [
        'custom.js',
        'matomoTag.js',
    ];

    public $depends = [
    ];
}
