<?php

namespace lucasgiovanny\SerproDataValid;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use lucasgiovanny\SerproDataValid\Exceptions\CouldNotSendRequest;
use lucasgiovanny\SerproDataValid\Exceptions\InvalidRequestOrResponse;

class SerproDataValid
{
    protected $apiBaseUrl = "https://apigateway.serpro.gov.br/";

    protected $apiService = "datavalid/";

    protected $apiVersion = "v2/";

    protected $consumerKey;

    protected $consumerSecret;

    protected $barear;

    protected $http;

    public function __construct(HttpClient $http = null)
    {
        $this->consumerKey = config('serpro-datavalid.consumerKey');
        $this->consumerSecret = config('serpro-datavalid.consumerSecret');
        $this->http = $http;

        if (config('serpro-datavalid.sandbox')) {
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

        $token = $this->barear ?? $this->setBearer();

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

            $json = json_decode((string) $res->getBody());

            if ($json->cpf_disponivel) {
                return $json;
            } else {
                throw InvalidRequestOrResponse::cpfDoesnotExists();
            }
        } catch (ClientException $exception) {
            throw CouldNotSendRequest::serviceRespondeWithAnError($exception->getMessage());
        }
    }
}
