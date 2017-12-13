<?php

namespace Trucker\Tests\Support;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Prophecy\Argument;
use Prophecy\Prediction\CallTimesPrediction;
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
        $mApp = $this->prophesize(Container::class);
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
        $config = $this->prophesize(Repository::class);

        $config
            ->set(Argument::exact('trucker::request.driver'), Argument::exact('foo'))
            ->should(new CallTimesPrediction(1));

        $config
            ->get(Argument::exact('trucker::request.driver'))
            ->willReturn('foo')
            ->should(new CallTimesPrediction(1));

        $app['config'] = $config->reveal();

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
