<?php

namespace ThemeManager\Exceptions;

use Exception;
use OutOfBoundsException;


class MissingThemesFolder extends OutOfBoundsException
{

    public function __construct($createdPath = null, $code = 0, Exception $previous = null)
    {
        $message = 'Expecting themes folder at ' . ($createdPath ?: themes_base_path());
        parent::__construct($message, $code, $previous);
    }

}
