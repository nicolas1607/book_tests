<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    const FAKE_USERS = [
        ['Jeffy', 'Szanto', 'jszanto0@mozilla.com', 'KEDxLIwRjXr'],
        ['Joella', 'McCart', 'jmccart1@indiegogo.com', '1Cnp8Q1h2j'],
        ['Maddy', 'Vogeller', 'mvogeller2@cnbc.com', 'eZl2VgT'],
        ['Coriss', 'Baine', 'cbaine3@imageshack.us', 'rsBdSVHNZaMc'],
        ['Eddie', 'Mervyn', 'emervyn4@ow.ly', 'au64M8owHK5z'],
        ['Randolph', 'Bril', 'rbril5@exblog.jp', 'F5ET3OKf'],
        ['Zed', 'Pere', 'zpere6@ted.com', 'jZMzL2e1']
    ];

    private UserPasswordHasherInterface $passHasher;

    public function __construct(UserPasswordHasherInterface $passHasher)
    {
        $this->passHasher = $passHasher;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::FAKE_USERS as $fake_user) {
            $user = new User;
            $user->setFirstname($fake_user[0])
                ->setLastname($fake_user[1])
                ->setEmail($fake_user[2])
                ->setRoles([])
                ->setPassword($this->passHasher->hashPassword($user, $fake_user[3]));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
