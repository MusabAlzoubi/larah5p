<?php

namespace LaraH5P\Facades;

use Illuminate\Support\Facades\Facade;

class LaraH5P extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'LaraH5P';
    }
}
