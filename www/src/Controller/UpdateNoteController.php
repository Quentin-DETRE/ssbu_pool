<?php

namespace App\Controller;

use App\Entity\Note;
use App\Security\Voter\NoteVoter;
use App\Services\CharacterChoice\CharacterChoiceProvider;
use App\Services\Note\NoteFormBuilder;
use App\Services\Note\NoteFormHandler;
use App\Services\Note\NoteManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateNoteController extends AbstractController
{
    #[Route('/note/update/{id}', name: 'app_update_note', methods: 'GET')]
    public function index(Note $note, NoteFormBuilder $noteFormBuilder, CharacterChoiceProvider $characterChoiceProvider): Response
    {
        $this->denyAccessUnlessGranted(NoteVoter::EDIT, $note);
        return $this->render('update_note/index.html.twig', [
            'form' => $noteFormBuilder->getForm($note)->createView(),
            'character_choice' => $characterChoiceProvider->findCharacterChoiceByNoteId($note->getId()),
        ]);
    }

    #[Route('/note/update/{id}', name: 'app_process_update_note', methods: 'POST')]
    public function updateNote(Note $note, CharacterChoiceProvider $characterChoiceProvider, NoteFormHandler $noteFormHandler, NoteFormBuilder $noteFormBuilder, NoteManager $noteManager,  Request $request): Response
    {
        $this->denyAccessUnlessGranted(NoteVoter::EDIT, $note);
        $noteForm = $noteFormBuilder->getForm($note);
        $noteFormResult = $noteFormHandler->handleUpdateForm($noteForm, $request);
        $noteManager->createOrUpdateNote($noteFormResult);
        $this->addFlash("success", "The note was successfully updated !");
        return $this->redirectToRoute('app_character_detail', ['iterationNumber' => $characterChoiceProvider->findCharacterChoiceByNoteId($note->getId())->getIterationNumber()]);
    }
}
