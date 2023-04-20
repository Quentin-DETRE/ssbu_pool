<?php

namespace App\Controller;

use App\Entity\CharacterChoice;
use App\Entity\CharacterCp;
use App\Repository\CharacterChoiceRepository;
use App\Repository\CharacterCpRepository;
use App\Services\CharacterCpManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterChoiceController extends AbstractController
{
    #[Route('/character', name: 'app_character_choice', methods: 'GET')]
    public function index(CharacterChoiceRepository $characterChoiceRepository): Response
    {
        return $this->render('character_choice/index.html.twig', [
            'characterChoices' => $characterChoiceRepository->getAllCharacterChoices(),
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
