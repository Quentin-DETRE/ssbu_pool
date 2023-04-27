<?php

namespace App\Security\Voter;

use App\Entity\Note;
use App\Entity\User;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use App\Services\Note\NoteProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class NoteVoter extends Voter
{
    public const EDIT = 'NOTE_EDIT';
    public const DELETE = 'NOTE_DELETE';
    public const CREATE = 'NOTE_CREATE';
    public const VIEW = 'NOTE_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        if (!$subject instanceof Note) {
            return false;
        }
        return in_array($attribute, [self::EDIT, self::DELETE, self::CREATE, self::VIEW])
            && $subject instanceof \App\Entity\Note;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        $subjectUser = $subject->getCharacterCp()->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                return $this->canEdit($user, $subjectUser);
                // return true or false
                break;

            case self::DELETE:
                return $this->canDelete($user, $subjectUser);
                break;

            case self::VIEW:
                return $this->canView($user, $subjectUser);
                break;

            case self::CREATE:
                return $this->canCreate($user, $subjectUser);
                break;
        }

        return false;
    }

    private function canCreate(UserInterface $user, User $subjectUser): bool
    {
        return $this->canEdit($user, $subjectUser);
    }

    private function canView(UserInterface $user, User $subjectUser): bool
    {
        return $this->canEdit($user, $subjectUser);
    }

    private function canDelete(UserInterface $user, User $subjectUser): bool
    {
        if ($user === $subjectUser || $user->getRoles() != ['ROLE_USER']) {
            return true;
        }
        return false;
    }

    private function canEdit(UserInterface $user, User $subjectUser): bool
    {
        if ($user === $subjectUser) {
            return true;
        }
        return false;
    }
}
