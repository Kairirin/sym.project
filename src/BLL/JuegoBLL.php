<?php

namespace App\BLL;

use App\Repository\JuegoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JuegoBLL
{
    private RequestStack $requestStack;
    private JuegoRepository $juegoRepository;

    public function __construct(RequestStack $requestStack,JuegoRepository $juegoRepository)
    {
        $this->requestStack = $requestStack;
        $this->juegoRepository = $juegoRepository;
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
        return $this->juegoRepository->findJuegosPlataformas($ordenacion, $tipoOrdenacion);
    }
}
