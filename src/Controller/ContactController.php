<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;

class ContactController extends AbstractController
{


    /**
     * @Route("/contact", name="contact")
     */
    public function contactMe(Request $request, \Swift_Mailer $mailer)
    {   

        $message='';
        $succeed='';

         //Création du formulaire
         $form = $this->createForm(ContactType::class);
         $form->handleRequest($request);
 
         //Vérification de la soumission et de la validité des champs soumis
         if($form->isSubmitted() && $form->isValid()){
             
             //Récupération des données soumisent
             $contact = $form->getData(); 
 
             //Création de l'oblet mail
             $messageMailer = (new \Swift_Message($contact['object'])) 


                
                 ->setFrom($contact['email'])
                 ->setTo('manduel21@gmail.com')
                 ->setReplyTo($contact['email'])
                 ->setBody($contact['message'])
             ;
              //Si l'envois réussi on génère un message approprié
              if( $mailer->send($messageMailer) ) {


                $message= 'Votre message nous est bien parvenu, nous nous efforcerons à vous répondre dans les plus brefs délais ';
                $succeed ='true';
             }
             //Dans le cas contraire on indique l'échec de l'envois
             else{
                
                $message = 'Oops il semble qu\'il y ait un problème, veuillez vérifier votre addresse email';
                $succeed = 'false' ;
             }
            }
 
        //On retourne a vue contact avec les variable associées
        return $this->render('contact/contact.html.twig',[
            'contactForm'=>$form->createView(),
            'message' => $message,
            'succeed' => $succeed
        ]);
    }
}
