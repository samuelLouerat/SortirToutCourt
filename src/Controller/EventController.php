<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\FiltrerEventType;
use App\Form\EventType;
use App\Repository\CampusRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/list', name: 'event_list', methods: ['GET', 'POST'])]

    public function list(Request $request, EventRepository $eventRepository, EntityManagerInterface $em, CampusRepository $campusRepository): Response
    {
        $events = $eventRepository->findAll();
        $campusList = $campusRepository->findAll();

        return $this->render('event/list.html.twig',
        ['events'=>$events, 'campusList'=>$campusList]);
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

    #[Route('/{id}', name: 'event_show',requirements: ["id"=>"\d+"], methods: ['GET'])]
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

    #[Route('/{id}', name: 'event_cancel',requirements: ["id"=>"\d+"], methods: ['POST'])]
    public function delete(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $eventRepository->remove($event);
        }

        return $this->redirectToRoute('event_list', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search', name: 'event_search', methods: ['POST'])]
    public function searchlist(
        EventRepository $eventRepository,
        CampusRepository $campusRepository,
        Request $request
    ): Response {
        if ($request->isMethod('post')){

            $campusSite = $request->request->get("campus");
            $keywords = $request->request->get("keywords");
            $beginningDate = $request->request->get("beginningDate");
            $endingDate = $request->request->get("endingDate");
            $organizer = $request->request->get("organizer");
            $registered = $request->request->get("registered");
            $notRegistered = $request->request->get("notRegistered");
            $pastEvents = $request->request->get("pastEvents");

        }

        $events = $eventRepository->search($campusSite, $keywords, $beginningDate, $endingDate, $organizer, $registered, $notRegistered, $pastEvents);
        $campusList = $campusRepository->findAll();
        return $this->render('event/list.html.twig', compact("events", "campusList"));
    }
}


