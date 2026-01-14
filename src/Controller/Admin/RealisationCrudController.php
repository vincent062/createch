<?php

namespace App\Controller\Admin;

use App\Entity\Realisation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField; // <--- IMPORTANT : On ajoute ça
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RealisationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Realisation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titre'),
            
            // ICI : Le champ magique qui génère l'URL automatiquement
            SlugField::new('slug')
                ->setTargetFieldName('titre')
                ->hideOnIndex(), 

            TextEditorField::new('description'),
            
            ImageField::new('image')
                ->setBasePath('uploads/realisations')
                ->setUploadDir('public/uploads/realisations')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),

            TextField::new('lienWeb')
                ->setLabel('Lien du site (Optionnel)')
                ->setHelp('Laissez vide pour afficher le badge "Concept Design"')
                ->setRequired(false),

            DateField::new('dateRealisation')->setLabel('Date'),
        ];
    }
}