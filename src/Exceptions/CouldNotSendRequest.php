<?php

namespace lucasgiovanny\SerproDataValid\Exceptions;

class CouldNotSendRequest extends \Exception
{
    public static function serviceRespondeWithAnError($message)
    {
        return new static('Serpro Datavalid API Response: ' . $message);
    }

    public static function bearerTokenNotDefined()
    {
        return new static('Bearer token not defined.');
    }

    public static function apiAccessNotDefined()
    {
        return new static('Consumer Key or Consumer Secret not defined');
    }
}
