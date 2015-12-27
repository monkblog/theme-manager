<?php

require_once __DIR__ . '/vendor/autoload.php';

$basePath = null;
$requiredFields = ['display_name', 'version', 'license',];

/**
 * Laravel

if (function_exists('app') && function_exists('base_path') && class_exists('ThemeManager')) {

    $allThemes = \ThemeMangaer::all();

    $themeExists = ThemeMangaer::themeExists('theme-name');

    if ($themeExists) {
        $theme = \ThemeMangaer::getTheme('theme-name');
    }
    else {
        $theme = \ThemeManager::first();
    }

    $themeName = $theme->getName();

    $themeMeta = $theme->getInfo();

    \ThemeMangaer::addThemeLocation(base_path('/path/to/alternative/themes-folder'));
}
*/

/**
 * Via new Class
 */
//Bootstrapping theme php files if autoload.php file is present
(new \ThemeManager\Starter)->bootstrapAutoload();
//OR via helper
theme_manager_starter()->bootstrapAutoload();

//Via new
$themeManager = new \ThemeManager\ThemeManager((new \ThemeManager\Starter)->start());

//Optionally pass in initial base path
$themeManager = new \ThemeManager\ThemeManager((new \ThemeManager\Starter)->start(__DIR__ . '/path/to/themes/'));

//Optional Required Field(s)
$themeManager = new \ThemeManager\ThemeManager((new \ThemeManager\Starter)->start($basePath, $requiredFields));

//Via Theme Manager Starter Helper
$themeManager = new \ThemeManager\ThemeManager(theme_manager_starter()->start());

// Exception On Invalid
$themeManager = new \ThemeManager\ThemeManager(theme_manager_starter()->start($basePath, $requiredFields, true));

/**
 * Via Helper
 */
$themeManager = theme_manager();

//Optionally pass in initial base path
$themeManager = theme_manager(__DIR__ . '/path/to/themes/');

//Optional Required Field(s)
$themeManager = theme_manager($basePath, $requiredFields);

// Exception On Invalid
$themeManager = theme_manager($basePath, $requiredFields, true);

//ThemeCollection
$allThemes = $themeManager->all();

//Returns bool
$myThemeExists = $themeManager->themeExists('theme-name') ? 'yes' : 'nope';

//Theme Obj or null
$myTheme = $themeManager->getTheme('theme-name');

//Array
$myThemeInfo = $myTheme->getInfo();

//Array of strings
$themeNames = $themeManager->getAllThemeNames();

//First Theme Obj
$firstTheme = $allThemes->first();

//Last Theme Obj
$lastTheme = $allThemes->last();

//Add another location of themes to the ThemeManager
$themeManager->addThemeLocation(__DIR__ . '/path/to/alternative/themes-folder');
