<?php

namespace Doofinder\Management;

use Doofinder\Configuration;
use Doofinder\Management\Resources\Indexes;
use Doofinder\Management\Resources\Items;
use Doofinder\Management\Resources\SearchEngines;
use Doofinder\Shared\Exceptions\ValidatorException;
use Doofinder\Shared\HttpClient;
use Doofinder\Shared\Services\Validator\Validations\IntegerValidation;
use Doofinder\Shared\Services\Validator\Validations\StringValidation;
use Doofinder\Shared\Services\Validator\Validator;

class ManagementClient
{
    private $searchEnginesResource;
    private $itemsResource;
    private $indexesResource;
    private $validator;
    //TODO cambiar esto de sitio
    const CURRENCY = ['AED', 'ARS', 'AUD', 'BAM', 'BDT', 'BGN', 'BOB', 'BRL', 'BYN', 'CAD', 'CHF', 'CLP', 'CNY', 'COP', 'CZK', 'DKK', 'DOP', 'EGP', 'EUR', 'GBP', 'HKD', 'HRK', 'HUF', 'IDR', 'ILS', 'INR', 'IRR', 'ISK', 'JPY', 'KRW', 'KWD', 'MXN', 'MYR', 'NOK', 'NZD', 'PEN', 'PLN', 'RON', 'RSD', 'RUB', 'SAR', 'SEK', 'TRY', 'TWD', 'UAH', 'USD', 'VEF', 'VND', 'XPF', 'ZAR'];


    private function __construct($host, $token)
    {
        $config = Configuration::create($host, $token, 'Management');
        $httpClient = new HttpClient();
        $this->searchEnginesResource = SearchEngines::create($httpClient, $config);
        $this->itemsResource = Items::create($httpClient, $config);
        $this->indexesResource = Indexes::create($httpClient, $config);
        $this->validator = Validator::create();
    }

    public static function create($host, $token)
    {
        return new self($host, $token);
    }

    public function getProcessStatus($hashId)
    {

        // GET /api/v2/search_engines/{hashid}/_process
        return [];
    }

    public function processTask()
    {
        // POST /api/v2/search_engines/{hashid}/_process
    }

    public function getSearchEngine()
    {
        // GET /api/v2/search_engines/{hashid}
    }

    public function deleteSearchEngine()
    {
        // DELETE /api/v2/search_engines/{hashid}
    }

    public function updateSearchEngine()
    {
        // PATCH /api/v2/search_engines/{hashid}
    }

    public function listSearchEngines()
    {
        // GET /api/v2/search_engines
    }

    public function createSearchEngine(array $params)
    {
        try {
            $validations = $this->searchEnginesResource->getValidations('GetProcessStatus.php');

            $this->validator->validateParams(
                $params,
                $validations
            );

            $this->searchEnginesResource->createSearchEngine($params);
        } catch (\Exception $e) {
            echo $e->getMessage();
            throw $e;
        }
    }



}