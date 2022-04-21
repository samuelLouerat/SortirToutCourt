<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\EventCancelType;
use App\Form\FiltrerEventType;
use App\Entity\Place;
use App\Entity\Town;
use App\Form\EventType;
use App\Repository\CampusRepository;
use App\Repository\EventRepository;

use App\Repository\StateRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\PlaceRepository;
use App\Repository\TownRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[Route('/event')]
class EventController extends AbstractController
{

    #[Route('/list', name: 'event_list', methods: ['GET', 'POST'])]

    public function list(Request $request,StateRepository $stateRepository, EventRepository $eventRepository, EntityManagerInterface $em, CampusRepository $campusRepository): Response

    {

        $events = $eventRepository->findAll();
        $campusList = $campusRepository->findAll();
       //state event
        foreach($events as $e){
            if ($e->getState()!== $stateRepository->findOneBy(['id' => 6]) ) {
                if (date_add( $e->getStartTime(),$e->getDuration()) < new \DateTime('now')) {
                    $e->setState($stateRepository->findOneBy(['id' => 5]));
                    $em->persist($e);
                    $em->flush();
                }else{
                if ($e->getRegistrationTimeLimit() < new \DateTime('now')) {
                    $e->setState($stateRepository->findOneBy(['id' => 3]));
                    $em->persist($e);
                    $em->flush();
                }else{
                    if( $e->getStartTime()>new \DateTime('now')){
                        $e->setState($stateRepository->findOneBy(['id' => 2]));
                        $em->persist($e);
                        $em->flush();
                    }
                }}
            }
        }

        $campusSite=null;
        $keywords=null;
        $beginningDate=null;
        $endingDate=null;
        $organizer=null;
        $registered=null;
        $notRegistered=null;
        $pastEvents=null;


        return $this->render('event/list.html.twig',

        ['events'=>$events, 'campusList'=>$campusList, 'campusSite'=>$campusSite, 'keywords'=>$keywords, 'beginningDate'=>$beginningDate, 'endingDate'=>$endingDate, 'organizer'=>$organizer, 'registered'=>$registered, 'notRegistered'=>$notRegistered, 'pastEvents'=>$pastEvents]);

    }
    #[Route('/place/{id}', name: 'event_place', requirements: ["id" => "\d+"], methods: ['GET', 'POST'])]
    public function place(Place $place,SerializerInterface $serializer){

        return new Response($serializer->serialize($place,'json',['groups'=>['getPlace']]));

    }

    #[Route('/new', name: 'event_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $em, Request $request,StateRepository $stateRepository, EventRepository $eventRepository,CampusRepository $campusRepository, PlaceRepository $placeRepository,TownRepository $townRepository, UserRepository $userRepository): Response
    {
        $event = new Event();

        //Reading connected user
        $us= $this->getUser()->getUserIdentifier();


        $user = $userRepository->findOneBy(['email' => $us]);
        $event->setOrganizer($user);
        $event->setStartTime(new \DateTime('now'));
        $event->setRegistrationTimeLimit(new \DateTime('now'));
        $campusConnected = $user->getCampus();


        $formEvent = $this->createForm(EventType::class, $event);
        $formEvent->handleRequest($request);
        $event->setCampusSite($user->getCampus());
        if ($formEvent->isSubmitted()) {
            $event->setState($stateRepository->findOneBy(['id' => '1']));
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('event_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig',
            compact("formEvent","campusConnected","event"));
    }


    #[Route('/{id}', name: 'event_show', requirements: ["id" => "\d+"], methods: ['GET'])]

    public function show(Event $event, UserRepository $ur): Response
    {
        $me = $this->getUser()->getUserIdentifier();
        $userConnected = $ur->findOneBy(['email' => $me]);
        $AuthoriseShowUserProfil=false;
        foreach($event->getUsers() as $u) {
            if($u==$userConnected){
                $AuthoriseShowUserProfil=true;
            }
        }

        return $this->render('event/show.html.twig', [
            'event' => $event,'authorize'=>$AuthoriseShowUserProfil
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
            'formEvent' => $form,
        ]);
    }


    #[Route('/{id}/cancel', name: 'event_cancel', requirements: ["id" => "\d+"], methods: ['GET','POST'])]

    public function cancel(EntityManagerInterface $em,Request $request, Event $event,UserRepository $userRepository,StateRepository $stateRepository,EventRepository $eventRepository): Response
    {
        $us= $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $us]);

        if ($event->getOrganizer() == $user) {
            $event->setEventInfo("");
            $form = $this->createForm(EventCancelType::class, $event);
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {
                $event->setState($stateRepository->findOneBy(['id' => 6]));

                $em->persist($event);
                $em->flush();
                return $this->redirectToRoute('event_list', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('event/cancel.html.twig', [
                'event' => $event,
                'formEvent' => $form,
            ]);
        }else{
            return $this->redirectToRoute('event_list', [], Response::HTTP_SEE_OTHER);
        }
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
        $user = $pr->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);;

        if ($event->getUsers()->contains($user->getId())) {
            $event->removeUserParticipation($user);
        } else {
            $event->addUserParticipation($user);

            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_list', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/{id}/unsubscribe', name: 'event_unsubscribe', requirements: ["id" => "\d+"])]
    public function removeRegistered(
        ManagerRegistry $doctrine,
        UserRepository  $pr,
        EntityManagerInterface $em,
        int             $id
    ): Response
    {

        $entityManager = $doctrine->getManager();
        $event = $entityManager->getRepository(Event::class)->find($id);
        $user = $pr->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);;

            $event->removeUserParticipation($user);

        $em->persist($event);
        $em->flush();

        return $this->redirectToRoute('event_list', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search', name: 'event_search', methods: ['POST'])]
    public function searchlist(
        EventRepository $eventRepository,
        UserRepository $userRepository,
        CampusRepository $campusRepository,
        Request $request
    ): Response {
        if ($request->isMethod('post')){

            $campusSite = $request->request->get("campusSite");
            $keywords = $request->request->get("keywords");
            $beginningDate = $request->request->get("beginningDate");
            $endingDate = $request->request->get("endingDate");
            $organizer = $request->request->get("organizer");
            $registered = $request->request->get("registered");
            $notRegistered = $request->request->get("notRegistered");
            $pastEvents = $request->request->get("pastEvents");

        }

        $userMail = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $userMail]);

        $events = $eventRepository->search($campusSite, $keywords, $beginningDate, $endingDate, $organizer,$user, $registered, $notRegistered, $pastEvents);
        $campusList = $campusRepository->findAll();
        return $this->render('event/list.html.twig', compact("events", "campusList", "campusSite", "keywords", "beginningDate", "endingDate", "organizer", "registered", "notRegistered","pastEvents"));

    }
}


