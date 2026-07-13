<?php

namespace App\Twig;

use App\Repository\PlayerRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MyProfileExtension extends AbstractExtension
{
    public function __construct(
        private RequestStack $requestStack,
        private PlayerRepository $playerRepository
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('my_profile', [$this, 'getProfileByIp']),
        ];
    }

    public function getProfileByIp(): ?\App\Entity\Player
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return null;
        }

        $visitorIp = $request->getClientIp();

        if (!$visitorIp) {
            return null;
        }

        return $this->playerRepository->findOneBy(['ipAddress' => $visitorIp]);
    }
}