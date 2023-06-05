<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Repository\MessagesRepository;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiMessagesController extends AbstractController
{
    #[Route('/api/messag', name: 'api_messagess',  methods: 'GET')]
    public function listeMessages(MessagesRepository $MessagesRepository, SerializerInterface $serializer): Response
    {
        $Messages = $MessagesRepository->findAll();
        // dump($Messages);
        // die();
        $result = $serializer->serialize($Messages, 'json', [
            'groups' => ['listMessagesFull']
        ]);
        // return $this->render('api_Messages/index.html.twig', [
        //     'controller_name' => 'ApiMessagesController',
        // ]);
        // true : permet de savoir si serialiser sinon le faire en format que l'on veut 
        return new JsonResponse($result, 200, [], true);
    }

    #[Route('/api/messag/{id}', name: 'api_messagess_show', methods: 'GET')]
    public function ShowMessages(Messages $grenre,  SerializerInterface $serializer): Response
    {

        $result = $serializer->serialize($grenre, 'json', [
            'groups' => ['listMessagesFull']
        ]);

        return new JsonResponse($result, Response::HTTP_OK, [], true);
    }

    #[Route('/api/messag/', name: 'api_messagess_create', methods: 'POST')]
    public function CreateMessages(Request $request,  SerializerInterface $serializer,  EntityManagerInterface $manager, ValidatorInterface $validator): Response
    {

        $data = $request->getContent();
        // $Messages = new Messages();
        // $serializer = $serializer->deserialize($data, Messages::class, 'json', [ 'object_to_populate' => $Messages ]);
        //methode 2 : 
        $Messages = $serializer->deserialize($data ,Messages::class, 'json');
        // contrainte de validation de l'objet : 
        $errors = $validator->validate($Messages); 

        if(count($errors)){
            $errorsJson = $serializer->serialize($errors, 'json');
            return  new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        $manager->persist($Messages);
        $manager->flush();
        return new JsonResponse(
            
            'Le nouveau Messages a bien été crée. Merci Daouda',
            Response::HTTP_CREATED, [
            'location' => 'api/messag/' . $Messages->getId()
            // renvoyer la nouvelle header 
        ], true);

        //   ['location'=>$this->generateUrl('api/Messagess_show', ['id'=>$Messages->getId(), UrlGeneratorInterface::ABSOLUTE_URL])];


    }

    // edit 
    #[Route('/api/messag/{id}', name: 'api_messagess_update', methods: 'PUT')]
    public function Edit( Messages $Messages,  Request $request,  SerializerInterface $serializer,  EntityManagerInterface $manager, ValidatorInterface $validator): Response
    {

        $data = $request->getContent();
        $serializer->deserialize($data ,Messages::class, 'json', ['object_to_populate'=>$Messages ]);
        // gestion des erreurs 
        $errors = $validator->validate($Messages); 
        if(count($errors)){
            $errorsJson = $serializer->serialize($errors, 'json');
            return  new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        } 
        $manager->persist($Messages);
        $manager->flush();
        return new JsonResponse(
            
            'Le nouveau Messages a bien été modifié. Merci Daouda',
            Response::HTTP_OK,[], true);

       


    }

    #[Route('/api/messag/{id}', name: 'api_messagess_delete', methods: 'DELETE')]
    public function Delete( Messages $Messages,   EntityManagerInterface $manager): Response
    {

       // $data = $request->getContent();
       
        $manager->remove($Messages);
        $manager->flush();
        return new JsonResponse(
            
            'Le nouveau messages a bien été supprimé. Merci Daouda',
            Response::HTTP_OK,[], false);

       


    }
}
