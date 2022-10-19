<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class DefaultControllerPantherTest extends PantherTestCase
{
    public function testIndex(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');

        $client->takeScreenshot('tests-reports/PantherScreen/testIndex.png');
        $this->assertPageTitleContains('Hello DefaultController!');
        $this->assertSelectorTextContains('h1', 'Album example');
    }

    public function testGotoSignin(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');

        $client->takeScreenshot('tests-reports/PantherScreen/testGotoSignin_1-2.png');
        $this->assertSelectorTextContains('.navbar-nav:last-child li', 'login');

        $link = $crawler->selectLink("login")->link();
        $crawler = $client->click($link);

        $client->takeScreenshot('tests-reports/PantherScreen/testGotoSignin_2-2.png');
        $this->assertSelectorTextContains('h1', 'Please sign in');
    }
}
