<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CharacterCp::class, inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CharacterCp $characterCp = null;

    #[ORM\Column(length: 75)]
    #[Assert\NotNull()]
    #[Assert\Length(max: 75)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull()]
    #[Assert\Length(max: 255)]
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCharacterCp(): ?characterCp
    {
        return $this->characterCp;
    }

    public function setCharacterCp(?characterCp $characterCp): self
    {
        $this->characterCp = $characterCp;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
