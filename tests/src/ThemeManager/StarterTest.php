<?php

namespace ThemeManager;

use PHPUnit_Framework_TestCase;


class StarterTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        putenv("APP_ENV=testing");
    }

    /**
     * @test
     * @group starter
     */
    public function testBootstrapAutoload()
    {
        (new Starter)->bootstrapAutoload();

        $this->assertTrue(class_exists('ThemeManager\TestAutoload\TestAutoloadServiceProvider'));
    }

    /**
     * @test
     * @group starter
     */
    public function testStart()
    {
        $themeCollection = (new Starter)->start();

        $this->assertInstanceOf('ThemeManager\\ThemeCollection', $themeCollection);
    }

    /**
     * @test
     * @group starter
     */
    public function testStartWithInvalidThemes()
    {
        $path = themes_base_path() . '/../themes-test';

        $themeCollection = (new Starter)->start($path);

        $this->assertInstanceOf('ThemeManager\\ThemeCollection', $themeCollection);

        $this->assertNotEmpty($themeCollection->getInvalidThemes());

        $this->assertEmpty($themeCollection->getValidThemes());

        $this->assertTrue($themeCollection->invalidCount() == 2);

        $this->assertTrue($themeCollection->validCount() == 0);
    }

    /**
     * @test
     * @group starter
     *
     * @expectedException \ThemeManager\Exceptions\NoThemeName
     */
    public function testStartExceptionHandler()
    {
        (new Starter)->start(null, [], true);
    }

    /**
     * @test
     * @group starter
     *
     * @expectedException \ThemeManager\Exceptions\EmptyThemeName
     */
    public function testStartExceptionHandlerEmptyName()
    {
        $path = themes_base_path() . '/../themes-test';

        (new Starter)->start($path, [], true);
    }

    /**
     * @test
     * @group starter
     *
     * @expectedException \ThemeManager\Exceptions\MissingThemesFolder
     */
    public function testStartFail()
    {
        (new Starter)->start('fake/src/testing');
    }

    /**
     * @test
     * @group starter
     *
     * @expectedException \ThemeManager\Exceptions\MissingThemesFolder
     */
    public function testStartFailNonTestingEnv()
    {
        putenv("APP_ENV=local");

        (new Starter)->start();
    }

}