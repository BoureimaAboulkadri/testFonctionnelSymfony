<?php

namespace App\Tests;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{

    private KernelBrowser $client;
    private ArticleRepository $repository;

    public function setUp(): void 
    {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->filter('button[type=submit]')->form();
        $form['email'] = "author1@yopmail.com";
        $form['password'] = "azertyuiop";
        $this->client->submit($form);
        $this->client->followRedirect();

        $this->repository = static::getContainer()->get('doctrine')->getRepository(Article::class);
        foreach($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }
    
    public function testIndexNotRecord(): void
    {
        $crawler = $this->client->request('GET', '/article/');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('Article index');
        $this->assertSelectorTextContains('.table tbody tr td', 'no records found');
    }

    public function testNewArticle(): void
    {
        $originalNumberArticle = count($this->repository->findAll());
        $crawler = $this->client->request('GET', '/article/new');

        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Save', [
           'article[title]' => 'Testing',
           'article[description]' => 'Testing Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);

        $this->assertResponseRedirects('/article/');
        $this->assertSame($originalNumberArticle + 1, count($this->repository->findAll()));
    }

    public function testShowArticle(): void
    {
        $originalNumberArticle = count($this->repository->findAll());
        $this->client->request('GET', '/article/new');
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Save', [
           'article[title]' => 'Testing 2',
           'article[description]' => 'Testing 2 Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);

        $this->assertSame($originalNumberArticle + 1, count($this->repository->findAll()));
        $items = $this->repository->findAll();
        $lastID = $items[0]->getId();

        $this->client->request('GET', '/article/'.$lastID);
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('Article: Testing 2');
    }

    public function testEditArticle(): void
    {
        $originalNumberArticle = count($this->repository->findAll());
        $this->client->request('GET', '/article/new');
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Save', [
           'article[title]' => 'Testing 3',
           'article[description]' => 'Testing 3 Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);

        $this->assertSame($originalNumberArticle + 1, count($this->repository->findAll()));
        $items = $this->repository->findAll();
        $lastID = $items[0]->getId();

        $this->client->request('GET', '/article/'.$lastID.'/edit');
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Update', [
            'article[title]' => 'Testing 3 edit',
            'article[description]' => 'Testing 3 editLorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);

        $this->assertResponseRedirects('/article/');

        $this->client->request('GET', '/article/'.$lastID);
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('Article: Testing 3 edit');
    }

    public function testDeleteArticle(): void
    {
        $originalNumberArticle = count($this->repository->findAll());
        $this->client->request('GET', '/article/new');
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Save', [
           'article[title]' => 'Testing 4',
           'article[description]' => 'Testing 4 Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);
        $this->assertSame($originalNumberArticle + 1, count($this->repository->findAll()));
        $this->client->followRedirect();

        $this->client->submitForm('Delete');
        $this->assertSame($originalNumberArticle, count($this->repository->findAll()));
        $this->assertResponseRedirects('/article/');
    }
}