<?php

namespace App\Controller\API;

use App\BLL\JuegoBLL;
use App\Controller\API\BaseApiController;
use App\Entity\Juego;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class JuegoApiController extends BaseApiController
{
    #[Route('/prueba', name: 'api_prueba', methods: ["GET"])]
    public function pruebaApi(): JsonResponse
    {
        return $this->json([
            'message' => 'Bienvenido al nuevo controlador!',
        ]);
    }

    #[Route('/juegosapinuevo', name: 'api_post_juegos', methods: ['POST'])]
    public function postJuego(Request $request, JuegoBLL $juegoBLL)
    {
        $data = $this->getContent($request);
        $juego = $juegoBLL->nueva($data);
        return $this->getResponse($juego, Response::HTTP_CREATED);
    }

    #[Route('/juegosapi', name: 'api_get_juegos', methods: ['GET'])]
    public function getAllJuegos(Request $request, JuegoBLL $juegoBLL)
    {
        $juegos = $juegoBLL->getJuegos();
        return $this->getResponse($juegos);
    }

    #[Route('/juegosapi/{id}', name: 'api_get_juego', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getOneJuego(Juego $juego, JuegoBLL $juegoBLL)
    {
        return $this->getResponse($juegoBLL->toArray($juego));
    }

    #[Route('/juegosapi/{id}', name: 'api_delete_juego', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteJuego(Juego $juego, JuegoBLL $juegoBLL)
    {
        $juegoBLL->delete($juego);
        return $this->getResponse(null, Response::HTTP_NO_CONTENT); //Devuelve sin contenido
    }

    #[Route('/juegosapi/{id}', name: 'api_update_juego', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function updateJuego(Request $request, Juego $juego, JuegoBLL $juegoBLL)
    {
        $data = $this->getContent($request);
        $juego = $juegoBLL->actualizaJuego($juego, $data);
        return $this->getResponse($juego, Response::HTTP_OK);
    }
}
