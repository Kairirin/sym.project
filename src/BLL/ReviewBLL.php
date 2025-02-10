<?php

namespace App\BLL;

use App\Entity\Juego;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class ReviewBLL
{
    private RequestStack $requestStack;
    private ReviewRepository $reviewRepository;

    public function __construct(RequestStack $requestStack, ReviewRepository $reviewRepository)
    {
        $this->requestStack = $requestStack;
        $this->reviewRepository = $reviewRepository;
    }

    public function getAllRevs() {
        return $this->reviewRepository->findReviews();
    }
    
    public function getReviews(int $juego, ?string $fechaInicial = null, ?string $fechaFinal = null)
    {
/*         return $this->reviewRepository->findReviewsPorJuego($juego); */
        return $this->reviewRepository->findReviewsPorFecha($juego, $fechaInicial, $fechaFinal);
    }

    public function getReviewsAutor(int $autor)
    {
        return $this->reviewRepository->findReviewsPorAutor($autor);
    }
}
