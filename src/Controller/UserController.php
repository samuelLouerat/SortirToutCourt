<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\AdminUserFormType;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/user')]
class UserController extends AbstractController
{

    #[Route('/list', name: 'user_list', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/list.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }


    #[Route('/{id}', name: 'user_profile', requirements: ["id" => "\d+"], methods: ['GET'])]
    public function profile(User $user): Response
    {
        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/admin/edit', name: 'admin_user_edit', requirements: ["id" => "\d+"], methods: ['GET', 'POST'])]
    public function adminedit(Request $request, User $user, UserRepository $userRepository, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(AdminUserFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('user_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/admin_update.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'user_edit', requirements: ["id" => "\d+"], methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user);
            return $this->redirectToRoute('user_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/update.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/delete/{id}', name: 'user_delete', requirements: ["id" => "\d+"], methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        return $this->redirectToRoute('user_list', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/myprofile', name: 'user_myprofile')]
    public function myProfile(
        EntityManagerInterface      $em,
        Request                     $request,
        UserRepository              $pr,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response
    {

        $us = $this->getUser()->getUserIdentifier();
        $user = $pr->findOneBy(['email' => $us]);
        $oldPassword = $user->getPassword();

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if (
            $userForm->isSubmitted()
            && $userForm->isValid()
        ) {
            $newPassword = $userForm->get('password')->getData();

            if ($userPasswordHasher->isPasswordValid($user, $newPassword)) {
                $em->persist($user);
                $em->flush();
                $this->addFlash(
                    'Modifok',
                    'La mise à jour de votre profil est prise en compte.'
                );
            } else {
                $this->addFlash(
                    'ModifNok',
                    'Le mot de passe est incorrect .'
                );
                return $this->render(
                    'user/myProfile.html.twig', ['userForm' => $userForm->createView()]
                );
            }
            return $this->redirectToRoute(
                'event_list'
            );
        }else {
            $user->setImageFile(null);
        }

        return $this->render(
            'user/myProfile.html.twig', ['userForm' => $userForm->createView()]
        );

    }

}
