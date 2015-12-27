<?php

namespace ThemeManager\Exceptions;

use Exception;
use ThemeManager\Theme;

class NoThemeName extends NoThemeData
{
    /**
     * @var bool|Theme
     */
    protected $theme = false;

    /**
     * @param string         $themePath
     * @param bool        $subMessage
     * @param Theme|null     $theme
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct($themePath, Theme $theme = null, $subMessage = false, $code = 0, Exception $previous = null)
    {
        parent::__construct($themePath, $theme, ($subMessage ?: "doesn't define 'name'"), $code, $previous);
    }
}
