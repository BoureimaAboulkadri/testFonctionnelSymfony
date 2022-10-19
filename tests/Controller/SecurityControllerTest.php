<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginLogout(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Please sign in');
        $this->assertSelectorTextContains('.navbar-nav:last-child li', 'login');

        $form = $crawler->filter('button[type=submit]')->form();
        $form['email'] = "author1@yopmail.com";
        $form['password'] = "azertyuiop";
        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('Hello DefaultController!');
        $this->assertSelectorTextContains('.navbar-nav:last-child .dropdown-menu li:last-child', 'Logout');

        $crawler = $client->getCrawler();
        $link = $crawler->filter('a:contains("Logout")')->eq(0)->link();
        $crawler = $client->click($link);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('Hello DefaultController!');
        $this->assertSelectorTextContains('.navbar-nav:last-child li', 'login');      
    }

    public function testLoginError()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('button[type=submit]')->form();
        $form['email'] = "author-fake@yopmail.com";
        $form['password'] = "azertyuiop";
        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert.alert-danger', 'Invalid credentials.');      
    }

    public function testEmailNotVerified()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('button[type=submit]')->form();
        $form['email'] = "author2@yopmail.com";
        $form['password'] = "azertyuiop";
        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert.alert-danger', 'Please verify your account before logging in.');  
    }
}
