<?php

namespace Trucker\Tests\Responses;

use Mockery as m;
use Trucker\Responses\RawResponse;
use Trucker\Responses\Response;
use Trucker\Tests\TruckerTestCase;

class RawResponseTest extends TruckerTestCase
{
    public function testConstructorHasObjects()
    {
        $mock = m::mock(Response::class);
        $errors = ['foo', 'bar'];

        $r = new RawResponse(true, $mock, $errors);

        $this->assertTrue($r->success, 'RawResponse succes expected to be true');
        $this->assertEquals($mock, $r->getResponse());
        $this->assertTrue(
            $this->arraysAreSimilar($errors, $r->errors())
        );
    }

    public function testGetWrappedResponseObject()
    {
        $mock = m::mock(Response::class);
        $mock->shouldDeferMissing();
        $mock->shouldReceive('getStatusCode')
            ->once()
            ->andReturn(200);
        $mock->shouldReceive('getReasonPhrase')
            ->once()
            ->andReturn('OK');
        $mock->shouldReceive('getProtocol')
            ->once()
            ->andReturn('HTTP');

        $r = new RawResponse(true, $mock, []);

        $this->assertEquals(200, $r->getStatusCode());
        $this->assertEquals('OK', $r->getReasonPhrase());
        $this->assertEquals('HTTP', $r->getProtocol());

        //$this->expectException('BadMethodCallException');
        //$this->assertNull($r->nonExistingMethod());
    }

    public function testResponseStrToObjectGetter()
    {
        $obj = json_decode('{"a":1,"b":2,"c":3,"d":4,"e":5}');

        $mock = m::mock(Response::class);
        $mock->shouldReceive('parseResponseStringToObject')
            ->once()
            ->andReturn($obj);

        $r = new RawResponse(true, $mock, []);
        $this->assertEquals(
            $r->response(),
            $obj
        );
    }
}
