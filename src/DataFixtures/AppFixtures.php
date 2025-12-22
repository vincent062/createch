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

        // 2. Création des prestations avec contenu détaillé
        $slugger = new AsciiSlugger();

        // On change la structure : Clé = Titre, Valeur = [Description Courte, Contenu HTML Long]
        $services = [
            'Site Vitrine' => [
                'short' => 'Présentez votre activité avec un site élégant et performant.',
                'content' => '
                    <h2 class="text-2xl font-bold mb-4 text-primary">Votre image de marque, sublimée sur le web</h2>
                    <p class="mb-4 text-lg">
                        À l\'ère du numérique, ne pas avoir de site web revient à être invisible. Notre offre <strong>Site Vitrine</strong> est conçue pour les artisans, PME et professions libérales qui souhaitent asseoir leur crédibilité.
                    </p>
                    
                    <h3 class="text-xl font-bold mt-6 mb-2">Ce que nous incluons :</h3>
                    <ul class="list-disc list-inside space-y-2 mb-6 ml-4">
                        <li><strong>Design Unique :</strong> Une charte graphique adaptée à votre identité visuelle.</li>
                        <li><strong>Responsive Design :</strong> Un affichage parfait sur ordinateurs, tablettes et smartphones.</li>
                        <li><strong>Optimisation SEO :</strong> Une structure technique pensée pour plaire à Google.</li>
                        <li><strong>Interface d\'Administration :</strong> Modifiez vos textes et photos en toute autonomie.</li>
                        <li><strong>Formulaire de Contact :</strong> Recevez les demandes de devis directement dans votre boîte mail.</li>
                    </ul>

                    <div class="alert alert-info shadow-lg mt-8">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current flex-shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Hébergement et nom de domaine offerts la première année !</span>
                        </div>
                    </div>
                '
            ],
            'Boutique E-commerce' => [
                'short' => 'Vendez vos produits en ligne 24h/24 avec une solution sécurisée.',
                'content' => '
                    <h2 class="text-2xl font-bold mb-4 text-primary">Lancez votre boutique en ligne</h2>
                    <p>Ouvrez votre zone de chalandise au monde entier. Nous développons des boutiques performantes, sécurisées et faciles à gérer.</p>
                    <ul class="list-disc list-inside mt-4">
                        <li>Paiement sécurisé (Stripe, PayPal)</li>
                        <li>Gestion des stocks</li>
                        <li>Espace client dédié</li>
                    </ul>
                '
            ],
            'Applications Mobiles' => [
                'short' => 'Votre application native iOS et Android disponible sur les stores.',
                'content' => '
                    <h2 class="text-2xl font-bold mb-4 text-primary">Soyez dans la poche de vos clients</h2>
                    <p>Développement d\'applications natives ou hybrides pour offrir une expérience utilisateur fluide et engageante.</p>
                '
            ],
            'Référencement SEO' => [
                'short' => 'Améliorez votre visibilité sur Google et attirez plus de clients.',
                'content' => '
                    <h2 class="text-2xl font-bold mb-4 text-primary">Dominez les résultats de recherche</h2>
                    <p>Un beau site ne sert à rien si personne ne le voit. Nos experts SEO optimisent votre contenu pour vous placer en tête.</p>
                '
            ],
            'Maintenance Web' => [
                'short' => 'Gardez votre site à jour et sécurisé sans vous soucier de la technique.',
                'content' => '
                    <h2 class="text-2xl font-bold mb-4 text-primary">Dormez sur vos deux oreilles</h2>
                    <p>Mises à jour de sécurité, sauvegardes journalières et corrections de bugs. Nous veillons sur votre site.</p>
                '
            ]
        ];

        foreach ($services as $titre => $data) {
            $prestation = new Prestation();
            $prestation->setTitre($titre);
            $prestation->setSlug(strtolower($slugger->slug($titre)));
            $prestation->setDescriptionCourte($data['short']);
            
            // ICI : On injecte le vrai contenu HTML
            $prestation->setContenu($data['content']);
            
            $manager->persist($prestation);
        }

        $manager->flush();
    }
}