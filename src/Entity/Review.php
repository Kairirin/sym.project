<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Juego $juego = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(length: 255)]
    private ?string $comentario = null;

    #[ORM\Column(length: 255)]
    private ?string $ruta_captura = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $autor = null;

    const RUTA_CAPTURAS_JUEGOS = 'images/capturasUsuarios/';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJuego(): ?Juego
    {
        return $this->juego;
    }

    public function setJuego(?Juego $juego): static
    {
        $this->juego = $juego;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(string $comentario): static
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getRutaCaptura(): ?string
    {
        return $this->ruta_captura;
    }

    public function setRutaCaptura(string $ruta_captura): static
    {
        $this->ruta_captura = $ruta_captura;

        return $this;
    }

    public function getAutor(): ?Usuario
    {
        return $this->autor;
    }

    public function setAutor(?Usuario $autor): static
    {
        $this->autor = $autor;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitulo();
    }

    public function getUrlCaptura(): string
    {
        return self::RUTA_CAPTURAS_JUEGOS . $this->getRutaCaptura();
    }
}
