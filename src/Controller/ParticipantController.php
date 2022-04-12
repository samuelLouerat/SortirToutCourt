<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/participant')]
class ParticipantController extends AbstractController
{


    #[Route('/', name: 'participant_index', methods: ['GET'])]
    public function index(ParticipantRepository $participantRepository): Response
    {
        return $this->render('participant/index.html.twig', [
            'participants' => $participantRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'participant_new', methods: ['GET', 'POST'])]

    public function new(Request $request, ParticipantRepository $participantRepository): Response
    {
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participantRepository->add($participant);

            return $this->redirectToRoute('participant_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('participant/new.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participant_show', methods: ['GET'])]
    public function show(Participant $participant): Response
    {
        return $this->render('participant/show.html.twig', [
            'participant' => $participant,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_participant_edit', methods: ['GET', 'POST'])]

    public function edit(Request $request, Participant $participant, ParticipantRepository $participantRepository): Response
    {
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participantRepository->add($participant);
            return $this->redirectToRoute('app_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participant/edit.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }


    #[Route('/delete/{id}', name: 'app_participant_delete', methods: ['POST'])]

    public function delete(Request $request, Participant $participant, ParticipantRepository $participantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participant->getId(), $request->request->get('_token'))) {
            $participantRepository->remove($participant);
        }

        return $this->redirectToRoute('app_participant_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/myprofil/{id}', name: 'participant_myprofil')]
    public function myProfil(
        EntityManagerInterface $em,
        Request $request,
        ParticipantRepository $pr,
        int $id
    ): Response
    {
        $participant = $pr->find($id);

        $participantForm = $this->createForm(ParticipantType::class, $participant);

        $participantForm->handleRequest($request);

        if(
            $participantForm->isSubmitted()
            && $participantForm->isValid()
        ){
            $em->persist($participant);
            $em->flush();
            $this->addFlash(
                'Modif ok',
                'La mise à jour de votre profil est prise en compte.'
            );
            return $this->redirectToRoute('/participant/myprofil/{id}');
        }

        return $this->render(
            'participant/myprofil.html.twig',
            ['participantForm' => $participantForm->createView()]
        );
    }




}
