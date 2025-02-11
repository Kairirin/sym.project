<?php

namespace App\BLL;

use App\Entity\Juego;
use App\Entity\Review;
use App\Entity\User;
use App\Repository\ReviewRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReviewBLL extends BaseBLL
{
    private ReviewRepository $reviewRepository;

    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        RequestStack $requestStack,
        Security $security,
        ReviewRepository $reviewRepository
    ) {
        parent::__construct($em, $validator, $requestStack, $security);
        $this->reviewRepository = $reviewRepository;
    }

    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    public function setSecurity(Security $security)
    {
        $this->security = $security;
    }

    public function getAllRevs()
    {
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

    public function actualizaReview(Review $review, array $data)
    {
        $review->setTitulo($data['titulo']);
        $review->setComentario($data['comentario']);
        $review->setRutaCaptura($data['ruta_captura']);
        // El id de la categoria, la tenemos que busar en su BBDD
        $juego = $this->em->getRepository(Juego::class)->find($data['juego']);
        $review->setJuego($juego);
        $fecha = DateTime::createFromFormat('d/m/Y', $data['fecha']);
        $review->setFecha($fecha);
        $autor = $this->em->getRepository(User::class)->find($data['autor']);
        $review->setAutor($autor);
        return $this->guardaValidando($review);
    }

    public function nueva(array $data)
    {
        $review = new Review();
        return $this->actualizaReview($review, $data);
    }

    public function toArray(Review $review)
    {
        if (is_null($review))
            return null;
        return [
            'id' => $review->getId(),
            'titulo' => $review->getTitulo(),
            'comentario' => $review->getComentario(),
            'juego' => $review->getJuego()->getNombre(),
            'ruta_captura' => $review->getRutaCaptura(),
            'fecha' => is_null($review->getFecha()) ? '' : $review->getFecha()->format('d/m/Y'),
            'autor' => $review->getAutor()->getId()
        ];
    }
}
