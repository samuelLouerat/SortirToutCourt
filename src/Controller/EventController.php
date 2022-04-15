<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\FiltrerEventType;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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
            ['form' => $form->createView(), 'events' => $events]);

//        return $this->renderForm('event/list.html.twig', [
//            compact("form", "events")
//        ]);
    }


    #[Route('/new', name: 'event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRepository $eventRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->add($event);
            return $this->redirectToRoute('event_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'event_show', requirements: ["id" => "\d+"], methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'event_update', requirements: ["id" => "\d+"], methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'event_cancel', requirements: ["id" => "\d+"], methods: ['POST'])]
    public function delete(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $eventRepository->remove($event);
        }

        return $this->redirectToRoute('event_list', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/subscribe', name: 'event_subscribe', requirements: ["id" => "\d+"])]
    public function registered(
        ManagerRegistry $doctrine,
        UserRepository  $pr,
        EntityManagerInterface $em,
        int             $id
    ): Response
    {
        $entityManager = $doctrine->getManager();
        $event = $entityManager->getRepository(Event::class)->find($id);
        // $us = $this->getUser()->getUserIdentifier();
        $user = $pr->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
                                                                                                                                                                                                 ;

        if ($event->getUsers()->contains($user->getId())) {
            $event->removeUserParticipation($user);
        } else {
            $event->addUserParticipation($user);
        }
        $em->persist($event);
        $em->flush();

        return $this->redirectToRoute('event_list', [], Response::HTTP_SEE_OTHER);
    }
}
