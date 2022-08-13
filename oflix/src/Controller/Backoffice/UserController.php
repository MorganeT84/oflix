<?php

namespace App\Controller\Backoffice;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice/user', name: 'backoffice_user_')]
class UserController extends AbstractController
{
    #[Route('/', name: 'browse', methods: ['GET'])]
    #[IsGranted("ROLE_USER_BROWSE")]
    public function browse(UserRepository $userRepository): Response
    {
        // on fournit ce formulaire à notre vue
        return $this->render('backoffice/user/browse.html.twig', [
            'user_list' => $userRepository->findAll()
        ]);
    }


    #[Route('/read/{id}', name: 'read', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[IsGranted("ROLE_USER_READ")]
    public function read(Request $request, User $user): Response
    {
        // on créé un formulaire avec l'objet récupéré
        // on modifie dynamiquement (dans le controleur) les options du formulaire
        // pour désactiver tous les champs
        $userForm = $this->createForm(UserType::class, $user, [
            'disabled' => 'disabled',
        ]);

        $userForm
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ]);

        // on fournit ce formulaire à notre vue
        return $this->render('backoffice/user/read.html.twig', [
            'user_form' => $userForm->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    #[IsGranted("ROLE_USER_EDIT")]
    public function edit(Request $request, User $user, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);
        // dd($userForm);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $entityManager = $doctrine->getManager();

            $user->setUpdatedAt(new DateTimeImmutable());



           // dd($request->get('password'));
            $clearPassword = $request->get('password');
            // si un mot de passe a été saisi
            if (!empty($clearPassword)) {
                // hashage du mot de passe écrit en clair
                $hashedPassword = $passwordHasher->hashPassword($user, $clearPassword);
                $user->setPassword($hashedPassword);
            }
            // dd($user);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "User `{$user->getPseudo()}` udpated successfully");

            return $this->redirectToRoute('backoffice_user_browse');
        }

        // le champ mot de passe est différent en update et en ajout
        // on le rajoute au niveau du controleur
        $userForm
            ->remove('password')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,

                // comme on veut appliquer des règles de gestion non standard
                // on précise à symfony que cette valeur ne correspond à aucun 
                // champ de notre objet
                //!\ il faudra gérer la valeur saisie dans le controleur
                'mapped' => false,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ]);

        // on fournit ce formulaire à notre vue
        return $this->render('backoffice/user/add.html.twig', [
            'user_form' => $userForm->createView(),
            'user' => $user,
            'page' => 'edit',
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_USER_ADD")]
    public function add(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);


        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $entityManager = $doctrine->getManager();

            $entityManager->persist($user);

            // dd($request->get('user')['password']['first']);
            $clearPassword = $request->get('user')['password']['first'];
            // si un mot de passe a été saisi
            if (!empty($clearPassword)) {
                // hashage du mot de passe écrit en clair
                $hashedPassword = $passwordHasher->hashPassword($user, $clearPassword);
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();

            // pour opquast 
            $this->addFlash('success', "User `{$user->getPseudo()}` created successfully");

            // redirection
            return $this->redirectToRoute('backoffice_user_browse');
        }

        // on fournit ce formulaire à notre vue
        return $this->render('backoffice/user/add.html.twig', [
            'user_form' => $userForm->createView(),
            'page' => 'create',
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[IsGranted("ROLE_USER_DELETE")]
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        $this->addFlash('success', "User {$user->getId()} deleted");

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('backoffice_user_browse');
    }
}
