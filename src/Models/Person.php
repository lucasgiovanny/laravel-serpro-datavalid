<?php

namespace lucasgiovanny\SerproDataValid\Models;

use lucasgiovanny\SerproDataValid\SerproDataValid;

class Person extends BaseModel
{
    protected $endpoint = "validate/pf-face";

    public function validate(string $cpf, array $answers)
    {
        $data = [
            'key' => [
                'cpf' => $cpf
            ],
            'answer' => $answers
        ];

        $request = new SerproDataValid();
        return $request->sendRequest($this->endpoint, $data);
    }
}
