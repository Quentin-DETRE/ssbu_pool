<?php

namespace App\Security\Voter;

use App\Entity\Note;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class NoteVoter extends Voter
{
    public const EDIT = 'NOTE_EDIT';
    public const DELETE = 'NOTE_DELETE';
    public const VIEW = 'NOTE_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        if (!$subject instanceof Note) {
            return false;
        }
        return in_array($attribute, [self::EDIT, self::DELETE, self::VIEW])
            && $subject instanceof \App\Entity\Note;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                if ($user == $subject->getCharacterCp()->getUser()) {
                    return true;
                }
                return false;
                // return true or false
                break;

            case self::DELETE:
                if ($user == $subject->getCharacterCp()->getUser() || $user->getRoles() != []) {
                    return true;
                }
                return false;
                break;

            case self::VIEW:
                if ($user == $subject->getCharacterCp()->getUser()) {
                    return true;
                }
                return false;
                break;
        }

        return false;
    }
}
