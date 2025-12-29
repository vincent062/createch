<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    // On injecte le service de hachage de mot de passe
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email', 'Adresse Email'),

            // Champ Rôles
            ChoiceField::new('roles', 'Rôles')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices()
                ->renderExpanded(),

            // --- MOT DE PASSE (VISIBLE) ---
            TextField::new('password', 'Nouveau mot de passe')
                ->setFormTypeOptions(['mapped' => false]) // Important : on ne lie pas directement à la BDD
                ->setRequired($pageName === Crud::PAGE_NEW)
                ->onlyOnForms(),
        ];
    }

    // Cette fonction se déclenche quand on CRÉE un utilisateur
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->hashPassword($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    // Cette fonction se déclenche quand on MODIFIE un utilisateur
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->hashPassword($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    // Notre fonction perso pour crypter le mot de passe
    private function hashPassword($user): void
    {
        // On récupère ce que vous avez tapé dans le formulaire
        $request = $this->getContext()->getRequest();
        $formData = $request->request->all('User');
        $plainPassword = $formData['password'] ?? null;

        // Si un mot de passe a été tapé, on le crypte et on l'injecte
        if ($plainPassword) {
            $user->setPassword($this->hasher->hashPassword($user, $plainPassword));
        }
    }
}