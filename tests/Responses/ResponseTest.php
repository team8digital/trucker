<?php

namespace Trucker\Tests\Responses;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Prophecy\Argument;
use Prophecy\Prediction\CallTimesPrediction;
use Trucker\Facades\Config;
use Trucker\Facades\Response;
use Trucker\Tests\TruckerTestCase;

class ResponseTest extends TruckerTestCase
{
    public function testDynamicFunctionCallOnResponse()
    {
        $gResponse = $this->prophesize(\Guzzle\Http\Message\Response::class);
        $gResponse
            ->getStatusCode()
            ->willReturn(200)
            ->shouldBeCalled();
        $response = new \Trucker\Responses\Response($this->app, $gResponse->reveal());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetOption()
    {
        $config = $this->prophesize(Repository::class);
        $config
            ->get(Argument::exact('trucker::transporter.driver'))
            ->willReturn('json');

        $app = $this->prophesize(Container::class);
        $app
            ->offsetGet(Argument::exact('config'))
            ->willReturn($config);

        $transporter = Config::get('transporter.driver');

        $this->assertEquals('json', $transporter);
    }

    public function testNewInstanceCreator()
    {
        $gResponse = $this->prophesize(\Guzzle\Http\Message\Response::class);
        $gResponse
            ->getStatusCode()
            ->willReturn(200)
            ->shouldBeCalled();

        $i = Response::newInstance($this->app, $gResponse->reveal());
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
        $config = $this->prophesize(Repository::class);
        $config
            ->get(Argument::exact('trucker::transporter.driver'))
            ->willReturn('json');

        $app = $this->prophesize(Container::class);
        $app
            ->offsetGet(Argument::exact('config'))
            ->willReturn($config);

        $data = ['foo' => 'bar'];

        $gResponse = $this->prophesize(\Guzzle\Http\Message\Response::class);
        $gResponse
            ->json()
            ->willReturn($data)
            ->should(new CallTimesPrediction(1));

        $response = new \Trucker\Responses\Response($app->reveal(), $gResponse->reveal());

        $this->assertTrue(
            $this->arraysAreSimilar($data, $response->parseResponseToData())
        );
    }

    public function testParseJsonResponseStringToObject()
    {
        $config = $this->prophesize(Repository::class);
        $config
            ->get(Argument::exact('trucker::transporter.driver'))
            ->willReturn('json');

        $app = $this->prophesize(Container::class);
        $app
            ->offsetGet(Argument::exact('config'))
            ->willReturn($config);

        $data = ['foo' => 'bar'];
        $dataJson = json_encode($data);
        $decodedJson = json_decode($dataJson);

        $gResponse = $this->prophesize(\Guzzle\Http\Message\Response::class);
        $gResponse->getBody(Argument::exact(true))
            ->shouldBeCalled(new CallTimesPrediction(1))
            ->willReturn($dataJson);

        $response = new \Trucker\Responses\Response($app->reveal(), $gResponse->reveal());

        $this->assertEquals(
            $decodedJson,
            $response->parseResponseStringToObject()
        );
    }
}
