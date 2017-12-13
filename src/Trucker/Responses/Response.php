<?php

namespace Trucker\Responses;

use Guzzle\Http\Message\Response as GuzzleResponse;
use Illuminate\Container\Container;
use Trucker\Facades\TransporterFactory;

class Response extends BaseResponse
{
    /**
     * The IoC Container.
     *
     * @var Container
     */
    protected $app;

    /**
     * Response object managed by this
     * class.
     *
     * @var GuzzleResponse
     */
    protected $response;

    /**
     * Response constructor.
     *
     * @param Container           $app
     * @param GuzzleResponse|null $response
     */
    public function __construct(Container $app, GuzzleResponse $response = null)
    {
        $this->app = $app;

        parent::__construct($response);
    }

    /**
     * Getter to access the IoC Container.
     *
     * @return Container
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Magic function to pass methods not found
     * on this class down to the guzzle response
     * object that is being wrapped.
     *
     * @param string $method name of called method
     * @param array  $args   arguments to the method
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (!method_exists($this, $method)) {
            return call_user_func_array(
                [$this->response, $method],
                $args
            );
        }
        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * @param Container      $app
     * @param GuzzleResponse $response
     *
     * @return Response
     */
    public function newInstance(Container $app, GuzzleResponse $response)
    {
        // This method just provides a convenient way for us to generate fresh model
        // instances of this current model. It is particularly useful during the
        // hydration of new objects via the Eloquent query builder instances.
        return new static($app, $response);
    }

    /**
     * Function to take a response object and convert it
     * into an array of data that is ready for use.
     *
     * @return array Parsed array of data
     */
    public function parseResponseToData()
    {
        $transporter = TransporterFactory::build();

        return $transporter->parseResponseToData($this->response);
    }

    /**
     * Function to take a response string (as a string) and depending on
     * the type of string it is, parse it into an object.
     *
     * @return mixed
     */
    public function parseResponseStringToObject()
    {
        $transporter = TransporterFactory::build();

        return $transporter->parseResponseStringToObject($this->response);
    }
}
