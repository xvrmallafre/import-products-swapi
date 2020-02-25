<?php

namespace Xvrmallafre\ImportProductsSwapi\Console\Commands;

use Illuminate\Console\Command;
use SWAPI\SWAPI;
use Xvrmallafre\ImportProductsSwapi\Models\Starship;
use Xvrmallafre\ImportProductsSwapi\Models\Pilot;
use Xvrmallafre\ImportProductsSwapi\Traits\EntityTrait;

/**
 * Class GetStarships
 * @package Xvrmallafre\ImportProductsSwapi\Console\Commands
 */
class ImportFromSwapi extends Command
{
    use EntityTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swapi:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data of starships from SWAPI';

    /**
     * @var SWAPI
     */
    protected $swapi;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->swapi = new SWAPI();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        do {
            if (!isset($starships)) {
                $starships = $this->swapi->starships()->index();
            } else {
                $starships = $starships->getNext();
            }

            foreach ($starships as $starship) {
                $newStarship = $this->saveEntityFromArray('starship', $starship);

                if (!empty($starship->pilots)) {
                    foreach ($starship->pilots as $pilotUrl) {
                        $pilot = $this->swapi->characters()->get($this->getEntityIdFromUrl('pilot', $pilotUrl->url));
                        $newPilot = $this->saveEntityFromArray('pilot', $pilot);
                        $newStarship->pilots()->attach($newPilot->id);
                    }
                }
            }
        } while ($starships->hasNext());
    }

    /**
     * @param string $entityType
     * @param object $entityData
     */
    protected function saveEntityFromArray(string $entityType, object $entityData)
    {
        if (empty($entityType) || empty($entityData)) {
            return;
        }

        $filter = ['url' => $entityData->url];

        switch ($entityType) {
            case 'starship':
                $entity = Starship::updateOrCreate(
                    $filter,
                    [
                        'name' => $entityData->name,
                        'model' => $entityData->model,
                        'manufacturer' => $entityData->manufacturer,
                        'starship_class' => $entityData->starship_class,
                        'cost_in_credits' => $entityData->cost_in_credits,
                        'url' => $entityData->url,
                    ]
                );
                break;
            case 'pilot':
                $entity = Pilot::updateOrCreate(
                    $filter,
                    [
                        'name' => $entityData->name,
                        'gender' => $entityData->gender,
                        'url' => $entityData->url,
                    ]
                );
                break;
        }

        return $entity;
    }
}
