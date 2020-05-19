<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class ReportsService
 * @package App\Services\ReportsService
 */
class ReportsService
{
    const REPORT_API = 'http://api.securedserver.xyz/api/report/';
    /**
     * @var Client
     */
    private $client;

    /**
     * ReportsService constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function getApiData($params)
    {
        try {
            $res = $this->client->get(self::REPORT_API.$params);
            $response = json_decode($res->getBody());
            if ($response){
                return $response;
            } else {
                json_decode(false);
            }
        } catch (ClientException $exception){
            return null;
        }

    }

}
