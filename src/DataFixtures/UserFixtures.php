<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $count = 0;
        $users = [
            "author1@yopmail.com" => [
                "role" => [],
                "verified" => 1
            ],
            "author2@yopmail.com" => [
                "role" => [],
                "verified" => 0
            ]
        ];

        foreach($users as $email => $info) {
            $user = new User();
            $user->setEmail($email);
            $user->setRoles($info["role"]);
            $user->setIsVerified($info["verified"]);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "azertyuiop"));
            $manager->persist($user);

            $this->addReference("USER".$count, $user);
            $count++;
        }

        $manager->flush();
    }
}
