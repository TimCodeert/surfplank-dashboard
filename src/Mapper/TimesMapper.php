<?php
namespace App\Mapper;

use App\Entity\MapTime;
use App\Repository\MapTimeRepository;

class TimesMapper
{
    public function __construct(
        private MapTimeRepository $mapTimeRepository
    ) {}

    /**
     * 
     * @param MapTime[] $times
     */
    public function map(array $times): array
    {
        $mainTimes = [];
        $bonusTimes = [];
        
        $worldRecords = $this->mapTimeRepository->getWorldRecords();
        $indexedWorldRecords = [];

        foreach ($worldRecords as $wr) {
            $wrMap = $wr->getMap();
            
            $key = $wr->isBonus() 
                ? sprintf('%d_bonus_%d', $wrMap->getId(), $wr->getBonusNumber() ?? 1)
                : sprintf('%d_main', $wrMap->getId());
                
            $indexedWorldRecords[$key] = $wr;
        }

        foreach ($times as $time) {
            $map = $time->getMap();

            $lookupKey = $time->isBonus()
                ? sprintf('%d_bonus_%d', $map->getId(), $time->getBonusNumber() ?? 1)
                : sprintf('%d_main', $map->getId());

            $wr = $indexedWorldRecords[$lookupKey] ?? null;

            $data = [
                'own' => $time,
                'WR'  => $wr->getRunTime(),
                'map' => $time->getMap()
            ];

            if ($time->isBonus()) {
                $bonusTimes[] = $data;
            } else {
                $mainTimes[] = $data;
            }
        }

        return [
            'main' => $mainTimes,
            'bonus' => $bonusTimes,
        ];
    }
}