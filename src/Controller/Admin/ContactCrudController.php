<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

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
            ->setPageTitle('index', 'Boîte de réception 📬')
            ->setPageTitle('detail', fn (Contact $contact) => sprintf('Message de %s', $contact->getNom()))
            ->setDefaultSort(['created_at' => 'DESC'])
            // On garde notre super template personnalisé pour la vue "Détail"
            ->overrideTemplate('crud/detail', 'admin/contact/detail.html.twig');
    }

    public function configureFilters(Filters $filters): Filters
    {
        // Ajout de filtres sur la droite pour rechercher facilement
        return $filters
            ->add(TextFilter::new('nom', 'Nom de l\'expéditeur'))
            ->add(TextFilter::new('email', 'Adresse Email'))
            ->add(DateTimeFilter::new('created_at', 'Date de réception'));
    }

    public function configureActions(Actions $actions): Actions
    {
        $viewAction = Action::DETAIL;

        return $actions
            ->add(Crud::PAGE_INDEX, $viewAction)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setIcon('fas fa-eye')->setLabel(''); // Juste l'icône pour gagner de la place
            })
            ->disable(Action::NEW, Action::EDIT) // Pas de bouton "Ajouter" ni "Editer"
            ->remove(Crud::PAGE_DETAIL, Action::DELETE); // On évite de supprimer depuis le détail par erreur
    }

    public function configureFields(string $pageName): iterable
    {
        // --- 1. VUE LISTE (INDEX) ---
        if (Crud::PAGE_INDEX === $pageName) {
            // Petite astuce : on utilise AvatarField qui génère des initiales colorées
            yield AvatarField::new('nom')
                ->setLabel('')
                
                ->setIsGravatarEmail('email'); // Si l'email a un Gravatar, il s'affiche !

            yield TextField::new('nom', 'Expéditeur')->setSortable(true);
            
            yield TextField::new('sujet', 'Sujet');
            
            // On affiche le début du message seulement
            yield TextField::new('message', 'Aperçu')
                ->setMaxLength(50)
                ->stripTags(); 

            yield DateTimeField::new('created_at', 'Reçu le')
                ->setFormat('dd MMM yyyy à HH:mm')
                ->setSortable(true);

            return; // On arrête là pour la liste
        }

        // --- 2. VUE DÉTAIL (Ce qu'on a fait avant) ---
        yield FormField::addPanel('Informations de l\'expéditeur')
            ->setIcon('fas fa-id-card');

        yield TextField::new('nom', 'Nom complet')->setColumns(6);
        yield EmailField::new('email', 'Adresse Email')->setColumns(6);

        yield FormField::addPanel('Contenu du message')
            ->setIcon('fas fa-envelope-open-text');

        yield TextField::new('sujet', 'Sujet du message')->setColumns(12);
        yield TextareaField::new('message', 'Message')
            ->setColumns(12)
            ->renderAsHtml(false)
            ->stripTags();

        yield DateTimeField::new('created_at', 'Reçu le')
            ->setFormat('dd/MM/yyyy à HH:mm');
    }
}