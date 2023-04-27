<?php

namespace App\Services\Note;

use App\Entity\CharacterCp;
use App\Entity\Note;
use App\Repository\CharacterCpRepository;
use App\Repository\NoteRepository;

class NoteProvider
{
    private CharacterCpRepository $characterCpRepository;
    private NoteRepository $noteRepository;

    public function __construct(NoteRepository $noteRepository, CharacterCpRepository $characterCpRepository)
    {
        $this->characterCpRepository = $characterCpRepository;
        $this->noteRepository = $noteRepository;
    }
    public function findByCharacterCp(CharacterCp $characterCp):?array
    {
        return $this->noteRepository->findBy(['characterCp' => $characterCp]);
    }
    public function findAllNotesByCharacterCp(CharacterCp $characterCp): ?array
    {
        return $this->noteRepository->findAllNotesByCharacterCp($characterCp);
    }
    public function findNotesByIterationNumber(string $iterationNumber):?   array
    {
        return $this->noteRepository->findByInterationNumber($iterationNumber);
    }

}