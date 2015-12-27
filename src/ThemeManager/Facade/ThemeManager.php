<?php

namespace ThemeManager\Facade;

use Illuminate\Support\Facades\Facade;

class ThemeManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'theme.manager';
    }
}
