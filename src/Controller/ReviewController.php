<?php

namespace App\Controller;

use App\BLL\ReviewBLL;
use App\Entity\Juego;
use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReviewController extends AbstractController
{
    #[Route(name: 'reviews_index', methods: ['GET'])]
    public function index(Juego $juego, ?string $fechaInicial, ?string $fechaFinal, ReviewBLL $reviewBll): Response
    {
        $reviews = $reviewBll->getReviews($juego->getId(), $fechaInicial, $fechaFinal);

        return $this->render('review/index.html.twig', [
            'reviews' => $reviews
        ]);
    }

/*     #[Route(name: 'reviews_index_busqueda', methods: ['GET'])]
    public function busqueda(Juego $juego, ?string $fechaInicial, ?string $fechaFinal, ReviewBLL $reviewBll): Response
    {
        $reviews = $reviewBll->getReviews($juego->getId(), $fechaInicial, $fechaFinal);

        return $this->render('review/index.html.twig', [
            'reviews' => $reviews,
        ]);
    } */

    #[Route('videojuegos/{id}/new', name: 'review_new', methods: ['GET', 'POST'])]
    public function new(Juego $juegoId, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        $review = new Review();
        $juego = $entityManager->getRepository(Juego::class)->find($juegoId);

        $review->setJuego($juego);
        $review->setFecha(new \DateTime());
        $review->setAutor($user);

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form['ruta_captura']->getData();

            if ($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('images_directory_capturas'), $fileName);
                $review->setRutaCaptura($fileName);
            }

            $review->setTitulo($form['titulo']->getData());
            $review->setComentario($form['comentario']->getData());

            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash('mensaje', 'Se ha guardado el comentario en ' . $review->getJuego());
            return $this->redirectToRoute('videojuegos_show', ['id' => $juego->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('review/new.html.twig', [
            'juego' => $juego,
            'review' => $review,
            'form' => $form,
        ]);
    }

    #[Route(name: 'review_user_show', methods: ['GET'])]
    public function show(User $user, ReviewBLL $reviewBll): Response
    {
        $reviews = $reviewBll->getReviewsAutor($user->getId());

        return $this->render('review/show.html.twig', [
            'reviews' => $reviews,
        ]);
    }

    #[Route('/videojuegos/{idJ}/edit/{idR}', name: 'review_edit', methods: ['GET', 'POST'])]
    public function edit(int $idJ, Request $request, int $idR, EntityManagerInterface $entityManager): Response
    {
        $juego = $entityManager->getRepository(Juego::class)->find($idJ);
        $review = $entityManager->getRepository(Review::class)->find($idR);

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['ruta_captura']->getData();

            if ($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('images_directory_capturas'), $fileName);
                $review->setRutaCaptura($fileName);
            }

            $review->setTitulo($form['titulo']->getData());
            $review->setComentario($form['comentario']->getData());

            $entityManager->flush();

            return $this->redirectToRoute('videojuegos_show', ['id' => $juego->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('review/edit.html.twig', [
            'juego' => $juego,
            'review' => $review,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'review_delete', methods: ['POST'])]
    public function delete(Request $request, Review $review, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $review->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($review);
            $entityManager->flush();
        }

        return $this->redirectToRoute('videojuegos_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'review_delete_json', methods: ['DELETE'])]
    public function deleteJson(Review $review, ReviewRepository $reviewRepository): Response
    {
        $reviewRepository->remove($review, true);
        return new JsonResponse(['eliminado' => true]);
    }
}
