<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"NONE")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $date = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'albums')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Artiste $artiste = null;

    #[ORM\OneToMany(mappedBy: 'album', targetEntity: Morceau::class)]
    private Collection $morceaux;

    #[ORM\ManyToMany(targetEntity: Style::class, mappedBy: 'albums')]
    private Collection $styles;

    public function __construct()
    {
        $this->morceaux = new ArrayCollection();
        $this->styles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDate(): ?int
    {
        return $this->date;
    }

    public function setDate(int $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getArtiste(): ?Artiste
    {
        return $this->artiste;
    }

    public function setArtiste(?Artiste $artiste): self
    {
        $this->artiste = $artiste;

        return $this;
    }

    /**
     * @return Collection<int, Morceau>
     */
    public function getMorceaux(): Collection
    {
        return $this->morceaux;
    }

    public function addMorceaux(Morceau $morceaux): self
    {
        if (!$this->morceaux->contains($morceaux)) {
            $this->morceaux->add($morceaux);
            $morceaux->setAlbum($this);
        }

        return $this;
    }

    public function removeMorceaux(Morceau $morceaux): self
    {
        if ($this->morceaux->removeElement($morceaux)) {
            // set the owning side to null (unless already changed)
            if ($morceaux->getAlbum() === $this) {
                $morceaux->setAlbum(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Style>
     */
    public function getStyles(): Collection
    {
        return $this->styles;
    }

    public function addStyle(Style $style): self
    {
        if (!$this->styles->contains($style)) {
            $this->styles->add($style);
            $style->addAlbum($this);
        }

        return $this;
    }

    public function removeStyle(Style $style): self
    {
        if ($this->styles->removeElement($style)) {
            $style->removeAlbum($this);
        }

        return $this;
    }
}
