<?php

namespace Trucker\Tests\Responses;

use Trucker\Responses\RawResponse;
use Trucker\Responses\Response;
use Trucker\Tests\TruckerTestCase;

class RawResponseTest extends TruckerTestCase
{
    public function testConstructorHasObjects()
    {
        $mock = $this->prophesize(Response::class);
        $errors = ['foo', 'bar'];

        $r = new RawResponse(true, $mock->reveal(), $errors);

        $this->assertTrue($r->success, 'RawResponse succes expected to be true');
        $this->assertEquals($mock->reveal(), $r->getResponse());
        $this->assertTrue(
            $this->arraysAreSimilar($errors, $r->errors())
        );
    }

    public function testResponseStrToObjectGetter()
    {
        $obj = json_decode('{"a":1,"b":2,"c":3,"d":4,"e":5}');

        $mock = $this->prophesize(Response::class);
        $mock->parseResponseStringToObject()
            ->shouldBeCalled()
            ->willReturn($obj);

        $r = new RawResponse(true, $mock->reveal(), []);
        $this->assertEquals(
            $r->response(),
            $obj
        );
    }
}
