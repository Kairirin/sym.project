<?php

namespace App\Entity;

use App\Repository\PlataformaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlataformaRepository::class)]
class Plataforma
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    /**
     * @var Collection<int, Juego>
     */
    #[ORM\OneToMany(targetEntity: Juego::class, mappedBy: 'plataforma')]
    private Collection $juegos;

    public function __construct()
    {
        $this->juegos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function __toString()
    {
        return $this->getNombre();
    }

    /**
     * @return Collection<int, Juego>
     */
    public function getJuegos(): Collection
    {
        return $this->juegos;
    }

    public function addJuego(Juego $juego): static
    {
        if (!$this->juegos->contains($juego)) {
            $this->juegos->add($juego);
            $juego->setPlataforma($this);
        }

        return $this;
    }

    public function removeJuego(Juego $juego): static
    {
        if ($this->juegos->removeElement($juego)) {
            // set the owning side to null (unless already changed)
            if ($juego->getPlataforma() === $this) {
                $juego->setPlataforma(null);
            }
        }

        return $this;
    }
}
