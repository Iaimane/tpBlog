<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request, MailerInterface $mailer): Response //objet request contient toutes less infos de la demande du navigateur ex: afficher la page, envoi de données ( qui sont mis dans la requête HTTP)
    {   
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);  // Pour récupérer les données du formulaire
        if ($form->isSubmitted() && $form ->isValid()) {//si des données sont présentes et valides
            $addressMail = $form->get('email')->getData();
            $subject = $form->get('subject')->getData();
            $content = $form->get('content')->getData(); 
            //les  méthodes getData() recupèrent les valeurs entrées dans le formulaire.

            $email = (new Email()) //ici on récupere la classe Email de symfony pour envoyer un mail
            ->from($addressMail)    //L'expéditeur
            ->to('admin@admin.com')    //Le destinataire
            ->subject($subject)
            ->text($content);

            $mailer->send($email);   //Envoi du mail
            return $this->redirectToRoute('app_success');
        }    
        

        return $this->renderForm('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form
        ]);
    }

     /**
     * @Route("/contact/success", name="app_success")
     */
    public function success(): Response
    {
        return $this->render('success/index.html.twig', [
            'controller_name' => 'SuccessController',
        ]);
    }
}