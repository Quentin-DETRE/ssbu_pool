<?php

namespace App\Model\Search;

use App\Entity\Serie;

class CharacterChoiceSearch
{
    private ?string $name = null;

    private ?Serie $serie = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSerie(): ?Serie
    {
        return $this->serie;
    }

    public function setSerie(?Serie $serie): self
    {
        $this->serie = $serie;
        return $this;
    }


}