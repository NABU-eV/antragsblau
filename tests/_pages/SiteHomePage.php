<?php

namespace app\tests\_pages;

use Helper\BasePage;

/**
 * Represents contact page
 * @property \AcceptanceTester|\FunctionalTester $actor
 */
class SiteHomePage extends BasePage
{
    public $route = 'consultation/home';
}
