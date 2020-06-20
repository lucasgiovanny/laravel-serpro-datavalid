<?php

namespace lucasgiovanny\SerproDataValid;

use Illuminate\Support\Facades\Facade;

class Person extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lucasgiovanny\SerproDataValid\Models\Person';
    }
}
