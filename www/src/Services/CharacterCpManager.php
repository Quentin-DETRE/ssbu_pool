<?php

namespace App\Services;

use App\Entity\CharacterChoice;
use App\Entity\CharacterCp;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CharacterCpManager
{
    public function addCharacterToUser(User $user, CharacterChoice $characterChoice): CharacterCp
    {
        $characterCp = new CharacterCp();
        $characterCp->setUser($user);
        $characterCp->setCharacterChoice($characterChoice);

        return $characterCp;

    }
}