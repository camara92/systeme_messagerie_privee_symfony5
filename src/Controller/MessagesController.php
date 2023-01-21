<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    #[Route('/messages', name: 'app_messages')]
    public function index(): Response
    {
        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
        ]);
    }

    #[Route('/send', name: 'app_send')]
    public function send(Request $request, EntityManagerInterface $entityManager ) :Response {
       $message = new Messages(); 
       $form = $this->createForm(MessagesType::class, $message ); 
        // traitement du formulaire : 
         $form->handleRequest($request);    
       if($form->isSubmitted() && $form->isValid()){
        $message->setSender($this->getUser());
        $entityManager->persist($message);
        $entityManager->flush();
        // $em->persist($message);
        // $em->flush();

        $this->addFlash('message', 'Message envoyé avec succès.');

        return $this->redirectToRoute('app_messages');
       }
        return $this->render('messages/sender.html.twig', [
            'controller_name' => 'Envoie de message ',
            'form'=>$form->createView()


        ]);
    }
}
