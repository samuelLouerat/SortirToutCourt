<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('administration/place')]
class PlaceController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('place/index.html.twig', [
            'controller_name' => 'PlaceController',
        ]);
    }

    #[Route('/new', name: 'place_new', methods: ['GET', 'POST'])]
    public function new(
        EntityManagerInterface $em,
        Request                $request
    ): Response
    {
        $place = new Place();

        $formPlace = $this->createForm(PlaceType::class, $place);
        $formPlace->handleRequest($request);

        if ($formPlace->isSubmitted()) {
            $em->persist($place);
            $em->flush();
            return $this->redirectToRoute('administration', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('place/new.html.twig',
            compact("formPlace"));
    }
}
