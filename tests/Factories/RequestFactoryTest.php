<?php

namespace Trucker\Tests\Factories;

use Trucker\Facades\Config;
use Trucker\Facades\RequestFactory;
use Trucker\Tests\TruckerTestCase;

class RequestFactoryTest extends TruckerTestCase
{
    public function testCreateValidRequest()
    {
        $this->swapConfig([
            'trucker::request.driver' => 'rest',
        ]);
        Config::setApp($this->app);

        $request = RequestFactory::build();
        $this->assertInstanceOf(
            \Trucker\Requests\RestRequest::class, $request, "Expected transporter to be Trucker\Requests\RestRequest"
        );

        $this->assertInstanceOf(
            \Trucker\Requests\RequestableInterface::class, $request, "Expected transporter to implement Trucker\Requests\RequestableInterface"
        );
    }

    public function testCreateInvalidRequest()
    {
        $this->swapConfig([
            'trucker::request.driver' => 'invalid',
        ]);
        Config::setApp($this->app);

        $this->expectException('ReflectionException');
        $this->expectException('InvalidArgumentException');
        $foo = RequestFactory::build();
    }
}
