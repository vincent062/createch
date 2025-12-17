<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Message')
            ->setEntityLabelInPlural('Messages reçus')
            ->setPageTitle('index', 'Liste des messages')
            ->setPageTitle('detail', fn (Contact $contact) => sprintf('Message de %s', $contact->getNom()))
            ->setDefaultSort(['created_at' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        // Sur la page index, on veut pouvoir voir le détail ("Voir") mais pas forcément "Editer"
        // car on ne modifie pas le message d'un client en général.
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::EDIT, Action::NEW); // On désactive la création manuelle et l'édition
    }

    public function configureFields(string $pageName): iterable
    {
        // --- 1. Panneau Informations Expéditeur ---
        yield FormField::addPanel('Informations de l\'expéditeur')
            ->setIcon('fas fa-id-card')
            ->setHelp('Coordonnées de la personne qui a pris contact');

        // On utilise setColumns(6) pour qu'ils prennent chacun 50% de la largeur
        yield TextField::new('nom', 'Nom complet')
            ->setColumns(6);

        yield EmailField::new('email', 'Adresse Email')
            ->setColumns(6);

        // --- 2. Panneau Contenu du Message ---
        yield FormField::addPanel('Contenu du message')
            ->setIcon('fas fa-envelope-open-text');

        yield TextField::new('sujet', 'Sujet du message')
            ->setColumns(12); // Prend toute la largeur

        yield TextareaField::new('message', 'Message')
            ->setColumns(12)
            ->renderAsHtml(false) // Affiche le texte brut
            ->stripTags();

        // --- 3. Méta-données (Date) ---
        // On affiche la date seulement en lecture (index ou detail)
        yield DateTimeField::new('created_at', 'Reçu le')
            ->setFormat('dd/MM/yyyy à HH:mm')
            ->hideOnForm(); // Caché si jamais on réactive le formulaire d'édition
    }
}