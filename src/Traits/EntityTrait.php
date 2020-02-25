<?php

namespace Xvrmallafre\ImportProductsSwapi\Traits;

/**
 * Trait EntityTrait
 * @package Xvrmallafre\ImportProductsSwapi\Traits
 */
trait EntityTrait
{
    /**
     * @param string $entityType
     * @param string $url
     * @return int
     */
    protected function getEntityIdFromUrl(string $entityType, string $url)
    {
        $baseUrl = 'https://swapi.co/api/';
        $entityUrl = '';

        switch ($entityType) {
            case 'starship':
                $entityUrl = $baseUrl . 'starships/';
                break;
            case 'pilot':
                $entityUrl = $baseUrl . 'people/';
                break;
        }

        $swapiId = str_replace($entityUrl, '', $url);
        $swapiId = str_replace('/', '', $swapiId);

        return (int)$swapiId;
    }
}
