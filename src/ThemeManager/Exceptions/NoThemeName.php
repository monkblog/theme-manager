<?php

namespace ThemeManager\Exceptions;

use Exception;
use ThemeManager\Theme;

class NoThemeName extends NoThemeData
{
    /**
     * @var boolean|Theme
     */
    protected $theme = false;


    /**
     * @param string         $themePath
     * @param boolean        $subMessage
     * @param Theme|null     $theme
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct($themePath, Theme $theme = null, $subMessage = false, $code = 0, Exception $previous = null)
    {
        parent::__construct($themePath, $theme, ($subMessage ?: "doesn't define 'name'"), $code, $previous);
    }

}
