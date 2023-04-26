<?php

namespace App\Controller;

use App\Entity\CharacterChoice;
use App\Services\CharacterChoice\CharacterChoiceFormBuilder;
use App\Services\CharacterChoice\CharacterChoiceFormHandler;
use App\Services\CharacterChoice\CharacterChoiceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CreateCharacterController extends AbstractController
{
    #[Route('/character/create', name: 'app_create_character', methods: 'GET')]
    public function index(CharacterChoiceFormBuilder $characterChoiceFormBuilder): Response
    {
        $this->denyAccessUnlessGranted('CHARACTER_CHOICE_EDIT', new CharacterChoice());
        return $this->render('create_character/index.html.twig', [
            'form' => $characterChoiceFormBuilder->getForm()->createView(),
        ]);
    }

    #[Route('/character/create', name: 'app_process_create_character', methods:'POST')]
    public function createCharacterChoice(Request $request, CharacterChoiceFormBuilder $characterChoiceFormBuilder, CharacterChoiceFormHandler $characterChoiceFormHandler, CharacterChoiceManager $characterChoiceManager, SluggerInterface $slugger): Response
    {
        $characterChoiceForm = $characterChoiceFormBuilder->getForm();
        $characterChoiceFormResult = $characterChoiceFormHandler->handleCreateForm($characterChoiceForm,$request);
        $characterChoiceManager->createCharacter($characterChoiceFormResult);
        $this->addFlash("success", "The characterChoice was successfully created !");
        return $this->redirectToRoute('app_character_choice');
    }
}
