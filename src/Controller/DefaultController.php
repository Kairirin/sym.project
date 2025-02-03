<?php

namespace App\Controller;

use App\Entity\Juego;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ManagerRegistry $doctrine)
    {
        //TODO: Hacer lo de las imágenes aleatorias de índice
        $totalJuegos = 10; //TODO: Modificar
        $totalUsuarios = 5; //TODO: Modificar
        $videojuegos = $doctrine->getRepository(Juego::class)->findAll();

        return $this->render('index.view.html.twig', [
            'juegos' => $videojuegos,
            'portada' => 'imagen1.png',
            'totalJuegos' => $totalJuegos,
            'totalUsuarios' => $totalUsuarios
        ]);
    }

    #[Route('/about', name: 'about')] //Se podrá acceder desde navegador poniendo sym.local/about
    public function abaut() {
        return $this->render('about.html.twig');
    }
}
