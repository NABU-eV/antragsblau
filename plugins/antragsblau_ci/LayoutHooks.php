<?php

namespace app\plugins\antragsblau_ci;

use app\components\UrlHelper;
use app\models\layoutHooks\{Hooks, Layout};
use yii\helpers\Html;

class LayoutHooks extends Hooks
{
    public function beforePage(string $before): string
    {
        return '<div class="header_wrap"><div class="container">
                    <div class="logo">
                        <a href="/" class="homeLinkLogo">
                            <span class="sr-only">Zur Startseite</span>
                            <span class="logoImg"></span>
                        </a>
                    </div>'
                    .$this->getMenu().
                '</div></div>';
    }
    public function beginPage(string $before): string
    {
        return '';
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
        return "";
    }

    private function getMenu() {
        $out = '
            <section class="navwrap">' .
            '<div>' .
            '<nav role="navigation" class="pos" id="mainmenu"><h6 class="unsichtbar">' .
            \Yii::t('base', 'menu_main') . ':</h6>' .
            '<div class="navigation nav-fallback clearfix">';
        $out .= Layout::getStdNavbarHeader();
        $out .= '</div></nav>';
        $out .= Layout::breadcrumbs();
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
