<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Place;
use App\Entity\Town;
use App\Form\FiltrerEventType;
use App\Form\EventType;
use App\Repository\CampusRepository;
use App\Repository\EventRepository;
use App\Repository\PlaceRepository;
use App\Repository\TownRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/list', name: 'event_list', methods: ['GET'])]

    public function list(Request $request, EventRepository $eventRepository, EntityManagerInterface $em): Response
    {
        $events = $eventRepository->findAll();

        $event = new Event();
        $form = $this->createForm(FiltrerEventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('event_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/list.html.twig',
        ['form'=>$form->createView(), 'events'=>$events]);

//        return $this->renderForm('event/list.html.twig', [
//            compact("form", "events")
//        ]);
    }



    #[Route('/new', name: 'event_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $em, Request $request, EventRepository $eventRepository,CampusRepository $campusRepository, PlaceRepository $placeRepository,TownRepository $townRepository, UserRepository $userRepository): Response
    {
        $event = new Event();

        //Reading campus of connected user
        $us= $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $us]);
        $event->setOrganizer($user);
        $campusConnected = $user->getCampus()->getName();

        //All of the campus registered
        $campusRegister =$campusRepository->findAll();
        //All of the place registered
        //$placeRegister =$placeRepository->findAll();

        $formEvent = $this->createForm(EventType::class, $event);
        $formEvent->handleRequest($request);

        if ($formEvent->isSubmitted()) {
            $event->setCampusSite($user->getCampus());
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('event_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig',
            compact("formEvent","campusConnected"));
    }

    #[Route('/{id}', name: 'event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/profile.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'event_update', methods: ['GET', 'POST'])]
    public function update(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->add($event);
            return $this->redirectToRoute('event_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/update.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'event_cancel', methods: ['POST'])]
    public function delete(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $eventRepository->remove($event);
        }

        return $this->redirectToRoute('event_list', [], Response::HTTP_SEE_OTHER);
    }
}
