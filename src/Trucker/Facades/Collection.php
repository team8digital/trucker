<?php

namespace Trucker\Facades;

use Illuminate\Support\Facades\Facade;
use Trucker\TruckerServiceProvider;

/**
 * Facade class for interacting with the Trucker Collection class.
 *
 * @author Alessandro Manno <alessandromanno96@gmail.com>
 */
class Collection extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        if (!static::$app) {
            static::$app = TruckerServiceProvider::make();
        }

        return 'trucker.collection-finder';
    }
}
