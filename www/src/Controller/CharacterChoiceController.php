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
    #[Route('/character', name: 'app_character_choice')]
    public function index(EntityManagerInterface $entityManager, Request $request, UserInterface $user): Response
    {
        $fighters = $entityManager->getRepository(CharacterChoice::class)->findAll();

        if ($request->query->get("id")) {
            $fighter = $entityManager->getRepository(CharacterChoice::class)->findOneBy(['id'=>$request->query->get('id')]);
            if (!$entityManager->getRepository(CharacterCp::class)->findBy(['user'=>$user, 'characterChoice' => $fighter])) {
                $cp = new CharacterCp();
                $cp->setUser($user);
                $cp->setCharacterChoice($fighter);
                $entityManager->persist($cp);
                $entityManager->flush();
            }
            return $this->redirectToRoute('app_character_cp');
        }
        return $this->render('character_choice/index.html.twig', [
            'fighters' => $fighters,
        ]);
    }

}
