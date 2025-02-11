<?php

namespace App\Controller\API;

use App\BLL\ReviewBLL;
use App\Controller\API\BaseApiController;
use App\Entity\Review;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ReviewApiController extends BaseApiController //Ya tendremos disponibles getContent y getResponse
{
    #[Route('/reviewsapinuevo', name: 'api_post_reviews', methods: ['POST'])]
    public function postReview(Request $request, ReviewBLL $reviewBLL)
    {
        $data = $this->getContent($request);
        $review = $reviewBLL->nueva($data);
        return $this->getResponse($review, Response::HTTP_CREATED);
    }

    #[Route('/reviewsapi/filtradas', name: 'api_get_reviews_filtradas', methods: ['GET'])]
    public function getAllReviews(Request $request, ReviewBLL $reviewBLL, int $juego) {
        $fechaInicial = $request->query->get('fechaInicial');
        $fechaFinal = $request->query->get('fechaFinal');

        $reviews = $reviewBLL->getReviews($juego, $fechaInicial, $fechaFinal);
        return $this->getResponse($reviews);
    }

    #[Route('/reviewsapi/{id}', name: 'api_get_review', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getOneReview(Review $review, ReviewBLL $reviewBLL)
    {
        return $this->getResponse($reviewBLL->toArray($review));
    }

    #[Route('/reviewsapi/{id}', name: 'api_delete_review', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteReview(Review $review, ReviewBLL $reviewBLL)
    {
        $reviewBLL->delete($review);
        return $this->getResponse(null, Response::HTTP_NO_CONTENT); //Devuelve sin contenido
    }

    #[Route('/reviewsapi/{id}', name: 'api_update_review', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function updateReview(Request $request, Review $review, ReviewBLL $reviewBLL)
    {
        $data = $this->getContent($request);
        $review = $reviewBLL->actualizaReview($review, $data);
        return $this->getResponse($review, Response::HTTP_OK);
    }
}