<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($i . 'user@user.com');

            $passwordHashed = $this->userPasswordHasher->hashPassword($user, 'user');

            $user->setPassword($passwordHashed);
            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);

            $this->addReference('user' . $i, $user);
        }

        $user = new User();
        $user->setEmail('admin@admin.com');

        $passwordHashed = $this->userPasswordHasher->hashPassword($user, 'admin');

        $user->setPassword($passwordHashed);
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);

        $manager->flush();
    }
}
