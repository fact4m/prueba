<?php

namespace Modules\Services\Data;

use GuzzleHttp\Client;

class ServiceData
{
    public static function service($type, $number)
    {
        $client = new Client(['base_uri' => config('configuration.api_service_url')]);
        $parameters = [
            'http_errors' => false,
            'connect_timeout' => 5,
            'headers' => [
                'Authorization' => 'Bearer '.config('configuration.api_service_token'),
                'Accept' => 'application/json',
            ],
        ];

        $res = $client->request('GET', '/api/'.$type.'/'.$number, $parameters);
        $response = json_decode($res->getBody()->getContents(), true);

        if($response['success']) {
            $response['data']['name'] = $response['data']['nombre_o_razon_social'];
            $response['data']['address'] = $response['data']['direccion_completa'];
        }

        return $response;
    }
}