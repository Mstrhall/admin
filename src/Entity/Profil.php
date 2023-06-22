<?php

namespace App\Entity;

use App\Repository\ProfilRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfilRepository::class)]
class Profil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(length: 50)]
    private ?string $reseausocial = null;

    #[ORM\OneToOne(mappedBy: 'profil', cascade: ['persist', 'remove'])]
    private ?Personne $personne = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getReseausocial(): ?string
    {
        return $this->reseausocial;
    }

    public function setReseausocial(string $reseausocial): static
    {
        $this->reseausocial = $reseausocial;

        return $this;
    }

    public function getPersonne(): ?Personne
    {
        return $this->personne;
    }

    public function setPersonne(?Personne $personne): static
    {
        // unset the owning side of the relation if necessary
        if ($personne === null && $this->personne !== null) {
            $this->personne->setProfil(null);
        }

        // set the owning side of the relation if necessary
        if ($personne !== null && $personne->getProfil() !== $this) {
            $personne->setProfil($this);
        }

        $this->personne = $personne;

        return $this;
    }
}
