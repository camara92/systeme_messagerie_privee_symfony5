<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
         /** @var UploadedFile $brochureFile */
         $brochureFile = $form->get('brochure')->getData();

         // this condition is needed because the 'brochure' field is not required
         // so the PDF file must be processed only when a file is uploaded
         if ($brochureFile) {
             $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
             // this is needed to safely include the file name as part of the URL
             $safeFilename = $originalFilename;
             $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

             // Move the file to the directory where brochures are stored
             try {
                 $brochureFile->move(
                     $this->getParameter('brochures_directory'),
                     $newFilename
                 );
             } catch (FileException $e) {
                 // ... handle exception if something happens during file upload
             }

             // updates the 'brochureFilename' property to store the PDF file name
             // instead of its contents
             $message->setBrochureFilename($newFilename);
         }

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

    #[Route('/received', name: 'app_received')]
    public function received(): Response
    {
        return $this->render('messages/received.html.twig', [
            'controller_name' => 'Message(s) reçu(s)',
        ]);
    }

    // read message
    #[Route('/read/{id}', name: 'app_read')]
    public function read(Messages $message, EntityManagerInterface $em ): Response
    {
        $message->setIsRead(true); 
        $em->persist($message);
        $em->flush(); 
        return $this->render('messages/read.html.twig', [
            'controller_name' => 'Message(s) lu(s',
            'message' => $message

        ]);
    } 

    // suppression des messages : 

    #[Route('/delete/{id}', name: 'app_delete')]
    public function Delete(Messages $message, EntityManagerInterface $em ): Response
    {
        // $message->setIsRead(true); 
        // $em->persist($message);
        $em->remove($message); 
        $em->flush(); 
       return $this->redirectToRoute('app_received'); 
    } 
    // voir les messages envoyés : 
    #[Route('/sent', name: 'app_sent')]
    public function sent(): Response
    {
        return $this->render('messages/sent.html.twig', [
            'controller_name' => 'Message(s) envoyé(s)',
        ]);
    }
}
