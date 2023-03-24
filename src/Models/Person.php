<?php

namespace LucasGiovanny\SerproDataValid\Models;

use Carbon\Carbon;
use LucasGiovanny\SerproDataValid\Exceptions\InvalidRequestOrResponse;
use LucasGiovanny\SerproDataValid\SerproDataValid;
use stdClass;

class Person extends BaseModel
{
    protected $endpoint = "validate/pf";

    protected $api;

    public function __construct(SerproDataValid $api)
    {
        $this->api = $api;
    }

    public function rawValidation(string $cpf, array $answers)
    {
        $this->endpoint = "validate/pf-face";
        $data = $this->mountDataArray($cpf, $answers);
        return $this->send($data);
    }

    public function validateName(string $cpf, string $name, bool $getSimilarity = false)
    {
        $data = $this->mountDataArray($cpf, ['nome' => $name]);
        $res = $this->send($data);

        if ($getSimilarity) {
            $return = new stdClass;
            $return->nome = $res->nome;
            $return->nome_similaridade = $res->nome_similaridade;
            return $return;
        }

        if ($res->nome) {
            return true;
        } else {
            return $res->nome_similaridade >= 0.85 ? true : false;
        }
    }

    public function validateGender(string $cpf, string $gender)
    {
        $data = $this->mountDataArray($cpf, ['sexo' => $gender]);
        $res = $this->send($data);

        return $res->sexo;
    }

    public function isBrazilian(string $cpf)
    {
        $data = $this->mountDataArray($cpf, ['nacionalidade' => 1]);
        $res = $this->send($data);

        return $res->nacionalidade;
    }

    public function validateParentsName(string $cpf, array $parents, bool $getSimilarity = false)
    {
        $data = $this->mountDataArray($cpf, [
            'filiacao' => [
                'nome_mae' => isset($parents['mother_name']) ? $parents['mother_name'] : null,
                'nome_pai' => isset($parents['father_name']) ? $parents['father_name'] : null,
            ]
        ]);
        $res = $this->send($data);

        if ($getSimilarity) {

            $return = array();

            if (isset($parents['mother_name'])) {
                $mother = new stdClass;
                $mother->mother_name = $res->filiacao->nome_mae;
                $mother->mother_name_similarity = $res->filiacao->nome_mae_similaridade;
                array_push($return, $mother);
            }

            if (isset($parents['father_name'])) {
                $father = new stdClass;
                $father->father_name = $res->filiacao->nome_pai;
                $father->father_name_similarity = $res->filiacao->nome_pai_similaridade;
                array_push($return, $father);
            }

            return $return;
        }

        $return = new stdClass;
        isset($parents['mother_name']) ? $return->mother_name = $res->filiacao->nome_mae : null;
        isset($parents['father_name']) ? $return->father_name = $res->filiacao->nome_pai : null;

        return $return;
    }

    public function isCPFRegular(string $cpf)
    {
        $data = $this->mountDataArray($cpf, ['situacao_cpf' => 'regular']);
        $res = $this->send($data);

        return $res->situacao_cpf;
    }

    public function validatePhoto(string $cpf, string $photo)
    {
        $this->endpoint = "validate/pf-face";
        $data = $this->mountDataArray($cpf, ['biometria_face' => $photo]);
        $res = $this->send($data);

        if ($res->biometria_face->disponivel) {
            $return = new stdClass;
            $return->valid = $res->biometria_face->similaridade >= 0.85 ? true : false;
            $return->similarity = $res->biometria_face->similaridade;
            $return->probability = $res->biometria_face->probabilidade;
        } else {
            throw InvalidRequestOrResponse::facialBioNotAvailable();
        }

        return $return;
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
