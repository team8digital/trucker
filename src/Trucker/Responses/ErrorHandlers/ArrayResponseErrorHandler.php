<?php

namespace Trucker\Responses\ErrorHandlers;

use Illuminate\Container\Container;
use Trucker\Responses\Response;

class ArrayResponseErrorHandler implements ErrorHandlerInterface
{
    /**
     * The IoC Container.
     *
     * @var Container
     */
    protected $app;

    /**
     * Constructor to setup the interpreter.
     *
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Function to take the response object and return
     * an array of errors.
     *
     * @param Response $response - response object
     *
     * @return array - array of string error messages
     */
    public function parseErrors(Response $response)
    {
        return $response->parseResponseStringToObject();
    }
}
