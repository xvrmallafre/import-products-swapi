<?php

namespace Xvrmallafre\ImportProductsSwapi\Console\Commands;

use Illuminate\Console\Command;
use SWAPI\SWAPI;
use Xvrmallafre\ImportProductsSwapi\Models\Starship;

/**
 * Class GetStarships
 * @package Xvrmallafre\ImportProductsSwapi\Console\Commands
 */
class ImportFromSwapi extends Command
{
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
        $pilots = [];

        do {
            if (!isset($starships)) {
                $starships = $this->swapi->starships()->index();
            } else {
                $starships = $starships->getNext();
            }

            foreach ($starships as $starship) {
                Starship::updateOrCreate(
                    [
                        'url' => $starship->url
                    ],
                    [
                        'name' => $starship->name,
                        'model' => $starship->model,
                        'manufacturer' => $starship->manufacturer,
                        'starship_class' => $starship->starship_class,
                        'cost_in_credits' => $starship->cost_in_credits,
                    ]
                );
                
            }
        } while ($starships->hasNext());
    }
}
