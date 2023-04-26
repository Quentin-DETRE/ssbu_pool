<?php

namespace App\Services\CharacterChoice;

use App\Entity\CharacterChoice;
use App\Model\Search\CharacterChoiceSearch;
use App\Repository\CharacterChoiceRepository;

class CharacterChoiceProvider
{
    private CharacterChoiceRepository $characterChoiceRepository;

    public function __construct(CharacterChoiceRepository $characterChoiceRepository)
    {
        $this->characterChoiceRepository = $characterChoiceRepository;
    }
    public function getCharacterChoiceListForUser(CharacterChoiceSearch $characterChoiceSearch):?array
    {
        return $this->characterChoiceRepository->getCharacterChoicesByParams($characterChoiceSearch);
    }

    public function findCharacterChoiceByIterationNumber(string $iterationNumber):?CharacterChoice
    {
        return $this->characterChoiceRepository->findOneBy(['iterationNumber' => $iterationNumber]);
    }
    public function findCharacterChoiceByNoteId($id):?CharacterChoice
    {
        $characterChoice = $this->characterChoiceRepository->findCharacterByIdNote($id);
        if (count($characterChoice) == 0 || !isset($characterChoice[0])) {
            return null;
        }
        return $characterChoice[0];
    }
}