<?php

namespace LucasGiovanny\SerproDataValid\Exceptions;

class InvalidRequestOrResponse extends \Exception
{
    public static function cpfDoesnotExists()
    {
        return new static("CPF doesn't exists on database");
    }

    public static function facialBioNotAvailable()
    {
        return new static('Facial verification not avaiable for this CPF.');
    }
}
