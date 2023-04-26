<?php

namespace App\Services\Note;

use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class NoteManager
{
    private EntityManagerInterface $entityManager;
    private CsrfTokenManagerInterface $csrfTokenManager;
    private AuthorizationCheckerInterface $authorizationChecker;


    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createOrUpdateNote(Note $note): Note
    {
        $this->authorizationChecker->isGranted('NOTE_EDIT', $note);
        $this->entityManager->persist($note);
        $this->entityManager->flush();
        return $note;
    }
    public function deleteNote(Note $note, string $token):void
    {
        $this->authorizationChecker->isGranted('NOTE_DELETE', $note);
        if ($this->csrfTokenManager->isTokenValid(new CsrfToken('delete-note' . $note->getId(), $token))) {
            $this->entityManager->remove($note);
            $this->entityManager->flush();
        }
    }
}