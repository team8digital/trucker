<?php

namespace Trucker\Requests\Auth;

use Guzzle\Http\Message\Request;
use Illuminate\Container\Container;

interface AuthenticationInterface
{
    /**
     * Constructor, likely never called in implementation
     * but rather through the Factory.
     *
     * @param Container $app
     */
    public function __construct(Container $app);

    /**
     * Function to add the necessary authentication
     * to the request.
     *
     * @param Request $request Request passed by reference
     */
    public function authenticateRequest($request);
}
