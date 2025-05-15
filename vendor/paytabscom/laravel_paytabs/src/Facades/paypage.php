<?php

namespace Paytabscom\Laravel_paytabs\Facades;

use Illuminate\Support\Facades\Facade;

class Paypage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'paypage';
    }
}
