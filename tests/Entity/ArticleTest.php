<?php

namespace App\Tests;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArticleTest extends KernelTestCase
{
    public function setUp(): void 
    {
        // Init Author
        $this->user = (new User())
            ->setEmail("user-test@yopmail.com")
            ->setPassword("azertyuiop")
            ->setIsVerified(true);
    }

    public function assertHasErrors(Article $article, int $numberErrors=0, string $field=null, Array $fieldMessages=null): void
    {
        $validatorService = static::getContainer()->get('validator');
        $errors = $validatorService->validate($article);

        $debugMessages = [];
        $errorMessages = [];
        $errorField    = "";
        foreach($errors as $error) {
            $debugMessages[] = $error->getPropertyPath() . " : " . $error->getMessage();
            $errorMessages[] = $error->getMessage();
            $errorField = $error->getPropertyPath();
        }

        $this->assertCount($numberErrors, $errors, implode(', ', $debugMessages));
        if(!is_null($field) && !is_null($fieldMessages)) {
            $this->assertEquals($field, $errorField);
            $this->assertEquals($fieldMessages, $errorMessages);
        }
    }

    public function testValidArticle()
    {
        $article = (new Article())
            ->setTitle("Loren")
            ->setDescription("Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae, ad dicta dignissimos deleniti a ab perspiciatis quis distinctio nobis velit doloremque quaerat? Aut tempora cupiditate itaque suscipit debitis, cumque quod!")
            ->setAuthor($this->user);

        $this->assertHasErrors($article, 0);
    }


    // TEST - TITLE 

    public function testInvalidArticleNotTitle()
    {
        $article = (new Article())
            ->setDescription("Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae, ad dicta dignissimos deleniti a ab perspiciatis quis distinctio nobis velit doloremque quaerat? Aut tempora cupiditate itaque suscipit debitis, cumque quod!")
            ->setAuthor($this->user);

        $this->assertHasErrors($article, 1, "title", ["This value should not be blank."]);
    }

    public function testInvalidArticleEmptyTitle()
    {
        $article = (new Article())
            ->setTitle("")
            ->setDescription("Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae, ad dicta dignissimos deleniti a ab perspiciatis quis distinctio nobis velit doloremque quaerat? Aut tempora cupiditate itaque suscipit debitis, cumque quod!")
            ->setAuthor($this->user);

        $this->assertHasErrors($article, 2, "title", [
            "This value should not be blank.",
            "This value is too short. It should have 2 characters or more."
        ]);
    }

    public function testInvalidArticleTooShortTitle()
    {
        $article = (new Article())
            ->setTitle("a")
            ->setDescription("Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae, ad dicta dignissimos deleniti a ab perspiciatis quis distinctio nobis velit doloremque quaerat? Aut tempora cupiditate itaque suscipit debitis, cumque quod!")
            ->setAuthor($this->user);

        $this->assertHasErrors($article, 1, "title", [
            "This value is too short. It should have 2 characters or more."
        ]);
    }

    public function testInvalidArticleTooLongTitle()
    {
        $article = (new Article())
            ->setTitle("Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae, ad dicta di Lorem ipsum dolor sit am")
            ->setDescription("Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae, ad dicta dignissimos deleniti a ab perspiciatis quis distinctio nobis velit doloremque quaerat? Aut tempora cupiditate itaque suscipit debitis, cumque quod!")
            ->setAuthor($this->user);

        $this->assertHasErrors($article, 1, "title", [
            "This value is too long. It should have 100 characters or less."
        ]);
    }


    // TEST - DESCRIPTION

    public function testInvalidArticleNotDescription()
    {
        $article = (new Article())
            ->setTitle("Lorem")
            ->setAuthor($this->user);

        $this->assertHasErrors($article, 1, "description", ["This value should not be blank."]);
    }

    public function testInvalidArticleEmptyDescription()
    {
        $article = (new Article())
            ->setTitle("Lorem")
            ->setDescription("")
            ->setAuthor($this->user);

        $this->assertHasErrors($article, 2, "description", [
            "This value should not be blank.",
            "This value is too short. It should have 50 characters or more."
        ]);
    }

    public function testInvalidArticleTooShortDescription()
    {
        $article = (new Article())
            ->setTitle("Lorem")
            ->setDescription("Lorem")
            ->setAuthor($this->user);

        $this->assertHasErrors($article, 1, "description", [
            "This value is too short. It should have 50 characters or more."
        ]);
    }
}
