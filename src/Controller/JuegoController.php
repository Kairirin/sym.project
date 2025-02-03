<?php

namespace App\Controller;

use App\Entity\Juego;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;

final class JuegoController extends AbstractController
{
    #[Route('/juego', name: 'app_juego')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $videojuegos = $doctrine->getRepository(Juego::class)->findAll();

        return $this->render('juego/index.html.twig', [
            'videojuegos' => $videojuegos
        ]);
    }
}
