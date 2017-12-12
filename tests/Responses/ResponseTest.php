<?php

namespace Trucker\Tests\Responses;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Mockery as m;
use Trucker\Facades\Config;
use Trucker\Facades\Response;
use Trucker\Tests\TruckerTestCase;

class ResponseTest extends TruckerTestCase
{
    public function testDynamicFunctionCallOnResponse()
    {
        $gResponse = m::mock(\Guzzle\Http\Message\Response::class);
        $gResponse->shouldReceive('getStatusCode')
            ->once()
            ->andReturn(200);
        $response = new \Trucker\Responses\Response($this->app, $gResponse);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetOption()
    {
        $config = m::mock(Repository::class);
        $config->shouldIgnoreMissing();
        $config->shouldReceive('get')->with('trucker::transporter.driver')
            ->andReturn('json');

        $app = m::mock(Container::class);
        $app->shouldIgnoreMissing();
        $app->shouldReceive('offsetGet')->with('config')->andReturn($config);

        $transporter = Config::get('transporter.driver');

        $this->assertEquals('json', $transporter);
    }

    public function testNewInstanceCreator()
    {
        $gResponse = m::mock(\Guzzle\Http\Message\Response::class);
        $gResponse->shouldReceive('getStatusCode')
            ->times(2)
            ->andReturn(200);

        $i = Response::newInstance($this->app, $gResponse);
        $this->assertInstanceOf(
            \Trucker\Responses\Response::class, $i
        );
        $this->assertEquals(
            $this->app,
            $i->getApp()
        );
        $this->assertEquals(200, $i->getStatusCode());
    }

    public function testParseJsonResponseToData()
    {
        $config = m::mock(Repository::class);
        $config->shouldIgnoreMissing();
        $config->shouldReceive('get')->with('trucker::transporter.driver')
            ->andReturn('json');

        $app = m::mock(Container::class);
        $app->shouldIgnoreMissing();
        $app->shouldReceive('offsetGet')->with('config')->andReturn($config);

        $data = ['foo' => 'bar'];

        $gResponse = m::mock(\Guzzle\Http\Message\Response::class);
        $gResponse->shouldReceive('json')
            ->once()
            ->andReturn($data);
        $response = new \Trucker\Responses\Response($app, $gResponse);
        $this->assertTrue(
            $this->arraysAreSimilar($data, $response->parseResponseToData())
        );
    }

    public function testParseJsonResponseStringToObject()
    {
        $config = m::mock(Repository::class);
        $config->shouldIgnoreMissing();
        $config->shouldReceive('get')->with('trucker::transporter.driver')
            ->andReturn('json');

        $app = m::mock(Container::class);
        $app->shouldIgnoreMissing();
        $app->shouldReceive('offsetGet')->with('config')->andReturn($config);

        $data = ['foo' => 'bar'];
        $dataJson = json_encode($data);
        $decodedJson = json_decode($dataJson);

        $gResponse = m::mock(\Guzzle\Http\Message\Response::class);
        $gResponse->shouldReceive('getBody')
            ->with(true)
            ->once()
            ->andReturn($dataJson);
        $response = new \Trucker\Responses\Response($app, $gResponse);
        $this->assertEquals(
            $decodedJson,
            $response->parseResponseStringToObject()
        );
    }
}
