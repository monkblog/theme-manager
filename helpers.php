<?php

use Illuminate\Support\Facades\Config;
use ThemeManager\Starter;
use ThemeManager\ThemeManager;

if (! function_exists('themes_base_path')) {
    /**
     * @return string|bool
     */
    function themes_base_path()
    {
        $base = __DIR__ . '/../../../';
        $vendor = realpath($base . 'vendor');
        $themes = realpath($base . 'themes');

        if (is_dir($vendor)) {
            return $themes;
        }
        if (is_dir($themes)) {
            return $themes;
        }
        if (getenv('APP_ENV') === 'testing') {
            return realpath(__DIR__ . '/tests/themes');
        }

        return false;
    }
}

if (! function_exists('theme_manager_starter')) {
    /**
     * @return \ThemeManager\Starter
     */
    function theme_manager_starter()
    {
        return new Starter;
    }
}

if (! function_exists('theme_manager')) {
    /**
     * @param null    $basePath
     * @param array   $requiredFields
     * @param bool $exceptionOnInvalid
     *
     * @return \ThemeManager\ThemeManager
     */
    function theme_manager($basePath = null, array $requiredFields = [], $exceptionOnInvalid = false)
    {
        if (function_exists('app') && class_exists('Illuminate\Container\Container') &&
            Config::get('app.aliases.ThemeManager') == 'ThemeManager\Facade\ThemeManager'
        ) {
            return \ThemeManager::returnThis();
        }

        return new ThemeManager(theme_manager_starter()->start($basePath, $requiredFields, $exceptionOnInvalid));
    }
}
