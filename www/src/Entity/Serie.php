<?php

namespace App\Entity;

use App\Repository\SerieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SerieRepository::class)]
class Serie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $imagePath = null;

    #[ORM\OneToMany(mappedBy: 'serie', targetEntity: CharacterChoice::class)]
    private Collection $characterChoices;

    public function __construct()
    {
        $this->characterChoices = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * @return Collection<int, CharacterChoice>
     */
    public function getCharacterChoices(): Collection
    {
        return $this->characterChoices;
    }

    public function addCharacterChoice(CharacterChoice $characterChoice): self
    {
        if (!$this->characterChoices->contains($characterChoice)) {
            $this->characterChoices->add($characterChoice);
            $characterChoice->setSerie($this);
        }

        return $this;
    }

    public function removeCharacterChoice(CharacterChoice $characterChoice): self
    {
        if ($this->characterChoices->removeElement($characterChoice)) {
            // set the owning side to null (unless already changed)
            if ($characterChoice->getSerie() === $this) {
                $characterChoice->setSerie(null);
            }
        }

        return $this;
    }
}
