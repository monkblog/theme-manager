<?php

if( !function_exists( 'themes_base_path' ) ) {
    /**
     * @return string|boolean
     */
    function themes_base_path()
    {
        if( is_dir( realpath( __DIR__ . '/../../../vendor' ) ) ) {
            return realpath( __DIR__ . '/../../../themes' );
        }
        if( is_dir( realpath( __DIR__ . '/../../../themes' ) ) ) {
            return realpath( __DIR__ . '/../../../themes' );
        }
        if( getenv( 'APP_ENV' ) === 'testing' ) {
            return realpath( __DIR__ . '/tests/themes' );
        }

        return false;
    }
}

if( ! function_exists( 'theme_manager_starter' ) ) {
    /**
     * @return \ThemeManager\Starter
     */
    function theme_manager_starter() {
        return new \ThemeManager\Starter;
    }
}

if( !function_exists( 'theme_manager' ) ) {
    /**
     * @param null    $basePath
     * @param array   $requiredFields
     * @param boolean $exceptionOnInvalid
     *
     * @return \ThemeManager\ThemeManager
     */
    function theme_manager( $basePath = null, Array $requiredFields = [], $exceptionOnInvalid = false )
    {
        if( function_exists( 'app' ) && class_exists( 'Illuminate\Container\Container' ) ) {
            return ThemeManager::returnThis();
        }

        return new \ThemeManager\ThemeManager( theme_manager_starter()->start( $basePath, $requiredFields, $exceptionOnInvalid ) );
    }
}