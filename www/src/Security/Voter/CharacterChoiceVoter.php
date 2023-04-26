<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterChoiceVoter extends Voter
{
    public const EDIT = 'CHARACTER_CHOICE_EDIT';
    public const DELETE = 'CHARACTER_CHOICE_DELETE';


    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\CharacterChoice;
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
                //dd($user->getRoles());
                foreach ($user->getRoles() as $role) {
                    if ($role == 'ROLE_ADMIN' || $role == 'ROLE_SUPER_ADMIN') {
                        return true;
                    }
                }
                return false;
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case self::DELETE:
                foreach ($user->getRoles() as $role) {
                    if ($role == 'ROLE_ADMIN' || $role == 'ROLE_SUPER_ADMIN') {
                        return true;
                    }
                }
                return false;
                // logic to determine if the user can DELETE
                // return true or false
                break;
        }

        return false;
    }
}
