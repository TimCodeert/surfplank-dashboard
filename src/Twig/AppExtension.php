<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('format_ticks', [$this, 'formatTicks']),
        ];
    }

    public function formatTicks(int $ticks): string
    {
        $tickRate = 64;
        
        $totalSeconds = (int) floor($ticks / $tickRate);
        $remainingTicks = $ticks % $tickRate;

        $minutes = (int) floor($totalSeconds / 60);
        $seconds = $totalSeconds % 60;

        $milliseconds = (int) floor(($remainingTicks / $tickRate) * 100);

        return sprintf('%02d:%02d.%02d', $minutes, $seconds, $milliseconds);
    }
}