<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class SteamProfileService
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private CacheInterface $cache
    ) {}

    public function getAvatarUrl(string $steamId): ?string
    {
        $cacheKey = 'steam_avatar_' . $steamId;

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($steamId) {
            $item->expiresAfter(86400);

            try {
                $response = $this->httpClient->request(
                    'GET',
                    sprintf('https://steamcommunity.com/profiles/%s?xml=1', $steamId)
                );

                if ($response->getStatusCode() !== 200) {
                    return null;
                }

                $xmlContent = $response->getContent();
                $xml = simplexml_load_string($xmlContent);

                if ($xml && isset($xml->avatarFull)) {
                    return (string) $xml->avatarFull;
                }
            } catch (\Throwable $e) {
                return null;
            }

            return null;
        });
    }
}