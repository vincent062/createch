<?php

namespace App\Controller\Admin;

use App\Entity\Prestation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField; // <-- Changement ici (Textarea au lieu de TextEditor)
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PrestationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Prestation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            
            // ATTENTION : Utilisez bien 'titre' et non 'title'
            TextField::new('titre'),
            
            // Configuration de l'image
            ImageField::new('image')
                ->setBasePath('uploads/prestations')
                ->setUploadDir('public/uploads/prestations')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),

            TextField::new('slug')->setHelp('Laissez vide pour générer automatiquement'),
            TextField::new('description_courte'),
            // C'est ICI que ça change : On permet de coller du code HTML brut
            TextareaField::new('contenu')->renderAsHtml(),
        ];
    }
}