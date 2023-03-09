<?php

namespace App\Entity;

use App\Repository\MorceauRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MorceauRepository::class)]
class Morceau
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"NONE")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $duree = null;

    #[ORM\ManyToOne(inversedBy: 'morceaux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Album $album = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setID(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): self
    {
        $this->album = $album;

        return $this;
    }
}
