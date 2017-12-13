<?php

namespace Trucker\Tests\ErrorHandlers;

use Prophecy\Prediction\CallTimesPrediction;
use Trucker\Responses\ErrorHandlers\ParameterKeyErrorHandler;
use Trucker\Responses\Response;
use Trucker\Tests\TruckerTestCase;

class ParameterKeyErrorHandlerTest extends TruckerTestCase
{
    public function testParseErrors()
    {
        $errorsObject = ((object) ['errors' => ['name is required', 'address is required']]);
        $response = $this->prophesize(Response::class);
        $response
            ->parseResponseStringToObject()
            ->should(new CallTimesPrediction(1))
            ->willReturn($errorsObject);

        $this->swapConfig([
            'trucker::error_handler.driver' => 'parameter_key',
            'trucker::error_handler.errors_key' => 'errors',
        ]);
        $handler = new ParameterKeyErrorHandler($this->app);

        $errors = $handler->parseErrors($response->reveal());
        $this->assertCount(2, $errors, 'Expected 2 errors');

        $errorsObject = ((object) ['issues' => ['name is required', 'address is required']]);
        $response = $this->prophesize(Response::class);
        $response
            ->parseResponseStringToObject()
            ->should(new CallTimesPrediction(1))
            ->willReturn($errorsObject);

        $this->expectException('InvalidArgumentException');
        $handler->parseErrors($response->reveal());
    }
}
