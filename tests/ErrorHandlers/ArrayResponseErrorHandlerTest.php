<?php

namespace Trucker\Tests\ErrorHandlers;

use Prophecy\Prediction\CallTimesPrediction;
use Trucker\Responses\ErrorHandlers\ArrayResponseErrorHandler;
use Trucker\Responses\Response;
use Trucker\Tests\TruckerTestCase;

class ArrayResponseErrorHandlerTest extends TruckerTestCase
{
    public function testParseErrors()
    {
        $response = $this->prophesize(Response::class);
        $response->parseResponseStringToObject()
            ->should(new CallTimesPrediction(1))
            ->willReturn(['name is required', 'address is required']);

        $this->swapConfig([
            'trucker::error_handler.driver' => 'array_response',
        ]);
        $handler = new ArrayResponseErrorHandler($this->app);

        $errors = $handler->parseErrors($response->reveal());

        $this->assertCount(2, $errors, 'Expected 2 errors');
    }
}
