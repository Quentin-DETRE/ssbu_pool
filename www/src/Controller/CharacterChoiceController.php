<?php

namespace App\Controller;

use App\Entity\CharacterChoice;
use App\Entity\CharacterCp;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterChoiceController extends AbstractController
{
    #[Route('/character', name: 'app_character_choice', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,): Response
    {
        $characterChoices = $entityManager->getRepository(CharacterChoice::class)->getAllCharacterChoices();

        return $this->render('character_choice/index.html.twig', [
            'characterChoices' => $characterChoices,
        ]);
    }

    #[Route('/character/add/{id}', name: 'app_create_character_cp', methods: 'POST')]
    public function createCharacterCp(EntityManagerInterface $entityManager, UserInterface $user, int $id): Response
    {
        $characterChoice = $entityManager->getRepository(CharacterChoice::class)->findOneBy(['id' => $id]);
        if (!$entityManager->getRepository(CharacterCp::class)->findBy(['user' => $user, 'characterChoice' => $characterChoice])) {
            $characterCp = new CharacterCp();
            $characterCp->setUser($user);
            $characterCp->setCharacterChoice($characterChoice);
            $entityManager->persist($characterCp);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_character_cp');
    }
}
