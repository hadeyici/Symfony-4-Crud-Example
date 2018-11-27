<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
      $form = $this->createForm(ContactType::class);
      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){
        $contactFormData = $form->getData();

        $message = (new \Swift_Message('Eposta geldi'))
          ->setFrom($contactFormData['email'])   //formdan gelen eposta
          ->setTo('bitingmumbler@mailinator.com')
          ->setBody(
              $contactFormData['message'], 'text\plain' //formdan gelen mesaj
          );
        $mailer->send($message);

        $this->addFlash('success', 'Mesajınız gönderildi');

        return $this->redirectToRoute('contact');

      }
        return $this->render('contact/index.html.twig', [
            'our_form' => $form->createView(),
        ]);
    }
}

