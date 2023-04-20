<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CharacterCpRepository;
use App\Services\CharacterCpManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterCpController extends AbstractController
{
    #[Route('/pool', name: 'app_character_cp', methods: 'GET')]
    public function index(UserInterface $user, CharacterCpRepository $characterCpRepository): Response
    {
        /** @var User $user */
        return $this->render('character_cp/index.html.twig', [
            'characterCps' => $characterCpRepository->findAllCpByUser($user),
        ]);
    }


    #[Route('/pool', name: 'app_cp_drop', methods: 'DELETE')]
    public function dropCp(CharacterCpManager $characterCpManager, Request $request): Response
    {
        $characterCpManager->removeCharacterOfUser($request->query->get('id'), $request->get('_token'));
        return $this->redirectToRoute('app_character_cp');
    }
}
