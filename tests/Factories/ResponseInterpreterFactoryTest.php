<?php

namespace Trucker\Tests\Factories;

use Trucker\Facades\Config;
use Trucker\Facades\ResponseInterpreterFactory;
use Trucker\Tests\TruckerTestCase;

class ResponseInterpreterFactoryTest extends TruckerTestCase
{
    public function tearDown()
    {
        parent::tearDown();
        $this->swapConfig([]);
        Config::setApp($this->app);
    }

    public function testCreateValidInterpreter()
    {
        $this->swapConfig([
            'trucker::response.driver' => 'http_status_code',
        ]);
        Config::setApp($this->app);

        $json = ResponseInterpreterFactory::build();
        $this->assertInstanceOf(
            \Trucker\Responses\Interpreters\HttpStatusCodeInterpreter::class, $json, "Expected transporter to be Trucker\Responses\Interpreters\HttpStatusCodeInterpreter"
        );

        $this->assertInstanceOf(
            \Trucker\Responses\Interpreters\ResponseInterpreterInterface::class, $json, "Expected transporter to implement Trucker\Responses\Interpreters\ResponseInterpreterInterface"
        );
    }

    public function testCreateInvalidInterpreter()
    {
        $this->swapConfig([
            'trucker::response.driver' => 'invalid',
        ]);
        Config::setApp($this->app);

        $this->expectException('ReflectionException');
        $this->expectException('InvalidArgumentException');
        $foo = ResponseInterpreterFactory::build();
    }
}
