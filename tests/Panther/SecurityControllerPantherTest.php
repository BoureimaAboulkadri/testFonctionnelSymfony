<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class SecurityControllerPantherTest extends PantherTestCase
{
    public function testLoginLogout(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/login');

        $client->takeScreenshot('tests-reports/PantherScreen/testLoginLogout_1-5.png');
        $this->assertSelectorTextContains('h1', 'Please sign in');
        $this->assertSelectorTextContains('.navbar-nav:last-child li', 'login');

        $form = $crawler->filter('button[type=submit]')->form();
        $form['email'] = "author1@yopmail.com";
        $form['password'] = "azertyuiop";
        $client->takeScreenshot('tests-reports/PantherScreen/testLoginLogout_2-5.png');
        $client->submit($form);

        $client->takeScreenshot('tests-reports/PantherScreen/testLoginLogout_3-5.png');
        $this->assertPageTitleContains('Hello DefaultController!');
        $this->assertSelectorTextContains('.navbar-nav .dropdown', 'Settings');

        $crawler = $client->getCrawler();
        $link = $crawler->selectLink("Settings")->link();
        $crawler = $client->click($link);
        
        $client->takeScreenshot('tests-reports/PantherScreen/testLoginLogout_4-5.png');
        $this->assertSelectorTextContains('.navbar-nav:last-child .dropdown-menu li:last-child', 'Logout');

        $link = $crawler->selectLink("Logout")->link();
        $crawler = $client->click($link);

        $client->takeScreenshot('tests-reports/PantherScreen/testLoginLogout_5-5.png');
        $this->assertPageTitleContains('Hello DefaultController!');
        $this->assertSelectorTextContains('.navbar-nav:last-child li', 'login'); 
          
    }
}
