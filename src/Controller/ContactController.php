<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contactMe(Request $request, \Swift_Mailer $mailer)
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData(); 

            $messageMailer = (new \Swift_Message($contact['object'])) 

                ->setFrom('manduel21@gmail.com')
                ->setTo($contact['email'])
                ->setBody($contact['message']
                )
            ;

            $mailer->send($messageMailer); 
            
            $this->addFlash('message', 'Votre message nous est bien parvenu, nous nous efforcerons à vous répondre dans les plus brefs délais ');
            return $this->redirectToRoute('contact');
        }
        return $this->render('contact/contact.html.twig',[
            'contactForm'=>$form->createView()
        ]);
    }
}
