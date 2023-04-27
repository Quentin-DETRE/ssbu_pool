<?php

namespace App\Controller;

use App\Entity\CharacterChoice;
use App\Security\Voter\CharacterChoiceVoter;
use App\Services\CharacterChoice\CharacterChoiceFormBuilder;
use App\Services\CharacterChoice\CharacterChoiceFormHandler;
use App\Services\CharacterChoice\CharacterChoiceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateCharacterController extends AbstractController
{
    #[Route('/character/create', name: 'app_create_character', methods: ['GET', 'POST'])]
    public function index(CharacterChoiceFormBuilder $characterChoiceFormBuilder, CharacterChoiceFormHandler $characterChoiceFormHandler, CharacterChoiceManager $characterChoiceManager, Request $request): Response
    {
        $this->denyAccessUnlessGranted(CharacterChoiceVoter::CREATE, new CharacterChoice());
        $characterChoiceForm = $characterChoiceFormBuilder->getForm();
        $characterChoice = $characterChoiceFormHandler->handleCreateForm($characterChoiceForm, $request);
        dump($request);
        if ($characterChoice) {
            $this->denyAccessUnlessGranted(CharacterChoiceVoter::CREATE, $characterChoice);
            $characterChoiceManager->createCharacter($characterChoice);
            $this->addFlash("success", "The characterChoice was successfully created !");
            return $this->redirectToRoute('app_character_choice');
        }
        return $this->render('create_character/index.html.twig', [
            'form' => $characterChoiceForm->createView(),
        ]);
    }
}
