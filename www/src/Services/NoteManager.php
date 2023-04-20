<?php

namespace App\Services;

use App\Entity\Note;
use App\Entity\User;
use App\Form\NoteType;
use App\Repository\CharacterChoiceRepository;
use App\Repository\CharacterCpRepository;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class NoteManager
{
    private CharacterChoiceRepository $characterChoiceRepository;
    private CharacterCpRepository $characterCpRepository;
    private EntityManagerInterface $entityManager;
    private UserInterface $user;
    private FormInterface $form;
    private NoteRepository $noteRepository;

    public function __construct(EntityManagerInterface $entityManager,NoteRepository $noteRepository, CharacterChoiceRepository $characterChoiceRepository, CharacterCpRepository $characterCpRepository)
    {

        $this->characterChoiceRepository = $characterChoiceRepository;
        $this->characterCpRepository = $characterCpRepository;
        $this->entityManager = $entityManager;
        $this->noteRepository = $noteRepository;
    }

    public function createNote(string $slug, Request $request, FormInterface $form, User $user): Note
    {
        $characterChoice = $this->characterChoiceRepository->findOneBy(['iterationNumber' => $slug]);
        $characterCp = $this->characterCpRepository->findOneBy(['user' => $user, 'characterChoice' => $characterChoice]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->getData();
            $note->setCharacterCp($characterCp);
            $this->entityManager->persist($note);
            $this->entityManager->flush();
        }
        $form->handleRequest($request);
        return $note;
    }

    public function updateNote(int $id, Request $request, $form): ?Note
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->getData();
            $this->entityManager->persist($note);
            $this->entityManager->flush();
            return $note;
        }
        return null;
    }

    public function deleteNote(Note $note)
    {

            $this->entityManager->remove($note);
            $this->entityManager->flush();

    }
}