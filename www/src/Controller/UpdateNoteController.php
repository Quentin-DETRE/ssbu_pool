<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\CharacterCp;
use App\Entity\CharacterChoice;
use App\Form\NoteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UpdateNoteController extends AbstractController
{
    #[Route('/update/note/{id}', name: 'app_update_note')]
    public function index(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {

        $note = $entityManager->getRepository(Note::class)->findOneBy(['id' => $id]);
        $this->denyAccessUnlessGranted('NOTE_EDIT', $note);
        $characterCp = $entityManager->getRepository(CharacterCp::class)->findOneBy(['id' => $note->getCharacterCp()->getId()]);
        $CharacterChoice = $entityManager->getRepository(CharacterChoice::class)->findOneBy(['id' => $characterCp->getCharacterChoice()->getId()]);

        $form = $this->createForm(NoteType::class, $note);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->getData();
            $entityManager->persist($note);
            $entityManager->flush();
            return $this->redirectToRoute('app_character_detail', ['slug' => $CharacterChoice->getIterationNumber()]);
        }

        return $this->render('update_note/index.html.twig', [
            'controller_name' => 'UpdateNoteController',
            'form' => $form->createView(),
            'characterChoice' => $CharacterChoice,
        ]);
    }
}
