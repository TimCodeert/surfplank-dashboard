<?php

namespace App\Controller;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlayerController extends AbstractController
{
    #[Route('/players', name: 'app_players')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $players = $entityManager->getRepository(Player::class)->findAll();

        return $this->render('players/index.html.twig', [
            'players' => $players,
        ]);
    }
}