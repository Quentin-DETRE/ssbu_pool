<?php

namespace App\Services\CharacterChoice;

use App\Entity\CharacterChoice;
use App\Form\CharacterChoiceSearchType;
use App\Form\CharacterChoiceType;
use App\Model\Search\CharacterChoiceSearch;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class CharacterChoiceFormBuilder
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function getSearchForm(): FormInterface
    {
        return $this->formFactory->create(CharacterChoiceSearchType::class, new CharacterChoiceSearch());
    }

    public function getForm(?CharacterChoice $characterChoice = null): FormInterface
    {
        return $this->formFactory->create(CharacterChoiceType::class, $characterChoice ?? new CharacterChoice());
    }
}