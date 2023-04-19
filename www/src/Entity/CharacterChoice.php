<?php

namespace App\Entity;

use App\Entity\Serie;
use App\Repository\CharacterChoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterChoiceRepository::class)]
class CharacterChoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Serie:: class, inversedBy: 'characterChoices')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull()]
    #[Assert\Length(max: 25)]
    private ?serie $serie = null;

    #[ORM\Column(length: 25)]
    #[Assert\NotNull()]
    #[Assert\Length(max: 25)]
    private ?string $name = null;

    #[ORM\Column(length: 25)]
    #[Assert\NotNull()]
    #[Assert\Length(max: 25)]
    private ?string $weight = null;

    #[ORM\Column(length: 25)]
    #[Assert\NotNull()]
    #[Assert\Length(max: 25)]
    private ?string $speed = null;

    #[ORM\Column(length: 3)]
    #[Assert\NotNull()]
    #[Assert\Length(max: 3)]
    private ?string $tier = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotNull()]
    #[Assert\Length(max: 10)]
    private ?string $iterationNumber = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    private ?string $imagePath = null;

    #[ORM\OneToMany(mappedBy: 'characterChoice', targetEntity: CharacterCp::class)]
    private Collection $characterCps;

    public function __construct()
    {
        $this->characterCps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerie(): ?serie
    {
        return $this->serie;
    }

    public function setSerie(?serie $serie): self
    {
        $this->serie = $serie;

        return $this;
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

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getSpeed(): ?string
    {
        return $this->speed;
    }

    public function setSpeed(string $speed): self
    {
        $this->speed = $speed;

        return $this;
    }

    public function getTier(): ?string
    {
        return $this->tier;
    }

    public function setTier(string $tier): self
    {
        $this->tier = $tier;

        return $this;
    }

    public function getIterationNumber(): ?string
    {
        return $this->iterationNumber;
    }

    public function setIterationNumber(string $iterationNumber): self
    {
        $this->iterationNumber = $iterationNumber;

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
     * @return Collection<int, CharacterCp>
     */
    public function getCharacterCps(): Collection
    {
        return $this->characterCps;
    }

    public function addCharacterCp(CharacterCp $characterCp): self
    {
        if (!$this->characterCps->contains($characterCp)) {
            $this->characterCps->add($characterCp);
            $characterCp->setCharacterChoice($this);
        }

        return $this;
    }

    public function removeCharacterCp(CharacterCp $characterCp): self
    {
        if ($this->characterCps->removeElement($characterCp)) {
            // set the owning side to null (unless already changed)
            if ($characterCp->getCharacterChoice() === $this) {
                $characterCp->setCharacterChoice(null);
            }
        }

        return $this;
    }
}
