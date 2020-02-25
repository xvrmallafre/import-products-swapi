<?php

namespace Xvrmallafre\ImportProductsSwapi\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use KMurgadella\RestApiManager\ApiManager;
use KMurgadella\RestApiManager\ApiManagerFactory;
use Xvrmallafre\ImportProductsSwapi\Models\Pilot;
use Xvrmallafre\ImportProductsSwapi\Models\Starship;
use Xvrmallafre\ImportProductsSwapi\Traits\MagentoTrait;

/**
 * Class ExportToMagento
 * @package Xvrmallafre\ImportProductsSwapi\Console\Commands
 */
class ExportToMagento extends Command
{
    use MagentoTrait;

    const MAGENTO_LOGIN_URI = 'V1/integration/admin/token';
    const MAGENTO_PRODUCTS_ENDPOINT = 'default/V1/products/';
    const MAGENTO_PILOTS_ENDPOINT = 'V1/pilots/pilot/';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magento:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send data from pim from Magento';


    /**
     * @var Collection|Starship[]
     */
    protected $starships;

    /**
     * @var mixed
     */
    protected $restUrl;

    /**
     * @var ApiManager|null
     */
    protected $apiManager;

    /**
     * @var array
     */
    private $credentials;

    /**
     * @var array
     */
    protected $authenticationHeader;

    /**
     * @var Collection|Pilot[]
     */
    protected $pilots;

    /**
     * Create a new command instance.
     *
     * @return void
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->starships = Starship::all();
        $this->pilots = Pilot::all();
        $this->restUrl = env('MAGENTO_REST_URL', 'http://magento.test/rest/');
        $this->credentials = [
            'username' => env('MAGENTO_ADMIN_USER', 'admin'),
            'password' => env('MAGENTO_ADMIN_PASSWORD', 'admin123'),
        ];
        $this->apiManager = ApiManagerFactory::create($this->restUrl);
        $this->authenticationHeader = $this->getAuthenticationHeader();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->syncStarships();
        $this->syncPilots();
    }

    protected function syncStarships()
    {
        foreach ($this->starships as $starship) {
            $sku = 'starship-' . $this->getEntityIdFromUrl('starship', $starship->url);
            $magentoStarship = $this->apiManager->get(
                self::MAGENTO_PRODUCTS_ENDPOINT . $sku,
                $this->authenticationHeader
            );

            $magentoConfig = $this->getMagentoStarshipsConfig($starship, $sku);

            if (!preg_match('/^2/', $magentoStarship['statusCode'])) {
                $this->apiManager->post(
                    self::MAGENTO_PRODUCTS_ENDPOINT,
                    $magentoConfig,
                    $this->authenticationHeader
                );
            } else {
                $this->apiManager->put(
                    self::MAGENTO_PRODUCTS_ENDPOINT . $sku,
                    $magentoConfig,
                    $this->authenticationHeader
                );
            }
        }
    }

    protected function syncPilots()
    {
        foreach ($this->pilots as $pilot) {
            $pimId = $this->getEntityIdFromUrl('pilot', $pilot->url);
            $magentoPilot = $this->apiManager->get(
                self::MAGENTO_PILOTS_ENDPOINT . $pimId,
                $this->authenticationHeader
            );

            $magentoConfig = $this->getMagentoPilotConfig($pilot, $pimId);

            if (!preg_match('/^2/', $magentoPilot['statusCode'])) {
                $this->apiManager->post(
                    self::MAGENTO_PILOTS_ENDPOINT,
                    $magentoConfig,
                    $this->authenticationHeader
                );
            } else {
                $this->apiManager->put(
                    self::MAGENTO_PILOTS_ENDPOINT . $pimId,
                    $magentoConfig,
                    $this->authenticationHeader
                );
            }
        }
    }

    /**
     * @return array
     */
    protected function getAuthenticationHeader()
    {
        $bearer = $this->apiManager->post(self::MAGENTO_LOGIN_URI, $this->credentials);

        return ['Authorization: Bearer ' . $bearer['data']];
    }
}
