<?php

namespace ThemeManager\Exceptions;

use ThemeManager\Theme;

class EmptyThemeName extends NoThemeName
{

    public function __construct($themePath, Theme $theme = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($themePath, $theme, "'name' entry is empty", $code, $previous);
    }

}