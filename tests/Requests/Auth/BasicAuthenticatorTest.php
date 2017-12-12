<?php

namespace Trucker\Tests\Requests\Auth;

use Guzzle\Http\Message\Request;
use Prophecy\Argument;
use Trucker\Facades\Config;
use Trucker\Requests\Auth\BasicAuthenticator;
use Trucker\Tests\TruckerTestCase;

class BasicAuthenticatorTest extends TruckerTestCase
{
    public function testSetsAuthOnRequest()
    {
        $this->swapConfig([
            'trucker::auth.driver' => 'basic',
            'trucker::auth.basic.username' => 'myUsername',
            'trucker::auth.basic.password' => 'myPassword',
        ]);
        Config::setApp($this->app);

        $request = $this->prophesize(Request::class);
        $request
            ->setAuth(
                Argument::exact('myUsername'),
                Argument::exact('myPassword')
            )
            ->shouldBeCalled();

        $auth = new BasicAuthenticator($this->app);
        $auth->authenticateRequest($request->reveal());
    }
}
