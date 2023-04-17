<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\CharacterCp;
use App\Entity\CharacterChoice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class UpdateNoteController extends AbstractController
{
    #[Route('/update/note/{id}', name: 'app_update_note')]
    public function index(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {

        $note = $entityManager->getRepository(Note::class)->findOneBy(['id'=> $id]);
        $this->denyAccessUnlessGranted('NOTE_EDIT', $note);
        $cp = $entityManager->getRepository(CharacterCp::class)->findOneBy(['id' => $note->getCharacterCp()->getId()]);
        $fighter = $entityManager->getRepository(CharacterChoice::class)->findOneBy(['id' => $cp->getCharacterChoice()->getId()]);

        $form = $this->createFormBuilder($note) 
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $note = $form->getData();
            $entityManager->persist($note);
            $entityManager->flush();
            return $this->redirectToRoute('app_character_detail', ['slug' => $fighter->getIterationNumber()]);
        }
        
        return $this->render('update_note/index.html.twig', [
            'controller_name' => 'UpdateNoteController',
            'form' => $form->createView(),
            'fighter' => $fighter,
        ]);
    }
}
