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
    
    public function getReviews(int $juego)
    {
        return $this->reviewRepository->findReviewsPorJuego($juego);
    }
}
