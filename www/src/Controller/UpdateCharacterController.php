<?php

namespace App\Controller;

use App\Entity\CharacterChoice;
use App\Services\CharacterChoiceManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CharacterType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class UpdateCharacterController extends AbstractController
{
    #[Route('/character/update/{slug}', name: 'app_update_character')]
    public function index(EntityManagerInterface $entityManager, CharacterChoiceManager $characterChoiceManager, Request $request, string $slug, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $characterChoice = $entityManager->getRepository(CharacterChoice::class)->findOneBy(['iterationNumber' => $slug]);
        $form = $this->createForm(CharacterType::class, $characterChoice);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($characterChoiceManager->updateCharacter($form, $slugger));
            $entityManager->flush();
            return $this->redirectToRoute('app_character_detail', ['slug' => $characterChoice->getIterationNumber()]);
        }
        return $this->render('update_character/index.html.twig', [
            'form' => $form->createView(),
            'characterChoice' => $characterChoice,
        ]);
    }
}
