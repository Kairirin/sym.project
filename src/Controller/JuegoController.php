<?php

namespace App\Controller;

use App\BLL\JuegoBLL;
use App\Entity\Juego;
use App\Form\JuegoType;
use App\Repository\JuegoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/videojuegos')]
final class JuegoController extends AbstractController
{
    #[Route('/', name: 'videojuegos_index', methods: ['GET'])]
    public function index(JuegoBLL $juegoBLL, string $ordenacion = null): Response 
    {
        $juegos = $juegoBLL->getJuegosConOrdenacion($ordenacion);
        $h1Pagina = "Explora sin límites";
        $sobreTitulo = "¿Lo buscas todo? Aquí lo tienes";

        return $this->render('juego/index.html.twig', [
            'juegos' => $juegos,
            'sobretitulo' => $sobreTitulo,
            'h1Pagina' => $h1Pagina
        ]);
    }

    #[Route('/filter/{plataforma}', name: 'videojuegos_filter', methods: ['GET'])]
    public function filterByPlatform(JuegoBLL $juegoBLL, string $plataforma): Response 
    {
        $platBuscar = null;

        switch($plataforma){
            case 'playstation':
                $platBuscar = ['1', '2', '3', '4', '5'];
                break;
            case 'retro':
                $platBuscar = ['6'];
                break;
            case 'nintendo':
                $platBuscar = ['7'];
                break;
            case 'xbox':
                $platBuscar = ['8'];
                break;
        }

        $juegos = $juegoBLL->getJuegosPlataforma($platBuscar);
        $h1Pagina = "Oído cocina";
        $sobreTitulo = "¿Eres fan de " . $plataforma . "? Aquí lo tienes";

        return $this->render('juego/index.html.twig', [
            'juegos' => $juegos,
            'sobretitulo' => $sobreTitulo,
            'h1Pagina' => $h1Pagina
        ]);
    }

    #[Route('/new', name: 'videojuego_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $juego = new Juego();
        $form = $this->createForm(JuegoType::class, $juego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file almacena el archivo subido
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form['imagen']->getData();
            // Generamos un nombre único
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            // Move the file to the directory where brochures are stored
            $file->move($this->getParameter('images_directory_portadas'), $fileName);
            // Actualizamos el nombre del archivo en el objeto imagen al nuevo generado
            $juego->setNombre($form['nombre']->getData());
            $juego->setImagen($fileName);

            $entityManager->persist($juego);
            $entityManager->flush();

            return $this->redirectToRoute('videojuegos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('juego/new.html.twig', [
            'juego' => $juego,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'videojuegos_show', methods: ['GET'])]
    public function show(Juego $juego): Response
    {
        return $this->render('juego/show.html.twig', [
            'juego' => $juego
        ]);
    }

    #[Route('/edit/{id}', name: 'videojuego_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Juego $juego, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JuegoType::class, $juego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('videojuegos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('juego/edit.html.twig', [
            'juego' => $juego,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'videojuego_delete', methods: ['POST'])]
    public function delete(Request $request, Juego $juego, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $juego->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($juego);
            $entityManager->flush();
        }

        return $this->redirectToRoute('videojuegos_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'videojuego_delete_json', methods: ['DELETE'])]
    public function deleteJson(Juego $juego, JuegoRepository $juegoRepository): Response
    {
        $juegoRepository->remove($juego, true);
        return new JsonResponse(['eliminado' => true]);
    }
}
