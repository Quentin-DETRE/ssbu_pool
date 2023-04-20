<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\CharacterCp;
use App\Entity\CharacterChoice;
use App\Form\NoteType;
use App\Repository\CharacterChoiceRepository;
use App\Repository\CharacterCpRepository;
use App\Repository\NoteRepository;
use App\Services\NoteManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UpdateNoteController extends AbstractController
{
    #[Route('/update/note/{id}', name: 'app_update_note', methods: 'GET')]
    public function index(EntityManagerInterface $entityManager, int $id, Request $request, NoteRepository $noteRepository, CharacterCpRepository $characterCpRepository, CharacterChoiceRepository $characterChoiceRepository): Response
    {
        $note = $noteRepository->findOneBy(['id' => $id]);
        $this->denyAccessUnlessGranted('NOTE_EDIT', $note);
        return $this->render('update_note/index.html.twig', [
            'form' => $this->createForm(NoteType::class, $note)->createView(),
            'characterChoice' => $characterChoiceRepository->findCharacterByIdNote($id),
        ]);
    }

    #[Route('/update/note/{id}', name: 'app_process_update_note', methods: 'POST')]
    public function updateNote(CharacterChoiceRepository $characterChoiceRepository, NoteManager $noteManager, int $id, Request $request, NoteRepository $noteRepository,): Response
    {
        $this->denyAccessUnlessGranted('NOTE_EDIT', $noteRepository->findOneBy(['id' => $id]));
        $form = $this->createForm(NoteType::class, $noteRepository->findOneBy(['id' => $id]));
        $noteManager->updateNote($id, $request, $form);
        return $this->redirectToRoute('app_character_detail', ['slug' => $characterChoiceRepository->findCharacterByIdNote($id)[0]->getIterationNumber()]);;
    }
}
