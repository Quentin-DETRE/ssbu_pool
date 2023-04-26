<?php

namespace App\Controller;

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
    #[Route('/character/update/{slug}', name: 'app_update_character', methods: 'GET')]
    public function index(CharacterChoiceProvider $characterChoiceProvider, CharacterChoiceFormBuilder $characterChoiceFormBuilder, string $slug): Response
    {
        $characterChoice = $characterChoiceProvider->findCharacterChoiceByIterationNumber($slug);
        $this->denyAccessUnlessGranted('CHARACTER_CHOICE_EDIT', $characterChoice);
        return $this->render('update_character/index.html.twig', [
            'form' => $characterChoiceFormBuilder->getForm($characterChoice)->createView(),
            'character_choice' => $characterChoice,
        ]);
    }

    #[Route('/character/update/{slug}', name: 'app_process_update_character', methods: 'POST')]
    public function updateCharacterController(CharacterChoiceFormBuilder $characterChoiceFormBuilder, CharacterChoiceFormHandler $characterChoiceFormHandler, CharacterChoiceProvider $characterChoiceProvider, CharacterChoiceManager $characterChoiceManager, Request $request, string $slug): Response
    {
        $characterChoice = $characterChoiceProvider->findCharacterChoiceByIterationNumber($slug);
        $characterChoiceForm = $characterChoiceFormBuilder->getForm($characterChoice);
        $characterChoice = $characterChoiceFormHandler->handleUpdateForm($characterChoiceForm, $request);
        $characterChoiceManager->updateCharacter($characterChoice);
        $this->addFlash("success", "The characterChoice was successfully updated !");
        return $this->redirectToRoute('app_character_detail', ['iterationNumber' => $characterChoice->getIterationNumber()]);
    }
}
