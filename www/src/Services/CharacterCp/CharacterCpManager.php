<?php

namespace App\Services\CharacterCp;

use App\Entity\CharacterChoice;
use App\Entity\CharacterCp;
use App\Entity\User;
use App\Exception\AlreadyHaveCharacterException;
use App\Services\Note\NoteProvider;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Form\Exception\AlreadySubmittedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CharacterCpManager
{
    private EntityManagerInterface $entityManager;

    private CharacterCpProvider $characterCpProvider;
    private CsrfTokenManagerInterface $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, CharacterCpProvider $characterCpProvider, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;

        $this->characterCpProvider = $characterCpProvider;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * @throws AlreadyHaveCharacterException
     */
    public function addCharacterToUser(UserInterface $user, CharacterChoice $characterChoice): CharacterCp
    {
        if ($this->characterCpProvider->findCharacterCpByUserAndCharacterChoice($user,$characterChoice))
        {
            throw new AlreadyHaveCharacterException();
        }
        $characterCp = new CharacterCp();
        $characterCp->setUser($user);
        $characterCp->setCharacterChoice($characterChoice);
        $this->entityManager->persist($characterCp);
        $this->entityManager->flush();
        return $characterCp;
    }

    public function delete(CharacterCp $characterCp, string $token):void
    {
        if ($this->csrfTokenManager->isTokenValid(new CsrfToken('delete-character-cp' . $characterCp->getId(),$token))) {
            $this->entityManager->remove($characterCp);
            $this->entityManager->flush();
        }
    }
}