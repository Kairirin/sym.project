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
/*     #[Route(name: 'app_juego_index', methods: ['GET'])]
    public function index(Request $requestStack, JuegoBLL $juegoBLL, JuegoRepository $juegoRepository): Response
    {
        $h1Pagina = "Explora sin límites";
        $sobreTitulo = "¿Lo buscas todo? Aquí lo tienes";

        return $this->render('juego/index.html.twig', [
            'juegos' => $juegoRepository->findAll(),
            'sobretitulo' => $sobreTitulo,
            'h1Pagina' => $h1Pagina
        ]);
    } */

    #[Route('/', name: 'videojuegos_index', methods: ['GET'])]
    #[Route('/orden/{ordenacion}', name: 'app_imagen_index_ordenado', methods: ['GET'])]
    public function index(JuegoBLL $juegoBLL, string $ordenacion = null): Response {
        $juegos = $juegoBLL->getJuegosConOrdenacion($ordenacion);
        $h1Pagina = "Explora sin límites";
        $sobreTitulo = "¿Lo buscas todo? Aquí lo tienes";

        return $this->render('juego/index.html.twig', [
            'juegos' => $juegos,
            'sobretitulo' => $sobreTitulo,
            'h1Pagina' => $h1Pagina
        ]);
    }

    #[Route('/new', name: 'app_juego_new', methods: ['GET', 'POST'])]
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
            $juego->setNombre($fileName);

            $entityManager->persist($juego);
            $entityManager->flush();

            return $this->redirectToRoute('app_juego_index', [], Response::HTTP_SEE_OTHER);
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
            'juego' => $juego,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_juego_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Juego $juego, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JuegoType::class, $juego);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_juego_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('juego/edit.html.twig', [
            'juego' => $juego,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_juego_delete', methods: ['POST'])]
    public function delete(Request $request, Juego $juego, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $juego->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($juego);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_juego_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_imagen_delete_json', methods: ['DELETE'])]
    public function deleteJson(Juego $juego, JuegoRepository $imagenRepository): Response
    {
        $imagenRepository->remove($juego, true);
        return new JsonResponse(['eliminado' => true]);
    }
}
