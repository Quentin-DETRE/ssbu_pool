<?php

namespace App\Controller;


use App\Entity\CharacterChoice;
use App\Entity\CharacterCp;
use App\Entity\Note;
use App\Security\Voter\CharacterChoiceVoter;
use App\Security\Voter\NoteVoter;
use App\Services\CharacterChoice\CharacterChoiceManager;
use App\Services\CharacterChoice\CharacterChoiceProvider;
use App\Services\CharacterCp\CharacterCpProvider;
use App\Services\Note\NoteFormBuilder;
use App\Services\Note\NoteFormHandler;
use App\Services\Note\NoteManager;
use App\Services\Note\NoteProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterDetailController extends AbstractController
{
    #[Route('/character/number/{iterationNumber}', name: 'app_character_detail', methods: ['GET', 'POST'])]
    #[ParamConverter('characterCp', CharacterCp::class)]
    public function index(CharacterCp $characterCp, Request $request, NoteFormHandler $noteFormHandler, NoteManager $noteManager, NoteFormBuilder $noteFormBuilder, NoteProvider $noteProvider): Response
    {
        $noteCreateForm = $noteFormBuilder->getForm();
        $note = $noteFormHandler->handleCreateForm($noteCreateForm, $request, $characterCp);
        if ($note) {
            $this->denyAccessUnlessGranted(NoteVoter::CREATE, $note);
            $noteManager->createOrUpdateNote($note);
            $this->addFlash("success", "The note was successfully created !");
        }
        if ($noteCreateForm->isSubmitted() && !$noteCreateForm->isValid())  {
            $this->addFlash('error', "The note could not be created");
        }
        return $this->render('character_detail/index.html.twig', [
            'character_cp' => $characterCp,
            'notes' => $noteProvider->findAllNotesByCharacterCp($characterCp),
            'form' => $noteCreateForm->createView(),
        ]);
    }

    #[Route('/character/number/{iterationNumber}', name: 'app_character_detail_delete_character', methods: "DELETE")]
    public function deleteCharacter(CharacterChoice $characterChoice, CharacterCpProvider $characterCpProvider, CharacterChoiceProvider $characterChoiceProvider, CharacterChoiceManager $characterDetailManager, string $iterationNumber, Request $request): Response
    {
        $this->denyAccessUnlessGranted(CharacterChoiceVoter::DELETE, $characterChoice);
        $characterCps = $characterCpProvider->findByCharacterChoice($characterChoice);
        $characterDetailManager->deleteCharacter($characterCps, $characterChoice, $request->get('_token'));
        $this->addFlash("success", "The characterChoice was successfully deleted !");
        return $this->redirectToRoute('app_character_cp');
    }

    #[Route('/character/number/{iterationNumber}/delete/note/{id}', name: 'app_character_detail_delete_note', methods: "DELETE")]
    public function deleteNote(Request $request, Note $note, NoteManager $noteManager, string $iterationNumber): Response
    {
        $this->denyAccessUnlessGranted(NoteVoter::DELETE, $note);
        $noteManager->deleteNote($note, $request->get('_token'));
        $this->addFlash("success", "The note was successfully deleted !");
        return $this->redirectToRoute('app_character_detail', ['iterationNumber' => $iterationNumber]);
    }
}
