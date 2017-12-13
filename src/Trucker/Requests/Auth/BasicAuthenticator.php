<?php

namespace Trucker\Requests\Auth;

use Guzzle\Http\Message\Request;
use Illuminate\Container\Container;
use Trucker\Facades\Config;

class BasicAuthenticator implements AuthenticationInterface
{
    /**
     * The IoC Container.
     *
     * @var Container
     */
    protected $app;

    /**
     * Constructor, likely never called in implementation
     * but rather through the Factory.
     *
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Function to add the necessary authentication
     * to the request.
     *
     * @param Request $request Request passed by reference
     */
    public function authenticateRequest($request)
    {
        $username = Config::get('auth.basic.username');
        $password = Config::get('auth.basic.password');
        $request->setAuth($username, $password);
    }
}
