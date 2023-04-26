<?php

namespace App\Controller;


use App\Entity\Note;
use App\Entity\User;
use App\Services\CharacterChoice\CharacterChoiceManager;
use App\Services\CharacterChoice\CharacterChoiceProvider;
use App\Services\CharacterCp\CharacterCpProvider;
use App\Services\Note\NoteFormBuilder;
use App\Services\Note\NoteFormHandler;
use App\Services\Note\NoteManager;
use App\Services\Note\NoteProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterDetailController extends AbstractController
{
    #[Route('/character/number/{iterationNumber}', name: 'app_character_detail', methods: ['GET', 'POST'])]
    public function index(string $iterationNumber, NoteFormHandler $noteFormHandler, Request $request, NoteManager $noteManager, UserInterface $user, CharacterCpProvider $characterCpProvider, NoteFormBuilder $noteFormBuilder, NoteProvider $noteProvider): Response
    {
        $characterCp = $characterCpProvider->findCharacterCpByIterationNumberAndUser($iterationNumber, $user);
        if (!$characterCp) {
            return $this->redirectToRoute("app_character_cp");
        }
        $noteCreateForm = $noteFormBuilder->getForm();
        $note = $noteFormHandler->handleCreateForm($noteCreateForm, $request, $characterCp);
        if ($note) {
            $noteManager->createOrUpdateNote($note);
            $this->addFlash("success", "The note was successfully created !");
        }
        if ($noteCreateForm->isSubmitted() && !$noteCreateForm->isValid())  {
            $this->addFlash('error', "The note could not be created");
        }
        return $this->render('character_detail/index.html.twig', [
            'character_cp' => $characterCp,
            'notes' => $noteProvider->findNotesByIterationNumber($iterationNumber),
            'form' => $noteCreateForm->createView(),
        ]);
    }

    #[Route('/character/number/{iterationNumber}', name: 'app_character_detail_delete_character', methods: "DELETE")]
    public function deleteCharacter(CharacterCpProvider $characterCpProvider, CharacterChoiceProvider $characterChoiceProvider, CharacterChoiceManager $characterDetailManager, string $iterationNumber, Request $request): Response
    {
        $characterChoice = $characterChoiceProvider->findCharacterChoiceByIterationNumber($iterationNumber);
        $characterCps = $characterCpProvider->findByCharacterChoice($characterChoice);
        $characterDetailManager->deleteCharacter($characterCps, $characterChoice, $request->get('_token'));
        $this->addFlash("success", "The characterChoice was successfully deleted !");
        return $this->redirectToRoute('app_character_cp');
    }

    #[Route('/character/number/{iterationNumber}/delete/note/{id}', name: 'app_character_detail_delete_note', methods: "DELETE")]
    public function deleteNote(Note $note, NoteManager $noteManager, string $iterationNumber, Request $request): Response
    {
        $noteManager->deleteNote($note, $request->get('_token'));
        $this->addFlash("success", "The note was successfully deleted !");
        return $this->redirectToRoute('app_character_detail', ['slug' => $iterationNumber]);
    }
}
