<?php

namespace App\Services\CharacterCp;

use App\Entity\CharacterChoice;
use App\Entity\CharacterCp;
use App\Entity\User;
use App\Repository\CharacterCpRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterCpProvider
{
    private CharacterCpRepository $characterCpRepository;

    public function __construct(CharacterCpRepository $characterCpRepository)
    {
        $this->characterCpRepository = $characterCpRepository;
    }
    public function findCharacterCpByUserAndCharacterChoice(UserInterface $user, CharacterChoice $characterChoice):?array
    {
        return $this->characterCpRepository->findBy(['user' => $user, 'characterChoice' => $characterChoice]);
    }
    public function findByCharacterChoice(CharacterChoice $characterChoice):?array
    {
        return $this->characterCpRepository->findBy(['characterChoice' => $characterChoice]);
    }
    public function findCharacterCpByIterationNumberAndUser(string $iterationNumber, UserInterface $user):?CharacterCp
    {
        /** @var User $user */
        $characterCps =$this->characterCpRepository->findCharacterDetail($iterationNumber,$user);
        if(count($characterCps) == 0 || !isset($characterCps[0])){
            return null;
        }
        return $characterCps[0];
    }
}