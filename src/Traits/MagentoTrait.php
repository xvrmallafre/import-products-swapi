<?php

namespace Xvrmallafre\ImportProductsSwapi\Traits;

use Xvrmallafre\ImportProductsSwapi\Models\Pilot;
use Xvrmallafre\ImportProductsSwapi\Models\Starship;

/**
 * Trait MagentoTrait
 * @package Xvrmallafre\ImportProductsSwapi\Traits
 */
trait MagentoTrait
{
    use EntityTrait;

    /**
     * @param Starship $starship
     * @param string $sku
     * @return array
     */
    public function getMagentoStarshipsConfig(Starship $starship, string $sku)
    {
        return [
            'product' => [
                'sku' => $sku,
                'name' => $starship->name,
                'attribute_set_id' => 4,
                'price' => (intval($starship->cost_in_credits)) ? $starship->cost_in_credits : '0',
                'status' => 1,
                'visibility' => 4,
                'type_id' => 'simple',
                'extension_attributes' => [
                    'stock_item' => [
                        'qty' => (intval($starship->cost_in_credits)) ? '10' : '0',
                        'is_in_stock' => true
                    ],
                    'category_links' => [
                        [
                            'position' => 0,
                            'category_id' => '2'
                        ]
                    ],
                ],
                'custom_attributes' => [
                    [
                        'attribute_code' => 'pilots',
                        'value' => $this->getPilotsValueAttr($starship->pilots)
                    ]
                ]
            ]
        ];
    }

    /**
     * @param object $pilots
     * @return string
     */
    public function getPilotsValueAttr(object $pilots)
    {
        $pilotString = '';
        foreach ($pilots as $pilot) {
            if (!empty($pilot->get())) {
                $pilotString .= $this->getEntityIdFromUrl('pilot', $pilot->url) . ',';
            }
        }

        return rtrim($pilotString, ',');
    }

    public function getMagentoPilotConfig(Pilot $pilot, string $pimId)
    {
        return [
            'pilot' => [
                'name' => $pilot->name,
                'pim_id' => $pimId,
                'gender' => $pilot->gender,
            ]
        ];
    }
}
