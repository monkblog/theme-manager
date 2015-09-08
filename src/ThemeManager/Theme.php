<?php

namespace ThemeManager;

use Symfony\Component\Yaml\Yaml;
use ThemeManager\Exceptions\EmptyThemeName;
use ThemeManager\Exceptions\MissingRequiredFields;
use ThemeManager\Exceptions\NoThemeData;
use ThemeManager\Exceptions\NoThemeName;

class Theme
{

    /**
     * @var null|string
     */
    private $autoload = null;

    /**
     * @var string
     */
    protected $autoloadPath = 'vendor';

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var boolean
     */
    private $error = false;

    /**
     * @var string
     */
    private $errorType = 'Unknown';

    /**
     * @var string
     */
    protected $fileName = 'theme';

    /**
     * @var array
     */
    private $info;

    /**
     * @var array
     */
    private $missingRequiredFields = [];

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    protected $requiredFields = [];

    /**
     * @var string
     */
    private $locationType;

    /**
     * @var string
     */
    private $ymlFileExtension;

    /**
     * @var string
     */
    private $yml;


    /**
     * @param         $path
     * @param array   $requiredFields
     * @param boolean $yaml
     * @param boolean $isSecondary
     */
    public function __construct($path, Array $requiredFields = [], $yaml = false, $isSecondary = false)
    {
        $this->basePath = $path;
        $this->locationType = ($isSecondary) ? 'Secondary' : 'Primary';
        $this->requiredFields = $requiredFields;
        $this->ymlFileExtension = $yaml ? '.yaml' : '.yml';

        $this->setThemeYmlPath()
            ->setAutoloadPath()
            ->setInfo()
            ->setName()
            ->checkRequiredFields();
    }

    /**
     * @return $this
     */
    protected function setThemeYmlPath()
    {
        $this->yml = $this->basePath($this->fileName . $this->ymlExtension());

        return $this;
    }

    /**
     * @return $this
     */
    protected function setAutoloadPath()
    {
        if(file_exists($this->basePath(rtrim($this->autoloadPath, '/') . '/autoload.php'))) {
            $this->autoload = $this->basePath(rtrim($this->autoloadPath, '/') . '/autoload.php');
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function setInfo()
    {
        $this->info = Yaml::parse($this->yml);

        return $this;
    }

    /**
     * @throws \ThemeManager\Exceptions\NoThemeData When theme.yml is empty
     * @throws \ThemeManager\Exceptions\NoThemeName When themes name isn't defined
     * @throws \ThemeManager\Exceptions\EmptyThemeName When themes name is empty
     *
     * @return $this
     */
    protected function setName()
    {
        $info = $this->getInfo();

        if(!is_array($info)) {
            $this->setError('No Theme Data');
            throw new NoThemeData($this->getYmlPath(), $this);
        }
        else if(!array_key_exists('name', $info)) {
            $this->setError();
            throw new NoThemeName($this->getYmlPath(), $this);
        }
        else if(empty($info['name'])) {
            $this->setError('Empty Theme Name');
            throw new EmptyThemeName($this->getYmlPath(), $this);
        }
        $this->name = $info['name'];

        return $this;
    }

    /**
     * @throws \ThemeManager\Exceptions\MissingRequiredFields When required field is empty
     *
     * @return $this
     */
    protected function checkRequiredFields()
    {
        if(!empty($this->requiredFields)) {
            foreach($this->requiredFields as $field) {
                if(is_string($field) && ($this->getInfoByKey($field) === false ||
                        !isset($this->info[$field]))
                ) {
                    $this->missingRequiredFields[] = $field;
                }
            }

            if(!empty($this->missingRequiredFields)) {
                $this->setError('Missing Required Field(s)');
                throw new MissingRequiredFields($this->getYmlPath(), $this);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getMissingRequiredFields()
    {
        return $this->missingRequiredFields;
    }

    /**
     * @return int
     */
    public function countMissingRequiredFields()
    {
        return count($this->missingRequiredFields);
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    protected function setError($type = 'No Name')
    {
        $this->error = true;
        $this->errorType = $type;

        return $this;
    }

    /**
     * @return null|string
     */
    protected function getAutoloadPath()
    {
        return $this->autoload;
    }

    /**
     * @return boolean
     */
    public function hasError()
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getErrorType()
    {
        return $this->errorType;
    }

    /**
     * @return string
     */
    public function getYmlPath()
    {
        return realpath($this->yml) ?: '{path undefined}';
    }

    /**
     * @return string
     */
    public function getLocationType()
    {
        return $this->locationType;
    }

    /**
     * @return $this
     */
    public function registerAutoload()
    {
        if(!is_null($this->getAutoloadPath())) {
            require_once "{$this->getAutoloadPath()}";
        }

        return $this;
    }

    /**
     * @return string
     */
    public function ymlExtension()
    {
        return $this->ymlFileExtension;
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param $key
     *
     * @return boolean|mixed
     */
    public function getInfoByKey($key)
    {
        if(array_has($this->getInfo(), $key)) {
            return array_get($this->getInfo(), $key);
        }

        return false;
    }

    /**
     * @param $key
     *
     * @return boolean|mixed
     */
    public function __get($key)
    {
        return $this->getInfoByKey($key);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name ?: '{empty}';
    }

    /**
     * @param null $path
     *
     * @return string
     */
    public function basePath($path = null)
    {
        return $this->basePath . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

}