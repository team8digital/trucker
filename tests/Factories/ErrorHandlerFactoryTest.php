<?php

namespace Trucker\Tests\Factories;

use Trucker\Facades\Config;
use Trucker\Facades\ErrorHandlerFactory;
use Trucker\Tests\TruckerTestCase;

class ErrorHandlerFactoryTest extends TruckerTestCase
{
    public function tearDown()
    {
        parent::tearDown();
        $this->swapConfig([]);
        Config::setApp($this->app);
    }

    public function testCreateValidErrorHandler()
    {
        $this->swapConfig([
            'trucker::error_handler.driver' => 'array_response',
        ]);
        Config::setApp($this->app);

        $json = ErrorHandlerFactory::build();
        $this->assertInstanceOf(
            \Trucker\Responses\ErrorHandlers\ArrayResponseErrorHandler::class, $json, "Expected transporter to be Trucker\Responses\ErrorHandlers\ArrayResponseErrorHandler"
        );

        $this->assertInstanceOf(
            \Trucker\Responses\ErrorHandlers\ErrorHandlerInterface::class, $json, "Expected transporter to implement Trucker\Responses\ErrorHandlers\ErrorHandlerInterface"
        );
    }

    public function testCreateInvalidTransporter()
    {
        $this->swapConfig([
            'trucker::error_handler.driver' => 'invalid',
        ]);
        Config::setApp($this->app);

        $this->expectException('ReflectionException');
        $this->expectException('InvalidArgumentException');
        $foo = ErrorHandlerFactory::build();
    }
}
