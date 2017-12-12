<?php

namespace Trucker\Tests\Transporters;

use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Prophecy\Argument;
use Trucker\Requests\RestRequest;
use Trucker\Tests\TruckerTestCase;
use Trucker\Transporters\JsonTransporter;

class JsonTransporterTest extends TruckerTestCase
{
    public function testSetsHeaderOnRequest()
    {
        $request = $this->prophesize(Request::class);
        $request
            ->setHeaders(
                Argument::allOf(
                    Argument::type('array'),
                    Argument::withEntry('Accept', 'application/json'),
                    Argument::withEntry('Content-Type', 'application/json')
                )
            )
            ->shouldBeCalled();

        $transporter = new JsonTransporter();
        $transporter->setHeaderOnRequest($request->reveal());
    }

    public function testParsesResponseToData()
    {
        $response = $this->prophesize(Response::class);
        $response->json()->shouldBeCalled();

        $transporter = new JsonTransporter();
        $transporter->parseResponseToData($response->reveal());
    }

    public function testParsesResponseStringToObject()
    {
        $response = $this->prophesize(Response::class);
        $response
            ->getBody(
                Argument::exact(true)
            )
            ->willReturn('{"foo":"bar", "biz":"bang"}')
            ->shouldBeCalled();

        $transporter = new JsonTransporter();
        $result = $transporter->parseResponseStringToObject($response->reveal());

        $this->assertEquals('bar', $result->foo);
        $this->assertEquals('bang', $result->biz);
        $this->assertTrue(is_object($result), 'Expected result to be an object');
    }

    public function testSetRequestBody()
    {
        $body = ['testme!'];
        $request = $this->prophesize(RestRequest::class);
        $request
            ->setBody(
                Argument::exact(json_encode($body)),
                Argument::exact('application/json')
            )
            ->shouldBeCalled();

        $transporter = new JsonTransporter();
        $transporter->setRequestBody($request->reveal(), $body);
    }
}
