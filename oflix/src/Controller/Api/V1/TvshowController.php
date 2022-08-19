<?php

namespace App\Controller\Api\V1;

use App\Entity\TvShow;
use App\Repository\TvShowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1/tvshow', name: 'app_api_v1_tvshow_')]
class TvshowController extends AbstractController
{
    #[Route('/', name: 'browse', methods: ['GET'])]
    public function browse(TvShowRepository $tvShowRepository): Response
    {
        $allTvShows = $tvShowRepository->findAll();
        return $this->json($allTvShows, Response::HTTP_OK, [], ['groups' => 'api_tvshows_browse']);
    }

    #[Route('/{id}', name: 'read',  requirements: ['id' => '\d+'], methods: ['GET'])]
    public function read(int $id, TvShowRepository $tvShowRepository): Response
    {
        $tvShow = $tvShowRepository->find($id);

        if (is_null($tvShow)) {
            $responseArray = [
                'error' => true,
                'message' => 'Ce Tv show n\'existe pas'
            ];

            return $this->json($responseArray, Response::HTTP_NOT_FOUND);
        }

        return $this->json($tvShow, Response::HTTP_OK, [], ['groups' => 'api_tvshows_browse']);
    }

    #[Route('/{id}', name: 'edit', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function edit(int $id, TvShowRepository $tvShowRepository, Request $request, SerializerInterface $serializer, ValidatorInterface $validator,  EntityManagerInterface $entityManager): Response
    {
        // on récupère le tvshow  qui est en BDD
        $tvShow = $tvShowRepository->find($id);

        if (is_null($tvShow)) {
            return $this->getNotFoundResponse();
        }

        // on récupère le json fournit par le client
        $jsonContent = $request->getContent();

        /*
         Désérialise ces données
         je veux obtenir un objet de la classe TvShow
         au fait les données sont au format json
         met à jour l'objet $tvShow que je te fournit dans le contexte avec les données
         */
        // mettre à jour le tvshow avec les données fournies (le deserialize s'en occupe)
        $serializer->deserialize($jsonContent, TvShow::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $tvShow
        ]);

        // validation du tvshow
        $errors = $validator->validate($tvShow);

        // s'il y a eu au moins une erreur
        if (count($errors) > 0) {
            $reponseAsArray = [
                'error' => true,
                'message' => $errors,
            ];

            return $this->json($reponseAsArray, Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        // lancer le flush
        $entityManager->persist($tvShow);
        $entityManager->flush();

        $reponseAsArray = [
            'message' => 'Tvshow mis à jour',
            'id' => $tvShow->getId()
        ];

        return $this->json($reponseAsArray, Response::HTTP_CREATED);
    }

    #[Route('/', name: 'add', methods: ['POST'])]
    public function add(ValidatorInterface $validator, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $jsonContent = $request->getContent();
        $tvShow = $serializer->deserialize($jsonContent, Tvshow::class, 'json');

        //validation des données
        $errors = $validator->validate($tvShow);

        // s'il y a eu au moins une erreur
        if (count($errors) > 0) {
            $reponseAsArray = [
                'error' => true,
                'message' => $errors,
            ];

            return $this->json($reponseAsArray, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entityManager->persist($tvShow);
        $entityManager->flush();

        $responseAsArray = [
            'message' => 'tvshow créé',
            'id' => $tvShow->getId(),
            'name' => 'le tv show ' . $tvShow->getTitle() . ' a bien été créé'
        ];

        return $this->json($responseAsArray, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id, TvShowRepository $tvShowRepository, EntityManagerInterface $entityManager): Response
    {
        // on récupère le tvshow  qui est en BDD
        $tvShow = $tvShowRepository->find($id);

        if (is_null($tvShow)) {
            return $this->getNotFoundResponse();
        }


        // lancer le flush
        $entityManager->remove($tvShow);
        $entityManager->flush();

        $reponseAsArray = [
            'message' => 'Tvshow supprimé',
            'id' => $id
        ];

        return $this->json($reponseAsArray);
    }

    private function getNotFoundResponse()
    {

        $responseArray = [
            'error' => true,
            'userMessage' => 'Ressource non trouvé',
            'internalMessage' => 'Ce tv show n\'existe pas dans la BDD',
        ];

        return $this->json($responseArray, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
