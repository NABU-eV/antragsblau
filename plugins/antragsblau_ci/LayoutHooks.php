<?php

namespace app\plugins\antragsblau_ci;

use app\components\RequestContext;
use app\components\UrlHelper;
use app\controllers\Base;
use app\models\settings\AntragsgruenApp;
use app\models\layoutHooks\{Hooks, Layout};
use yii\helpers\Html;
use yii\web\View;

class LayoutHooks extends Hooks
{

    public function endOfHead(string $before): string
    {
        $before .= '<script> var require={urlArgs: "version=' . ANTRAGSGRUEN_VERSION . '"};</script>';

        return $before;
    }

    public function beforePage(string $before): string
    {
        /** @var Base $controller */
        $controller = RequestContext::getWebApplication()->controller;
        $assetBundle = reset($controller->view->assetBundles);
        $assetUrl = $assetBundle->baseUrl;
        return '<div class="header_wrap">
                    <div class="container">
                        <div class="logo">
                            <a href="/" class="homeLinkLogo">
                                <span class="sr-only">Zur Startseite</span>
                                <img class="logoImg" src="' . $assetUrl . '/NABUAntragsblau-logo.png" alt="Logo">
                            </a>
                        </div>'
            . $this->getMenu() .
            '</div>
               </div>
               <div class="breadcrumb_wrap"><div class="container">'
            . Layout::breadcrumbs() .
            '</div></div>';
    }

    public function beginPage(string $before): string
    {
        $controller = RequestContext::getWebApplication()->controller;
        if ($controller->id == 'amendment' && $controller->action->id == 'create') {
            return '<div style="position: sticky;top: 55%;z-index: 1000;">
                        <button type="button" name="scroll_down"
                                class="btn btn-primary scrollToBottom hidden"
                                style="position:absolute; right:-3%; color: #fff;background: #0069b4; border-color:#0069b4"
                                >
                        <span aria-hidden="true" class="glyphicon glyphicon-send" style="padding-right:8px"></span>
                        Änderungsantrag abschließen
                        </button>
                    </div>';
        }

        return '';
    }

    public function getAntragsgruenAd(string $before): string
    {
        return '';
    }


    public function favicons(string $before): string
    {
        /** @var Base $controller */
        $controller = RequestContext::getWebApplication()->controller;
        $assetBundle = reset($controller->view->assetBundles);
        $faviconBase = $assetBundle->baseUrl;

        return '<link rel="apple-touch-icon" sizes="180x180" href="' . $faviconBase . '/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="' . $faviconBase . '/favicon.ico">
<link rel="icon" type="image/png" sizes="16x16" href="' . $faviconBase . '/favicon.ico">
<link rel="mask-icon" href="' . $faviconBase . '/safari-pinned-tab.svg" color="#3bb030">
<meta name="theme-color" content="#ffffff">';
    }

    public function renderSidebar(string $before): string
    {
        $str = $this->layout->preSidebarHtml;
        if (count($this->layout->menusHtml) > 0) {
            $str .= '<div class="hidden-xs">';
            $str .= implode('', $this->layout->menusHtml);
            $str .= '</div>';
        }
        $str .= $this->layout->postSidebarHtml;
        $str = str_replace("glyphicon-bell", "glyphicon-envelope", $str);
        $str = str_replace("glyphicon-scissors", "glyphicon-pencil", $str);
        $str = str_replace("glyphicon-globe", "glyphicon-bell", $str);

        return $str;
    }

    public function getStdNavbarHeader(string $before): string
    {
        return $before;
    }

    public function logoRow(string $before): string
    {
        return '';
    }

    public function beforeContent(string $before): string
    {
        return '';
//        return '<div class="row">'.Layout::breadcrumbs().'</div>';
    }

    private function getMenu()
    {
        $out = '
            <section class="navwrap">' .
            '<div>' .
            '<nav role="navigation" class="pos" id="mainmenu"><h6 class="unsichtbar">' .
            \Yii::t('base', 'menu_main') . ':</h6>' .
            '<div class="navigation nav-fallback clearfix">';
        $out .= Layout::getStdNavbarHeader();
        $out .= '</div></nav>';
//        $out .= Layout::breadcrumbs();
        $out .= '</div></section>';

        if ($this->consultation) {
            $warnings = array_merge(
                Layout::getSitewidePublicWarnings($this->consultation->site),
                Layout::getConsultationwidePublicWarnings($this->consultation)
            );
            if (count($warnings) > 0) {
                $out .= '<div class="alert alert-danger consultationwideWarning">';
                $out .= '<p>' . implode('</p><p>', $warnings) . '</p>';
                $out .= '</div>';
            }
        }

        return $out;
    }

    public function breadcrumbs(string $before): string
    {
        $out = '';
        $showBreadcrumbs = (! $this->consultation || ! $this->consultation->site || $this->consultation->site->getSettings()->showBreadcrumbs);
        if (is_array($this->layout->breadcrumbs) && $showBreadcrumbs) {
            $out .= '<nav aria-label="' . \Yii::t('base', 'aria_breadcrumb') . '"><ol class="breadcrumb">';
            foreach ($this->layout->breadcrumbs as $link => $name) {
                if ($link === '' || is_numeric($link)) {
                    $out .= '<li>' . Html::encode($name) . '</li>';
                } else {
                    if ($link === UrlHelper::homeUrl()) {
                        // We have enough links to the home page already, esp. the logo just a few pixels away. This would be confusing for screenreaders.
                        $out .= '<li><span class="pseudoLink" data-href="' . Html::encode($link) . '"><span style="font-size: 13px;" class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;' . Html::encode($name) . '</span></li>';
                    } else {
                        $label = str_replace('%TITLE%', $name, \Yii::t('base', 'aria_bc_back'));
                        $out .= '<li>' . Html::a(Html::encode($name), $link, ['aria-label' => $label]) . '</li>';
                    }
                }
            }
            $out .= '</ol></nav>';
        }

        return $out;
    }

    public function getSearchForm(string $before): string
    {
        $html = Html::beginForm(UrlHelper::createUrl('consultation/search'), 'post', ['class' => 'form-search']);
        $html .= '<h6 class="invisible">' . \Yii::t('con', 'sb_search_form') . '</h6>';
        $html .= '<label for="search">' . \Yii::t('con', 'sb_search_desc') . '</label>
    <input type="text" class="query" name="query"
        placeholder="' . Html::encode(\Yii::t('con', 'sb_search_query')) . '" required
        title="' . Html::encode(\Yii::t('con', 'sb_search_query')) . '">

    <button type="submit" class="button-submit">
                <span class="fa fa-search"></span> <span class="text">' . \Yii::t('antragsblau_ci', 'search') . '</span>
            </button>';
        $html .= Html::endForm();

        return $html;
    }
}
