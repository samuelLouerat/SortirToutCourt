<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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


    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user);

            return $this->redirectToRoute('user_list', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user_profile', requirements: ["id" => "\d+"], methods: ['GET'])]
    public function profile(User $user): Response
    {
        return $this->render('user/profile.html.twig', [
            'user' => $user,
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
        EntityManagerInterface $em,
        Request                $request,
        UserRepository         $pr,
    ): Response
    {

        $us= $this->getUser()->getUserIdentifier();
        $user = $pr->findOneBy(['email' => $us]);

        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);

        if (
            $userForm->isSubmitted()
            && $userForm->isValid()
        ) {
            $em->persist($user);
            $em->flush();
            $this->addFlash(
                'Modif ok',
                'La mise à jour de votre profil est prise en compte.'
            );
            return $this->redirectToRoute(
                '/user/myprofile'
            );
        }

        return $this->render(
            'user/myProfile.html.twig',
            ['userForm' => $userForm->createView()]
        );
    }
}
