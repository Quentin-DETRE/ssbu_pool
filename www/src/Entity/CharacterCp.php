<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\CharacterChoice;
use App\Repository\CharacterCpRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterCpRepository::class)]
class CharacterCp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CharacterChoice::class, fetch: 'EAGER', inversedBy: 'characterCps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?characterChoice $characterChoice = null;

    #[ORM\ManyToOne(inversedBy: 'characterCps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;

    #[ORM\OneToMany(mappedBy: 'characterCp', targetEntity: Note::class, cascade: ["all"])]
    private Collection $notes;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCharacterChoice(): ?characterChoice
    {
        return $this->characterChoice;
    }

    public function setCharacterChoice(?characterChoice $characterChoice): self
    {
        $this->characterChoice = $characterChoice;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setCharacterCp($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getCharacterCp() === $this) {
                $note->setCharacterCp(null);
            }
        }

        return $this;
    }
}
