<?php

namespace App\Controller\Api\V1;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1/character', name: 'app_api_v1_character_')]
class CharacterController extends AbstractController
{
    #[Route('/', name: 'browse', methods: ['GET'])]
    public function browse(CharacterRepository $characterRepository): Response
    {
        $allCategories = $characterRepository->findAll();
       // dd($allCategories);
        return $this->json($allCategories, Response::HTTP_OK, [], ['groups' => 'api_character_browse']);
    }

    #[Route('/{id}', name: 'read',  requirements: ['id' => '\d+'], methods: ['GET'])]
    public function read(int $id, CharacterRepository $characterRepository): Response
    {
        $character = $characterRepository->find($id);

        if (is_null($character)) {
            $responseArray = [
                'error' => true,
                'message' => 'Cette categorie n\'existe pas'
            ];

            return $this->json($responseArray, Response::HTTP_NOT_FOUND);
        }

        return $this->json($character, Response::HTTP_OK, [], ['groups' => 'api_character_browse']);
    }

    #[Route('/{id}', name: 'edit', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function edit(int $id, CharacterRepository $characterRepository, Request $request, SerializerInterface $serializer, ValidatorInterface $validator,  EntityManagerInterface $entityManager): Response
    {
        // on récupère le character  qui est en BDD
        $character = $characterRepository->find($id);

        if (is_null($character)) {
            return $this->getNotFoundResponse();
        }

        // on récupère le json fournit par le client
        $jsonContent = $request->getContent();

        /*
         Désérialise ces données
         je veux obtenir un objet de la classe Character
         au fait les données sont au format json
         met à jour l'objet $character que je te fournit dans le contexte avec les données
         */
        // mettre à jour le character avec les données fournies (le deserialize s'en occupe)
        $serializer->deserialize($jsonContent, Character::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $character
        ]);

        // validation du character
        $errors = $validator->validate($character);

        // s'il y a eu au moins une erreur
        if (count($errors) > 0) {
            $reponseAsArray = [
                'error' => true,
                'message' => $errors,
            ];

            return $this->json($reponseAsArray, Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        // lancer le flush
        $entityManager->persist($character);
        $entityManager->flush();

        $reponseAsArray = [
            'message' => 'character mis à jour',
            'id' => $character->getId()
        ];

        return $this->json($reponseAsArray, Response::HTTP_CREATED);
    }

    #[Route('/', name: 'add', methods: ['POST'])]
    public function add(ValidatorInterface $validator, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $jsonContent = $request->getContent();
        $character = $serializer->deserialize($jsonContent, Character::class, 'json');

        //validation des données
        $errors = $validator->validate($character);

        // s'il y a eu au moins une erreur
        if (count($errors) > 0) {
            $reponseAsArray = [
                'error' => true,
                'message' => $errors,
            ];

            return $this->json($reponseAsArray, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entityManager->persist($character);
        $entityManager->flush();

        $responseAsArray = [
            'message' => 'character créé',
            'id' => $character->getId(),
            'name' => 'la personnage ' . $character->getFirstname() . ' a bien été créé'
        ];

        return $this->json($responseAsArray, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id, CharacterRepository $characterRepository, EntityManagerInterface $entityManager): Response
    {
        // on récupère le character  qui est en BDD
        $character = $characterRepository->find($id);

        if (is_null($character)) {
            return $this->getNotFoundResponse();
        }


        // lancer le flush
        $entityManager->remove($character);
        $entityManager->flush();

        $reponseAsArray = [
            'message' => 'character supprimé',
            'id' => $id
        ];

        return $this->json($reponseAsArray);
    }


    private function getNotFoundResponse()
    {

        $responseArray = [
            'error' => true,
            'userMessage' => 'Ressource non trouvé',
            'internalMessage' => 'Cette categorie n\'existe pas dans la BDD',
        ];

        return $this->json($responseArray, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
