<?php

namespace lucasgiovanny\SerproDataValid\Exceptions;

class CouldNotSendRequest extends \Exception
{
    public static function serviceRespondeWithAnError($message)
    {
        return new static('Serpro Datavalid API Response: ' . $message);
    }
}
