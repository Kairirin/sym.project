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

        if (sizeof($videojuegos) > 1 )
        {
            usort($videojuegos, function($j1, $j2) {
                return count($j2->getReviews()) - count($j1->getReviews());
            });

            array_slice($videojuegos, 0, 5);
        }
        
        $numeroPortada = rand(1, 3);

        $portada = 'images/index/imagen' . strval($numeroPortada) . '.png';

        return $this->render('index.view.html.twig', [
            'juegos' => $videojuegos,
            'portada' => $portada,
            'totalJuegos' => $totalJuegos,
            'totalUsuarios' => $totalUsuarios
        ]);
    }

    #[Route('/about', name: 'about')]
    public function about() {
        return $this->render('about.view.html.twig');
    }

    #[Route('/admin', name: 'panel_admin')] 
    public function admin() {
        return $this->render('about.view.html.twig');
    }
}
