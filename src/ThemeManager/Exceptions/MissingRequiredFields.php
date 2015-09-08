<?php

namespace ThemeManager\Exceptions;

use ThemeManager\Theme;

class MissingRequiredFields extends NoThemeName
{

    public function __construct($themePath, Theme $theme = null, $code = 0, \Exception $previous = null)
    {
        $fields = implode(', ', $theme->getMissingRequiredFields());
        parent::__construct($themePath, $theme, "is missing {$theme->countMissingRequiredFields()} required field(s): [ {$fields} ].", $code, $previous);
    }

}