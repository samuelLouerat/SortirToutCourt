<?php

namespace App\Controller;

use App\Entity\Town;
use App\Form\TownType;
use App\Repository\EventRepository;
use App\Repository\PlaceRepository;
use App\Repository\TownRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/town')]
class TownController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('town/index.html.twig', [
            'controller_name' => 'TownController',
        ]);
    }

    #[Route('/new', name: 'town_new', methods: ['GET', 'POST'])]
    public function new(
        EntityManagerInterface $em,
        Request                $request,
        PlaceRepository        $placeRepository,
        TownRepository         $townRepository,
        UserRepository         $userRepository
    ): Response
    {
        $town = new Town();

        $formTown = $this->createForm(TownType::class, $town);
        $formTown->handleRequest($request);

        if ($formTown->isSubmitted()) {
            $em->persist($town);
            $em->flush();
            return $this->redirectToRoute('administration', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('town/new.html.twig',
            compact("formTown"));
    }

    #[Route('/list', name: 'Town_list', methods: ['GET', 'POST'])]
    public function list(
        Request                $request,
        EventRepository        $eventRepository,
        EntityManagerInterface $em,
        TownRepository         $townRepository
    ): Response
    {
        $town = $townRepository->findAll();
        return $this->render('town/list.html.twig',

            ['town' => $town]);
    }
}