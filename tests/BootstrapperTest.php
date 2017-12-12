<?php

namespace Trucker\Tests;

use Trucker\Bootstrapper;

class BootstrapperTest extends TruckerTestCase
{
    /**
     * The bootstrapper instance.
     *
     * @var Bootstrapper
     */
    protected $bootstrapper;

    /**
     * Setup the tests.
     */
    public function setUp()
    {
        parent::setUp();

        $this->bootstrapper = new Bootstrapper($this->app);
        unset($this->app['path.base']);
    }

    /**
     * Tears down the tests.
     */
    public function tearDown()
    {
        parent::tearDown();

        $dir = __DIR__ . '/../.trucker';
        if (file_exists($dir)) {
            $this->rrmdir($dir);
        }
    }

    private function rrmdir($dir)
    {
        foreach (glob($dir . '/*') as $file) {
            if (is_dir($file)) {
                $this->rrmdir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dir);
    }

    public function testDoesntRebindBasePath()
    {
        $base = 'src';
        $this->app->instance('path.base', $base);
        $this->bootstrapper->bindPaths();

        $this->assertEquals($base, $this->app['path.base']);
    }

    public function testCanBindBasePath()
    {
        $this->bootstrapper->bindPaths();

        $this->assertEquals(dirname(__DIR__) . '', $this->app['path.base']);
    }

    public function testCanBindConfigurationPaths()
    {
        $this->bootstrapper->bindPaths();

        $root = dirname(__DIR__) . '';
        $this->assertEquals($root . '/.trucker', $this->app['path.trucker.config']);
    }

    public function testCanExportConfiguration()
    {
        $this->bootstrapper->bindPaths();
        $this->bootstrapper->exportConfiguration();

        $this->assertFileExists(__DIR__ . '/../.trucker');
    }

    public function testCanReplaceStubsInConfigurationFile()
    {
        $this->bootstrapper->bindPaths();
        $path = $this->bootstrapper->exportConfiguration();
        $this->bootstrapper->updateConfiguration($path, ['basic_username' => 'foo']);

        $this->assertFileExists(__DIR__ . '/../.trucker');
        $this->assertContains('foo', file_get_contents(__DIR__ . '/../.trucker/auth.php'));
    }
}
