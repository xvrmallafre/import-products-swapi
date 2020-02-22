<?php

namespace Xvrmallafre\ImportProductsSwapi\Console\Commands;

use Illuminate\Console\Command;
use SWAPI\SWAPI;

/**
 * Class GetStarships
 * @package Xvrmallafre\ImportProductsSwapi\Console\Commands
 */
class GetStarships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swapi:getstarships';

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
                echo "{$starship->name}\n";
            }
        } while ($starships->hasNext());
    }
}
