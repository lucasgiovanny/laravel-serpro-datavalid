<?php

namespace lucasgiovanny\SerproDataValid;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use lucasgiovanny\SerproDataValid\Exceptions\CouldNotSendRequest;

class SerproDataValid
{
    protected $apiBaseUrl = "https://apigateway.serpro.gov.br/";

    protected $apiService = "datavalid/";

    protected $apiVersion = "v2/";

    protected $consumerKey;

    protected $consumerSecret;

    protected $barear;

    protected $http;

    public function __construct(
        string $consumerKey = null,
        string $consumerSecret = null,
        bool $sandbox = false,
        HttpClient $http = null
    ) {

        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->http = $http;

        if ($sandbox) {
            $this->setSandBox();
        }
    }

    protected function httpClient(): HttpClient
    {
        return $this->http ?? new HttpClient();
    }

    protected function setSandBox()
    {
        $this->apiVersion = "v2/";
        $this->apiService = "datavalid-demonstracao/";
        $this->barear = "4e1a1858bdd584fdc077fb7d80f39283";
    }

    protected function setBearer()
    {

        if (!$this->consumerKey || !$this->consumerSecret) {
            throw CouldNotSendRequest::apiAccessNotDefined();
        }

        try {

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

    public function send(string $endpoint, array $data)
    {

        $token = $this->barear ?? $this->setBearer($this->sandbox);

        if (!$token) {
            throw CouldNotSendRequest::bearerTokenNotDefined();
        }

        try {

            $res = $this->httpClient()->post(
                $this->apiBaseUrl . $this->apiService . $this->apiVersion . $endpoint,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json'
                    ],
                    'body' => json_encode($data)
                ]
            );

            return json_decode((string) $res->getBody());
        } catch (ClientException $exception) {
            throw CouldNotSendRequest::serviceRespondeWithAnError($exception->getMessage());
        }
    }
}
