<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\CharacterCp;
use App\Entity\CharacterChoice;
use App\Form\NoteType;
use App\Repository\CharacterChoiceRepository;
use App\Repository\CharacterCpRepository;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UpdateNoteController extends AbstractController
{
    #[Route('/update/note/{id}', name: 'app_update_note', methods: 'GET')]
    public function index(EntityManagerInterface $entityManager, int $id, Request $request, NoteRepository $noteRepository, CharacterCpRepository $characterCpRepository, CharacterChoiceRepository $characterChoiceRepository): Response
    {

        $note = $noteRepository->findOneBy(['id' => $id]);
        $this->denyAccessUnlessGranted('NOTE_EDIT', $note);
        $characterCp = $characterCpRepository->findOneBy(['id' => $note->getCharacterCp()->getId()]);
        $CharacterChoice = $characterChoiceRepository->findOneBy(['id' => $characterCp->getCharacterChoice()->getId()]);

        $form = $this->createForm(NoteType::class, $note);


        return $this->render('update_note/index.html.twig', [
            'controller_name' => 'UpdateNoteController',
            'form' => $form->createView(),
            'characterChoice' => $CharacterChoice,
        ]);
    }

    #[Route('/update/note/{id}', name: 'app_process_update_note', methods: 'POST')]
    public function createNote(EntityManagerInterface $entityManager, int $id, Request $request, NoteRepository $noteRepository, CharacterCpRepository $characterCpRepository, CharacterChoiceRepository $characterChoiceRepository): Response
    {
        $this->denyAccessUnlessGranted('NOTE_EDIT', $noteRepository->findOneBy(['id' => $id]));

        $characterCp = $characterCpRepository->findOneBy(['id' =>  $noteRepository->findOneBy(['id' => $id])->getCharacterCp()->getId()]);
        $CharacterChoice = $characterChoiceRepository->findOneBy(['id' => $characterCp->getCharacterChoice()->getId()]);

        $form = $this->createForm(NoteType::class, $noteRepository->findOneBy(['id' => $id]));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->getData();
            $entityManager->persist($note);
            $entityManager->flush();

        }
        return $this->redirectToRoute('app_character_detail', ['slug' => $CharacterChoice->getIterationNumber()]);;
    }
}
