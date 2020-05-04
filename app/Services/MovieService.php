<?php

namespace App\Services;

use GuzzleHttp\Client;

/**
 * Class ReportsService
 * @package App\Services\ReportsService
 */
class MovieService
{
    const REPORT_API = 'http://api.securedserver.xyz/api/movie/';
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
     * @return mixed
     */
    public function getAllMovies()
    {
        $res = $this->client->get(self::REPORT_API);
        $response = json_decode($res->getBody());
        if ($response){
            return $response;
        } else {
            json_decode(false);
        }
    }

}
