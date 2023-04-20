<?php

namespace App\Controller;

use App\Entity\CharacterChoice;
use App\Repository\CharacterChoiceRepository;
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
    #[Route('/character/update/{slug}', name: 'app_update_character', methods: 'GET')]
    public function index(CharacterChoiceRepository $characterChoiceRepository, string $slug): Response
    {
        $characterChoice = $characterChoiceRepository->findOneBy(['iterationNumber' => $slug]);
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        return $this->render('update_character/index.html.twig', [
            'form' =>  $this->createForm(CharacterType::class, $characterChoice)->createView(),
            'characterChoice' => $characterChoice,
        ]);
    }

    #[Route('/character/update/{slug}', name: 'app_process_update_character', methods: 'POST')]
    public function updateCharacterController(CharacterChoiceRepository $characterChoiceRepository, CharacterChoiceManager $characterChoiceManager, Request $request, string $slug, SluggerInterface $slugger): Response
    {
        $characterChoice = $characterChoiceRepository->findOneBy(['iterationNumber' => $slug]);
        $characterChoiceManager->updateCharacter($this->createForm(CharacterType::class, $characterChoice), $slugger, $request);
        return $this->redirectToRoute('app_character_detail', ['slug' => $characterChoice->getIterationNumber()]);
    }
}
