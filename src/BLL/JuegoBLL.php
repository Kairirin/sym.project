<?php

namespace App\BLL;

use App\Entity\Juego;
use App\Entity\Plataforma;
use App\Repository\JuegoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JuegoBLL extends BaseBLL
{
    private JuegoRepository $juegoRepository;

    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        RequestStack $requestStack,
        Security $security,
        JuegoRepository $juegoRepository
    ) {
        parent::__construct($em, $validator, $requestStack, $security);
        $this->juegoRepository = $juegoRepository;
    }

    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    public function setSecurity(Security $security)
    {
        $this->security = $security;
    }

    public function getJuegos()
    {
        $juegos = $this->em->getRepository(Juego::class)->findAll();
        return $this->entitiesToArray($juegos);
    }

    public function getJuegosConOrdenacion(?string $ordenacion)
    {
        if (!is_null($ordenacion)) { // Cuando se establece un tipo de ordenación específico
            $tipoOrdenacion = 'asc'; // Por defecto si no se había guardado antes en la variable de sesión
            $session = $this->requestStack->getSession(); // Abrir la sesión
            $juegosOrdenacion = $session->get('juegosOrdenacion');
            if (!is_null($juegosOrdenacion)) { // Comprobamos si ya se había establecido un orden
                if ($juegosOrdenacion['ordenacion'] === $ordenacion) // Por si se ha cambiado de campo a ordenar
                {
                    if ($juegosOrdenacion['tipoOrdenacion'] === 'asc')
                        $tipoOrdenacion = 'desc';
                }
            }
            $session->set('juegosOrdenacion', [ // Se guarda la ordenación actual
                'ordenacion' => $ordenacion,
                'tipoOrdenacion' => $tipoOrdenacion
            ]);
        } else { // La primera vez que se entra se establece por defecto la ordenación por id ascendente
            $ordenacion = 'id';
            $tipoOrdenacion = 'asc';
        }
        return $this->juegoRepository->findJuegos($ordenacion, $tipoOrdenacion);
    }

    public function getJuegosPlataforma(array $plat)
    {
        return $this->juegoRepository->findJuegosPorPlataforma($plat);
    }

    public function actualizaJuego(Juego $juego, array $data)
    {
        $juego->setNombre($data['nombre']);
        $juego->setImagen($data['imagen']);
        // El id de la plataforma, la tenemos que busar en su BBDD
        $plataforma = $this->em->getRepository(Plataforma::class)->find($data['plataforma']);
        $juego->setPlataforma($plataforma);

        return $this->guardaValidando($juego);
    }

    public function nueva(array $data)
    {
        $juego = new Juego();
        return $this->actualizaJuego($juego, $data);
    }

    public function toArray(Juego $juego)
    {
        if (is_null($juego))
            return null;
        return [
            'id' => $juego->getId(),
            'nombre' => $juego->getNombre(),
            'imagen' => $juego->getImagen(),
            'plataforma' => $juego->getPlataforma()->getNombre(),
        ];
    }
}
