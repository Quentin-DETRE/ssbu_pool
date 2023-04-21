<?php

namespace App\Controller;

use App\Entity\CharacterChoice;
use App\Entity\CharacterCp;
use App\Entity\Serie;
use App\Form\SearchbarType;
use App\Repository\CharacterChoiceRepository;
use App\Repository\CharacterCpRepository;
use App\Services\CharacterChoiceManager;
use App\Services\CharacterCpManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class CharacterChoiceController extends AbstractController
{
    #[Route('/character', name: 'app_character_choice', methods: 'GET')]
    public function index(CharacterChoiceRepository $characterChoiceRepository, CharacterChoiceManager $characterChoiceManager, Request $request): Response
    {
        $form = $this->createForm(SearchbarType::class);
        return $this->render('character_choice/index.html.twig', [
            'characterChoices' => $characterChoiceRepository->getCharacterChoicesByParams($characterChoiceManager->handleSearch($form, $request)),
            'form' =>  $form->createView(),
        ]);
    }

    #[Route('/character/add/{id}', name: 'app_create_character_cp', methods: 'POST')]
    public function createCharacterCp(CharacterChoice $characterChoice, CharacterCpManager $characterCpManager, UserInterface $user, EntityManagerInterface $entityManager): Response
    {
        $characterCp = $characterCpManager->addCharacterToUser($user, $characterChoice);
        if ($characterCp){
            $this->addFlash('success', sprintf('Le personnage %s à bien été ajouté', $characterCp->getCharacterChoice()->getName()));
        }
        else {
            $this->addFlash('success', 'You already have this character in your pool');
        }
        return $this->redirectToRoute('app_character_cp');
    }
}
