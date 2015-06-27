<?php

namespace ThemeManager\Exceptions;

use Exception;
use OutOfBoundsException;
use ThemeManager\Theme;


class NoThemeName extends OutOfBoundsException {

    public function __construct( Theme $theme, $code = 0, Exception $previous = null ) {
        $message = "Theme {$theme->getYmlPath()} doesn't define 'name'";
        parent::__construct($message, $code, $previous);
    }

}