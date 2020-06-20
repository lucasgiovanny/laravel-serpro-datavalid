<?php

namespace lucasgiovanny\SerproDataValid;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use lucasgiovanny\SerproDataValid\Exceptions\CouldNotSendRequest;

class SerproDataValid
{
    protected $apiBaseUrl = "https://apigateway.serpro.gov.br/";

    protected $sandbox = false;

    protected $consumerKey;

    protected $consumerSecret;

    protected $barear;

    protected $http;

    public function __construct(HttpClient $http = null)
    {
        $this->consumerKey = config('serpro-datavalid.consumerKey');
        $this->consumerSecret = config('serpro-datavalid.consumerSecret');
        $this->sandbox = config('serpro-datavalid.sandbox');
        $this->http = $http;
    }

    protected function httpClient(): HttpClient
    {
        return $this->http ?? new HttpClient();
    }

    protected function getBearer($isSandBox = false)
    {
        try {

            if ($isSandBox) {
                return "4e1a1858bdd584fdc077fb7d80f39283";
            }

            $res = $this->httpClient()->post($this->apiBaseUrl . "token", [
                'query' => ['grant_type' => 'client_credentials'],
                'auth' => [$this->consumerKey, $this->consumerSecret]
            ]);

            $json = json_decode((string) $res->getBody());

            return $json->access_token;
        } catch (ClientException $exception) {
            throw CouldNotSendRequest::serviceRespondeWithAnError($exception->getMessage());
        }
    }

    public function sendRequest(string $endpoint, array $data)
    {
        try {

            $token = $this->getBearer($this->sandbox);

            $res = $this->httpClient()->post($this->apiBaseUrl . "datavalid/v2/" . $endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($data)
            ]);

            return json_decode((string) $res->getBody());
        } catch (ClientException $exception) {
            throw CouldNotSendRequest::serviceRespondeWithAnError($exception->getMessage());
        }
    }
}
