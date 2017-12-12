<?php


namespace Trucker\Responses\ErrorHandlers;

use Illuminate\Container\Container;
use Trucker\Responses\Response;

interface ErrorHandlerInterface
{
    /**
     * Constructor to setup the interpreter.
     *
     * @param Container $app
     */
    public function __construct(Container $app);

    /**
     * Function to take the response object and return
     * an array of errors.
     *
     * @param Response $response - response object
     *
     * @return array - array of string error messages
     */
    public function parseErrors(Response $response);
}
