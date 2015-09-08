<?php

namespace ThemeManager;

use Illuminate\Support\Collection;

class ThemeCollection extends Collection
{

    /**
     * @var boolean
     */
    protected $exceptionOnInvalid = false;

    /**
     * @var array
     */
    protected $invalidThemes = [];

    /**
     * @var array
     */
    protected $requiredFields = [];

    /**
     * @var array
     */
    protected $themesFolders = [];

    /**
     * @var array
     */
    protected $themeNames = [];


    /**
     * Create a new theme collection.
     *
     * @param mixed   $items
     * @param boolean $themesFolder
     * @param array   $requiredFields
     * @param boolean $exceptionOnInvalid
     */
    public function __construct($items = [], $themesFolder = false, Array $requiredFields = [], $exceptionOnInvalid = false)
    {
        $this->requiredFields = $requiredFields;
        $this->exceptionOnInvalid = $exceptionOnInvalid;

        if (is_string($themesFolder)) {
            $themesFolder = realpath($themesFolder);
            $this->themesFolders[$themesFolder] = $themesFolder;
        }
        $this->separateInvalidItems($items);

        parent::__construct($items);

        /* @var $theme Theme */
        foreach ($this->items as $theme) {
            if ($theme instanceof Theme) {
                $this->themeNames[] = $theme->getName();
            }
        }
    }

    /**
     * @param mixed          $items
     * @param string|boolean $addPath
     *
     * @return static
     */
    public function merge($items, $addPath = false)
    {
        $themesPaths = $this->getThemesPaths();
        /* @var $themeCollection $this */
        $themeCollection = parent::merge($items);

        $themeCollection->requiredFields = $this->requiredFields;
        $themeCollection->exceptionOnInvalid = $this->exceptionOnInvalid;

        if ($addPath !== false) {
            $themesPaths[] = realpath($addPath);
        }
        foreach ($themesPaths as $path) {
            $themeCollection->themesFolders[$path] = $path;
        }

        return $themeCollection;
    }

    /**
     * @return array
     */
    public function getRequiredFields()
    {
        return $this->requiredFields;
    }

    /**
     * @return boolean
     */
    public function getExceptionOnInvalid()
    {
        return $this->exceptionOnInvalid;
    }

    /**
     * @return array
     */
    public function getThemesPaths()
    {
        return $this->themesFolders;
    }

    /**
     * @param string $themesFolder
     *
     * @return boolean
     */
    public function pathExists($themesFolder)
    {
        return (is_string($themesFolder) && in_array(realpath($themesFolder), $this->themesFolders));
    }

    /**
     * @param $items
     *
     * @return $this
     */
    public function separateInvalidItems(&$items)
    {
        foreach ($items as $key => $theme) {
            if ($theme instanceof Theme && $theme->hasError()) {
                $this->invalidThemes[] = $theme;
                unset($items[$key]);
            }
        }
        array_values(array_filter($items));

        return $this;
    }

    /**
     * @return int
     */
    public function invalidCount()
    {
        return count($this->invalidThemes);
    }

    /**
     * @return array
     */
    public function getInvalidThemes()
    {
        return $this->invalidThemes;
    }

    /**
     * @return int
     */
    public function validCount()
    {
        return $this->count();
    }

    /**
     * @return array
     */
    public function getValidThemes()
    {
        return $this->all();
    }

    /**
     * @param $name
     *
     * @return boolean|Theme
     */
    public function getTheme($name)
    {
        /* @var $theme Theme */
        foreach ($this->items as $theme) {
            if ($theme instanceof Theme && $theme->getName() == $name) {
                return $theme;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function allThemeNames()
    {
        return $this->themeNames;
    }

    /**
     * @param $name
     *
     * @return boolean
     */
    public function themeExists($name)
    {
        return (in_array($name, $this->themeNames) && !is_null($this->getTheme($name)));
    }
}