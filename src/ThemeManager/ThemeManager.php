<?php

namespace ThemeManager;

class ThemeManager
{
    /**
     * @var \ThemeManager\ThemeCollection
     */
    private $themes;

    /**
     * @param ThemeCollection $themes
     */
    public function __construct(ThemeCollection $themes)
    {
        $this->themes = $themes;
    }

    /**
     * @return array
     */
    public function getAllThemeNames()
    {
        return $this->themes()->allThemeNames();
    }

    /**
     * @return array
     */
    public function getInvalidThemes()
    {
        return $this->themes()->getInvalidThemes();
    }

    /**
     * @return array
     */
    public function getInvalidThemesCount()
    {
        return $this->themes()->invalidCount();
    }

    /**
     * @param $name
     *
     * @return bool|Theme
     */
    public function getTheme($name)
    {
        return $this->themes()->getTheme($name);
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function themeExists($name)
    {
        return $this->themes()->themeExists($name);
    }

    /**
     * @return \ThemeManager\ThemeCollection
     */
    public function themes()
    {
        return $this->themes;
    }

    /**
     * @return \ThemeManager\ThemeCollection
     */
    public function all()
    {
        return $this->themes();
    }

    /**
     * @return \ThemeManager\Theme
     */
    public function first()
    {
        return $this->all()->first();
    }

    /**
     * @return \ThemeManager\Theme
     */
    public function last()
    {
        return $this->all()->last();
    }

    /**
     * @return int
     */
    public function countAll()
    {
        return $this->all()->count();
    }

    /**
     * @param         $path
     *
     * @return $this
     */
    public function addThemeLocation($path)
    {
        if (! empty($path) && ! $this->themes()->pathExists($path)) {
            $addLocation = (new Starter(true))->start($path, $this->themes()->getRequiredFields(), $this->themes()->getExceptionOnInvalid());

            $all = array_merge($this->getInvalidThemes(), $addLocation->all(), $addLocation->getInvalidThemes());

            $this->themes = $this->themes()->merge($all, $path);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function returnThis()
    {
        return $this;
    }
}
