<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création de l'Administrateur
        $admin = new User();
        $admin->setEmail('admin@createch.fr');
        $admin->setRoles(['ROLE_ADMIN']);

        // On chiffre le mot de passe (sécurité obligatoire)
        $password = $this->hasher->hashPassword($admin, 'password');
        $admin->setPassword($password);

        $manager->persist($admin);

        $manager->flush();
    }
}