<?php

namespace lucasgiovanny\SerproDataValid\Models;

use lucasgiovanny\SerproDataValid\SerproDataValid;

class Person extends BaseModel
{
    protected $endpoint = "validate/pf-face";

    protected $api;

    public function __construct(SerproDataValid $api)
    {
        $this->api = $api;
    }

    public function rawValidation(string $cpf, array $answers)
    {
        $data = $this->mountDataArray($cpf, $answers);
        return $this->send($data);
    }

    public function isNameValid(string $cpf, string $name, bool $hard = false)
    {
        $data = $this->mountDataArray($cpf, ['nome' => $name]);
        $res = $this->send($data);

        if ($hard) {
            return [
                'name' => $res->nome,
                'similarity' => $res->nome_similaridade
            ];
        }

        if ($res->nome) {
            return true;
        } else {
            return $res->nome_similaridade >= 0.85 ? true : false;
        }
    }

    protected function send(array $data)
    {
        return $this->api->send($this->endpoint, $data);
    }

    protected function mountDataArray(string $cpf, array $answers)
    {
        return [
            'key' => [
                'cpf' => $cpf
            ],
            'answer' => $answers
        ];
    }
}
