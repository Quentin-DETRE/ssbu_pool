<?php

namespace App\Controller;

use App\Entity\CharacterCp;
use App\Entity\User;
use App\Services\CharacterCp\CharacterCpManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterCpController extends AbstractController
{
    #[Route('/pool', name: 'app_character_cp', methods: 'GET')]
    public function index(UserInterface $user): Response
    {
        /** @var User $user */
        return $this->render('character_cp/index.html.twig', [
            'character_cps' => $user->getCharacterCps(),
        ]);
    }
    #[Route('/pool/drop/{id}', name: 'app_cp_drop', methods: 'DELETE')]
    public function dropCp(CharacterCp $characterCp, CharacterCpManager $characterCpManager, Request $request): Response
    {
        $characterCpManager->delete($characterCp, $request->get('_token'));
        $this->addFlash("success", "The character was successfully removed from your pool !");
        return $this->redirectToRoute('app_character_cp');
    }
}
