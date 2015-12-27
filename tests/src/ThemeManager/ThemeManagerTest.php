<?php

namespace ThemeManager;

use PHPUnit_Framework_TestCase;

class ThemeManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \ThemeManager\ThemeManager
     */
    protected $themeManager;

    public function setUp()
    {
        $this->setUpAlternative();
    }

    protected function setUpAlternative($path = null, array $requiredFields = [], $exceptionOnInvalid = false)
    {
        $this->themeManager = theme_manager($path, $requiredFields, $exceptionOnInvalid);
    }

    /**
     * @test
     * @group manager
     */
    public function testGetAllThemeNamesIsArray()
    {
        $this->assertTrue(is_array($this->themeManager->getAllThemeNames()));
    }

    /**
     * @test
     * @group manager
     */
    public function testGetAllThemeNamesCountThree()
    {
        $this->assertEquals(3, $this->themeManager->countAll());
    }

    /**
     * @test
     * @group manager
     */
    public function testGetInvalidThemes()
    {
        $this->assertEquals(1, $this->themeManager->getInvalidThemesCount());
    }

    /**
     * @test
     * @group manager
     */
    public function testGetAllThemes()
    {
        $this->assertInstanceOf('ThemeManager\\ThemeCollection', $this->themeManager->all());
    }

    /**
     * @test
     * @group manager
     */
    public function testThemeExists()
    {
        $this->assertTrue($this->themeManager->themeExists('demo-theme-yml'));
    }

    /**
     * @test
     * @group manager
     */
    public function testGetTheme()
    {
        $this->assertInstanceOf('ThemeManager\\Theme', $this->themeManager->getTheme('demo-theme-yml'));
    }

    /**
     * @test
     * @group manager
     */
    public function testGetLocationTypePrimary()
    {
        $this->assertEquals('Primary', $this->themeManager->first()->getLocationType());
        $this->assertEquals('Primary', $this->themeManager->last()->getLocationType());
    }

    /**
     * @test
     * @group manager
     */
    public function testReturnThis()
    {
        $this->assertInstanceOf('ThemeManager\\ThemeManager', $this->themeManager->returnThis());
    }

    /**
     * @test
     * @group manager
     */
    public function testAddThemeLocation()
    {
        //Path that has one theme
        $path = themes_base_path() . '/../themes-alternative';
        //returns $this
        $this->assertInstanceOf('ThemeManager\\ThemeManager', $this->themeManager->addThemeLocation($path));
        //Make sure it exists
        $this->assertTrue($this->themeManager->themeExists('example-theme'));
        $this->assertInstanceOf('ThemeManager\\Theme', $this->themeManager->getTheme('example-theme'));
        //example-theme is a Secondary Theme
        $this->assertEquals('Secondary', $this->themeManager->getTheme('example-theme')->getLocationType());
        //There should now be four themes
        $this->assertEquals(4, $this->themeManager->countAll());
    }

    /**
     * @test
     * @group manager
     */
    public function testAddThemeLocationWithBadData()
    {
        $this->assertEquals(1, $this->themeManager->getInvalidThemesCount());

        $addPath = themes_base_path() . '/../themes-test';

        $this->themeManager->addThemeLocation($addPath);

        $this->assertNotEmpty($this->themeManager->getInvalidThemes());

        $this->assertEquals(3, $this->themeManager->getInvalidThemesCount());
    }

    /**
     * @test
     * @group manager
     */
    public function testAddThemeLocationPreventDoubles()
    {
        $addPath = themes_base_path() . '/../themes-test';

        $this->assertFalse($this->themeManager->themes()->pathExists($addPath));

        $this->themeManager->addThemeLocation($addPath);

        $this->assertTrue($this->themeManager->themes()->pathExists($addPath));

        $this->themeManager->addThemeLocation($addPath);

        $this->assertEquals(3, $this->themeManager->getInvalidThemesCount());

        $this->assertEquals(3, $this->themeManager->countAll());
    }

    /**
     * @test
     * @group manager
     *
     * @expectedException \ThemeManager\Exceptions\EmptyThemeName
     */
    public function testAddThemeLocationEmptyThemeNameError()
    {
        $this->setUpAlternative(themes_base_path() . '/../themes-alternative', [], true);

        $addPath = themes_base_path() . '/../themes-test';

        $this->themeManager->addThemeLocation($addPath);
    }

    /**
     * @test
     * @group manager
     *
     * @expectedException \ThemeManager\Exceptions\MissingRequiredFields
     */
    public function testAddThemeLocationMissingRequiredFieldsError()
    {
        $this->setUpAlternative(themes_base_path() . '/../themes-alternative', ['name', 'version'], true);

        $addPath = themes_base_path() . '/../themes-test';

        $this->themeManager->addThemeLocation($addPath);
    }

    /**
     * @test
     * @group manager
     *
     * @expectedException \ThemeManager\Exceptions\NoThemeName
     */
    public function testAddThemeLocationNoThemeNameError()
    {
        $this->setUpAlternative(themes_base_path() . '/../themes-alternative', ['name', 'version'], true);

        $addPath = themes_base_path();

        $this->themeManager->addThemeLocation($addPath);
    }
}
