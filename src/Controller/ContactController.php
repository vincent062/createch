<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface; // <-- Import du Mailer
use Symfony\Component\Mime\Email;             // <-- Import de l'Email
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On sauvegarde en base de données
            $entityManager->persist($contact);
            $entityManager->flush();

            
            // 2. Envoi de l'email 
            
            $email = (new Email())
                ->from('contact@createch-wa.fr')      // (autorisé par o2switch)
                ->replyTo($contact->getEmail())       
                ->to('contact@createch-wa.fr')
                ->subject('Nouveau message de ' . $contact->getNom()) 
                ->text($contact->getMessage())
                ->html('<p>' . nl2br($contact->getMessage()) . '</p>');

            // 3. Message de succès
            $this->addFlash('success', 'Votre message a bien été envoyé ! Nous vous répondrons très vite.');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}

