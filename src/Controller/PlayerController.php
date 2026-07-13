<?php

namespace App\Controller;

use App\Repository\PlayerRepository;
use App\Mapper\PlayerPaginationMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlayerController extends AbstractController
{
    #[Route('/players', name: 'app_players')]
    public function index(
        PlayerRepository $playerRepository, 
        PlayerPaginationMapper $paginationMapper,
        Request $request
    ): Response {
        $page      = $request->query->getInt('page', 1);
        $search    = $request->query->get('search', null);
        $sort      = $request->query->get('sort', 'lastSeen');
        $direction = $request->query->get('direction', 'desc');
        $limit     = 40;

        $paginator = $playerRepository->findPaginatedPlayers($page, $limit, $sort, $direction, $search);
        $viewData = $paginationMapper->map($paginator, $page, $limit, $sort, $direction, $search);

        return $this->render('players/index.html.twig', $viewData);
    }
}