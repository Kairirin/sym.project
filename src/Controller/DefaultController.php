<?php

namespace App\Controller;

use App\Entity\Juego;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ManagerRegistry $doctrine)
    {
        $totalJuegos = count($doctrine->getRepository(Juego::class)->findAll());
        $totalUsuarios = count($doctrine->getRepository(User::class)->findAll());
        $videojuegos = $doctrine->getRepository(Juego::class)->findAll();
        
        $numeroPortada = rand(1, 3);

        $portada = 'images/index/imagen' . strval($numeroPortada) . '.png';

        return $this->render('index.view.html.twig', [
            'juegos' => $videojuegos,
            'portada' => $portada,
            'totalJuegos' => $totalJuegos,
            'totalUsuarios' => $totalUsuarios
        ]);
    }

    #[Route('/about', name: 'about')] //Se podrá acceder desde navegador poniendo sym.local/about
    public function abaut() {
        return $this->render('about.view.html.twig');
    }
}
