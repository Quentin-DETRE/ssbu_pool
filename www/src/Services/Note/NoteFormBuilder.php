<?php

namespace App\Services\Note;

use App\Entity\Note;
use App\Form\NoteType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class NoteFormBuilder
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function getForm(?Note $note = null):FormInterface
    {
        return $this->formFactory->create(NoteType::class, $note ?? new Note());
    }

}