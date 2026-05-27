<?php

namespace App\Tests\Fixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $verified = new User();
        $verified->setEmail('verified@example.com');
        $verified->setPassword($this->passwordHasher->hashPassword($verified, 'password123'));
        $verified->setIsVerified(true);
        $manager->persist($verified);
        $this->addReference('user-verified', $verified);

        $unverified = new User();
        $unverified->setEmail('unverified@example.com');
        $unverified->setPassword($this->passwordHasher->hashPassword($unverified, 'password123'));
        $unverified->setIsVerified(false);
        $manager->persist($unverified);
        $this->addReference('user-unverified', $unverified);

        $manager->flush();
    }
}
