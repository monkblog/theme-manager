<?php

namespace ThemeManager;

use PHPUnit_Framework_TestCase;

class ThemeCollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ThemeCollection
     */
    protected $collection;

    /**
     * @var Theme
     */
    protected $theme;

    /**
     * @var array
     */
    protected $themeArray;

    public function setUp()
    {
        $this->theme = new Theme(themes_base_path() . '/demo');
        $this->themeArray = [$this->theme];

        $this->collection = new ThemeCollection($this->themeArray);
    }

    /**
     * @test
     * @group collection
     */
    public function testConstruct()
    {
        $this->assertTrue($this->collection->first() instanceof Theme);
        $this->assertFalse($this->collection->themeExists('demo'));
    }

    /**
     * @test
     * @group collection
     */
    public function testGetTheme()
    {
        $this->assertEquals($this->theme, $this->collection->getTheme('demo-theme-yml'));
        $this->assertFalse($this->collection->getTheme('demo'));
    }

    /**
     * @test
     * @group collection
     */
    public function testThemeExists()
    {
        $this->assertTrue($this->collection->themeExists('demo-theme-yml'));
        $this->assertFalse($this->collection->themeExists('demo'));
    }

    /**
     * @test
     * @group collection
     */
    public function testAllThemNames()
    {
        $allThemeNames = $this->collection->allThemeNames();
        $this->assertTrue(is_array($allThemeNames) && ! empty($allThemeNames));

        $this->assertEquals('demo-theme-yml', array_shift($allThemeNames));
    }
}
