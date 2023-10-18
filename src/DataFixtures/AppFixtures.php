<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setName('houda hari');
        $user->setEmail('houda.hari@gmail.com');
        $user->setPassword($this->userPasswordHasherInterface->hashPassword(
            $user,
            "password"
        ));
        $user->setRoles(["ROLE_USER"]); // Rôle par défaut

        $admin = new User();
        $admin->setName('jihane hari');
        $admin->setEmail('jihane@gmail.com');
        $admin->setPassword($this->userPasswordHasherInterface->hashPassword(
            $admin,
            "password"
        ));
        $admin->setRoles(["ROLE_USER", "ROLE_ADMIN"]); // Attribution du rôle 'ROLE_ADMIN'


        $manager->flush();
    }
}
