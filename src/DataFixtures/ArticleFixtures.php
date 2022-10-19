<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];    
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");

        for($i=0; $i<20; $i++){
            $article = new Article();
            $article->setTitle($faker->word());
            $article->setDescription($faker->paragraphs(4, true));
            $article->setAuthor($this->getReference("USER".mt_rand(0,1)));
            $manager->persist($article);
            $this->addReference("ARTICLE".$i, $article);
        }

        $manager->flush();
    }
}
