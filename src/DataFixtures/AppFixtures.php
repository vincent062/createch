<?php

namespace App\DataFixtures;

use App\Entity\Prestation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Création de l'admin
        $admin = new User();
        $admin->setEmail('admin@createch.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($admin, 'password');
        $admin->setPassword($password);
        $manager->persist($admin);

        // 2. Création de fausses prestations
        $slugger = new AsciiSlugger();

        $services = [
            'Site Vitrine' => 'Présentez votre activité avec un site élégant et performant.',
            'Boutique E-commerce' => 'Vendez vos produits en ligne 24h/24 avec une solution sécurisée.',
            'Référencement SEO' => 'Améliorez votre visibilité sur Google et attirez plus de clients.',
            'Maintenance Web' => 'Gardez votre site à jour et sécurisé sans vous soucier de la technique.'
        ];

        foreach ($services as $titre => $desc) {
            $prestation = new Prestation();
            $prestation->setTitre($titre);
            // On génère le slug automatiquement (ex: "Site Vitrine" -> "site-vitrine")
            $prestation->setSlug(strtolower($slugger->slug($titre)));
            $prestation->setDescriptionCourte($desc);
            $prestation->setContenu("<h1>$titre</h1><p>Voici le détail complet de l'offre $titre. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>");
            // Pas d'image pour l'instant (null)
            
            $manager->persist($prestation);
        }

        $manager->flush();
    }
}