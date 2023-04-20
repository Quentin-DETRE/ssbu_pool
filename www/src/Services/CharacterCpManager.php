<?php

namespace App\Services;

use App\Entity\CharacterChoice;
use App\Entity\CharacterCp;
use App\Entity\User;
use App\Repository\CharacterCpRepository;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Response;

class CharacterCpManager
{
    private EntityManagerInterface $entityManager;
    private CharacterCpRepository $characterCpRepository;
    private NoteRepository $noteRepository;

    public function __construct(EntityManagerInterface $entityManager, CharacterCpRepository $characterCpRepository, NoteRepository $noteRepository)
    {
        $this->entityManager = $entityManager;
        $this->characterCpRepository = $characterCpRepository;
        $this->noteRepository = $noteRepository;
    }

    public function addCharacterToUser(User $user, CharacterChoice $characterChoice): ?CharacterCp
    {
        if ($this->characterCpRepository->findBy(['user' => $user, 'characterChoice' => $characterChoice])) {
            return null;
        }
        $characterCp = new CharacterCp();
        $characterCp->setUser($user);
        $characterCp->setCharacterChoice($characterChoice);
        $this->entityManager->persist($characterCp);
        $this->entityManager->flush();
        return $characterCp;
    }

    public function removeCharacterOfUser(int $id, string $token)
    {
        $characterCp = $this->characterCpRepository->findOneBy(['id' => $id]);
        $notes = $this->noteRepository->findBy(['characterCp' => $characterCp]);
        if ($this->isCsrfTokenValid('delete' . $characterCp->getId(), $token)) {
            if ($notes) {
                foreach ($notes as $note) {
                    $this->entityManager->remove($note);
                }
            }
            $this->entityManager->remove($characterCp);
            $this->entityManager->flush();
        }
    }
}