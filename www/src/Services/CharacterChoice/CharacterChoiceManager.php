<?php

namespace App\Services\CharacterChoice;

use App\Entity\CharacterChoice;
use App\Repository\NoteRepository;
use App\Services\Note\NoteProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CharacterChoiceManager
{
    private EntityManagerInterface $entityManager;

    private NoteProvider $noteProvider;
    private CsrfTokenManagerInterface $csrfTokenManager;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(EntityManagerInterface $entityManager, NoteProvider $noteProvider, CsrfTokenManagerInterface $csrfTokenManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->entityManager = $entityManager;
        $this->noteProvider = $noteProvider;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createCharacter(CharacterChoice $characterChoice): CharacterChoice
    {
        $this->authorizationChecker->isGranted('CHARACTER_CHOICE_EDIT', $characterChoice);
        $this->entityManager->persist($characterChoice);
        $this->entityManager->flush();
        return $characterChoice;
    }

    public function updateCharacter(CharacterChoice $characterChoice): CharacterChoice
    {
        $this->entityManager->persist($characterChoice);
        $this->entityManager->flush();
        return $characterChoice;
    }

    public function deleteCharacter( array $characterCps, CharacterChoice $characterChoice, string $token): void
    {
        $this->authorizationChecker->isGranted('CHARACTER_CHOICE_DELETE', $characterChoice);
        if ($this->csrfTokenManager->isTokenValid(new CsrfToken('delete-character-choice' . $characterChoice->getId(), $token))) {
            $filesystem = new Filesystem();
            foreach ($characterCps as $characterCp) {
                $notes = $this->noteProvider->findByCharacterCp($characterCp);
                foreach ($notes as $note) {
                    $this->entityManager->remove($note);
                }

                $this->entityManager->remove($characterCp);
            }

            if ($characterChoice->getImagePath() != 'Mario_SSBU.png') {
                $filesystem->remove('fighters/' . $characterChoice->getImagePath());
                $filesystem->remove('fighters/250_' . $characterChoice->getImagePath());
            }
            $this->entityManager->remove($characterChoice);
            $this->entityManager->flush();
        }
    }


}