<?php

namespace Trucker\Tests\Support;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Mockery as m;
use Trucker\Support\ConfigManager;
use Trucker\Tests\TruckerTestCase;

class ConfigManagerTest extends TruckerTestCase
{
    public function testGetApp()
    {
        $cm = new ConfigManager($this->app);
        $this->assertEquals($this->app, $cm->getApp());
    }

    public function testSetApp()
    {
        $mApp = m::mock(Container::class);
        $cm = new ConfigManager($this->app);
        $cm->setApp($mApp);
        $this->assertEquals($mApp, $cm->getApp());
    }

    public function testGet()
    {
        $cm = new ConfigManager($this->app);
        $this->assertEquals('rest', $cm->get('request.driver'));
    }

    public function testSet()
    {
        $app = new Container();
        $config = m::mock(Repository::class);

        $config->shouldReceive('set')
            ->once()
            ->with('trucker::request.driver', 'foo');

        $config->shouldReceive('get')
            ->once()
            ->with('trucker::request.driver')
            ->andReturn('foo');

        $app['config'] = $config;

        $cm = new ConfigManager($app);
        $cm->set('request.driver', 'foo');
        $this->assertEquals('foo', $cm->get('request.driver'));
    }

    public function testContains()
    {
        $this->swapConfig([
                'trucker::response.http_status.success' => [200, 201],
            ]);
        $cm = new ConfigManager($this->app);
        $this->assertTrue($cm->contains('response.http_status.success', 200));
    }
}
