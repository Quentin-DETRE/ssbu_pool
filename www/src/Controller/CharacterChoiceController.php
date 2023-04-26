<?php

namespace App\Controller;

use App\Entity\CharacterChoice;
use App\Exception\AlreadyHaveCharacterException;
use App\Services\CharacterChoice\CharacterChoiceFormBuilder;
use App\Services\CharacterChoice\CharacterChoiceFormHandler;
use App\Services\CharacterChoice\CharacterChoiceProvider;
use App\Services\CharacterCp\CharacterCpManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterChoiceController extends AbstractController
{
    #[Route('/character', name: 'app_character_choice', methods: 'GET')]
    public function index(CharacterChoiceProvider $choiceProvider, CharacterChoiceFormBuilder $characterChoiceFormBuilder, CharacterChoiceFormHandler $characterChoiceFormHandler, Request $request): Response
    {
        $characterChoiceSearchForm = $characterChoiceFormBuilder->getSearchForm();
        $characterChoiceSearch = $characterChoiceFormHandler->handleSearchForm($characterChoiceSearchForm, $request);
        return $this->render('character_choice/index.html.twig', [
            'character_choices' => $choiceProvider->getCharacterChoiceListForUser($characterChoiceSearch),
            'form' => $characterChoiceSearchForm->createView(),
        ]);
    }

    #[Route('/character/add/{id}', name: 'app_create_character_cp', methods: 'POST')]
    public function createCharacterCp(CharacterChoice $characterChoice, CharacterCpManager $characterCpManager, UserInterface $user): Response
    {
        try {
            $characterCp = $characterCpManager->addCharacterToUser($user, $characterChoice);
            $this->addFlash('success', sprintf('Le personnage %s à bien été ajouté', $characterCp->getCharacterChoice()->getName()));
        } catch (AlreadyHaveCharacterException $e) {
            $this->addFlash('error', "You can't add a character to your pool you already have !");
        }
        return $this->redirectToRoute('app_character_cp');

    }
}
