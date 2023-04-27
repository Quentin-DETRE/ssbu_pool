<?php

namespace App\Controller;

use App\Entity\CharacterChoice;
use App\Security\Voter\CharacterChoiceVoter;
use App\Services\CharacterChoice\CharacterChoiceFormBuilder;
use App\Services\CharacterChoice\CharacterChoiceFormHandler;
use App\Services\CharacterChoice\CharacterChoiceManager;
use App\Services\CharacterChoice\CharacterChoiceProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateCharacterChoiceController extends AbstractController
{
    #[Route('/character/update/{iterationNumber}', name: 'app_update_character', methods: 'GET')]
    public function index(CharacterChoice $characterChoice, CharacterChoiceFormBuilder $characterChoiceFormBuilder): Response
    {
        $this->denyAccessUnlessGranted(CharacterChoiceVoter::EDIT, $characterChoice);
        return $this->render('update_character/index.html.twig', [
            'form' => $characterChoiceFormBuilder->getForm($characterChoice)->createView(),
            'character_choice' => $characterChoice,
        ]);
    }

    #[Route('//character/update/{iterationNumber}', name: 'app_process_update_character', methods: 'POST')]
    public function updateCharacterController(CharacterChoice $characterChoice, CharacterChoiceFormBuilder $characterChoiceFormBuilder, CharacterChoiceFormHandler $characterChoiceFormHandler, CharacterChoiceProvider $characterChoiceProvider, CharacterChoiceManager $characterChoiceManager, Request $request): Response
    {
        $this->denyAccessUnlessGranted(CharacterChoiceVoter::EDIT, $characterChoice);
        $characterChoiceForm = $characterChoiceFormBuilder->getForm($characterChoice);
        $characterChoice = $characterChoiceFormHandler->handleUpdateForm($characterChoiceForm, $request);
        $characterChoiceManager->updateCharacter($characterChoice);
        $this->addFlash("success", "The characterChoice was successfully updated !");
        return $this->redirectToRoute('app_character_detail', ['iterationNumber' => $characterChoice->getIterationNumber()]);
    }
}
