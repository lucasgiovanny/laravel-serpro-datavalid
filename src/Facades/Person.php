<?php

namespace LucasGiovanny\SerproDataValid;

use Illuminate\Support\Facades\Facade;

class Person extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LucasGiovanny\SerproDataValid\Models\Person';
    }
}
