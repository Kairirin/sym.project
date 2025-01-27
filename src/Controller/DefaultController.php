<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index()
    {
        return $this->render('index.view.html.twig');
    }

    #[Route('/about', name: 'about')] //Se podrá acceder desde navegador poniendo sym.local/about
    public function abaut() {
        return $this->render('about.html.twig');
    }
}
