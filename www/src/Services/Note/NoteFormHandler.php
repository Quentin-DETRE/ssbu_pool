<?php

namespace App\Services\Note;

use App\Entity\CharacterCp;
use App\Entity\Note;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class NoteFormHandler
{
    public function __construct()
    {
    }

    public function handleCreateForm(FormInterface $form, Request $request, CharacterCp $characterCp):?Note
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $characterCp) {
            $note = $form->getData();
            $note->setCharacterCp($characterCp);
            return $note;
        }
        return null;
    }
    public function handleUpdateForm(FormInterface $form, Request $request):Note
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->getData();
            return $note;
        }
        return new Note();
    }
}