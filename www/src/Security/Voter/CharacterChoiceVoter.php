<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\CharacterChoice;

class CharacterChoiceVoter extends Voter
{
    public const EDIT = 'CHARACTER_CHOICE_EDIT';
    public const DELETE = 'CHARACTER_CHOICE_DELETE';
    public const CREATE = 'CHARACTER_CHOICE_CREATE';


    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE, self::CREATE])
            && $subject instanceof CharacterChoice;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        //dd($user);
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($user);
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case self::DELETE:
                return $this->canDelete($user);
                // logic to determine if the user can DELETE
                // return true or false
                break;
            case self::CREATE:
                return $this->canCreate($user);
                break;
        }

        return false;
    }

    private function canCreate(UserInterface $user):bool
    {
        return $this->canEdit($user);
    }
    private function canEdit(UserInterface $user):bool
    {
        foreach ($user->getRoles() as $role) {
            if ($role == 'ROLE_ADMIN' || $role == 'ROLE_SUPER_ADMIN') {
                return true;
            }
        }
        return false;
    }
    private function canDelete(UserInterface $user):bool
    {
        return $this->canEdit($user);
    }
}
