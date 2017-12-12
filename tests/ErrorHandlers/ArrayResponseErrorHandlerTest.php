<?php

namespace Trucker\Tests\ErrorHandlers;

use Mockery as m;
use Trucker\Responses\ErrorHandlers\ArrayResponseErrorHandler;
use Trucker\Responses\Response;
use Trucker\Tests\TruckerTestCase;

class ArrayResponseErrorHandlerTest extends TruckerTestCase
{
    public function testParseErrors()
    {
        $response = m::mock(Response::class);
        $response->shouldReceive('parseResponseStringToObject')
            ->once()
            ->andReturn(['name is required', 'address is required']);

        $this->swapConfig([
            'trucker::error_handler.driver' => 'array_response',
        ]);
        $handler = new ArrayResponseErrorHandler($this->app);

        $errors = $handler->parseErrors($response);

        $this->assertCount(2, $errors, 'Expected 2 errors');
    }
}
