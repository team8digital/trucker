<?php

namespace Trucker\Tests\Factories;

use Trucker\Facades\AuthFactory;
use Trucker\Facades\Config;
use Trucker\Tests\TruckerTestCase;

class AuthFactoryTest extends TruckerTestCase
{
    public function testCreateValidAuthenticator()
    {
        $this->swapConfig([
            'trucker::auth.driver' => 'basic',
        ]);
        Config::setApp($this->app);

        $json = AuthFactory::build();
        $this->assertInstanceOf(
            \Trucker\Requests\Auth\BasicAuthenticator::class, $json, "Expected transporter to be Trucker\Requests\Auth\BasicAuthenticator"
        );

        $this->assertInstanceOf(
            \Trucker\Requests\Auth\AuthenticationInterface::class, $json, "Expected transporter to implement Trucker\Requests\Auth\AuthenticationInterface"
        );
    }

    public function testCreateInvalidTransporter()
    {
        $this->swapConfig([
            'trucker::auth.driver' => 'invalid',
        ]);
        Config::setApp($this->app);

        $this->expectException('ReflectionException');
        $this->expectException('InvalidArgumentException');
        $foo = AuthFactory::build();
    }
}
