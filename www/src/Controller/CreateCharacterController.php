<?php

namespace App\Controller;

use App\Entity\CharacterChoice;
use App\Form\CharacterType;
use App\Services\CharacterChoiceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class CreateCharacterController extends AbstractController
{
    #[Route('/character/create', name: 'app_create_character')]
    public function index(Request $request, EntityManagerInterface $entityManager, CharacterChoiceManager $characterChoiceManager, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $characterChoice = new CharacterChoice();
        $form = $this->createForm(CharacterType::class, $characterChoice);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($characterChoiceManager->createCharacter($form, $slugger));
            $entityManager->flush();
            return $this->redirectToRoute('app_character_choice');
        }

        return $this->render('create_character/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
