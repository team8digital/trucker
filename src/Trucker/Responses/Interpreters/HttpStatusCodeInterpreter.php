<?php


namespace Trucker\Responses\Interpreters;

use Illuminate\Container\Container;
use Trucker\Facades\Config;
use Trucker\Responses\BaseResponse;
use Trucker\Support\Str;

class HttpStatusCodeInterpreter implements ResponseInterpreterInterface
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
     * Function to return a boolean value indicating whether
     * the request was successful or not.
     *
     * @param  $response - Guzzle response to interpret
     *
     * @return bool
     */
    public function success(BaseResponse $response)
    {
        return $this->matchesStatus('response.http_status.success', $response->getStatusCode());
    }

    /**
     * Function to return a boolean value indicating whether
     * the request indicated something was not found.
     *
     * @param  $response - Guzzle response to interpret
     *
     * @return bool
     */
    public function notFound(BaseResponse $response)
    {
        return $this->matchesStatus('response.http_status.not_found', $response->getStatusCode());
    }

    /**
     * Function to return a boolean value indicating whether
     * the request was considered invalid.
     *
     * @param  $response - Guzzle response to interpret
     *
     * @return bool
     */
    public function invalid(BaseResponse $response)
    {
        return $this->matchesStatus('response.http_status.invalid', $response->getStatusCode());
    }

    /**
     * Function to return a boolean value indicating whether
     * the request was ended in an error state.
     *
     * @param  $response - Guzzle response to interpret
     *
     * @return bool
     */
    public function error(BaseResponse $response)
    {
        return $this->matchesStatus('response.http_status.error', $response->getStatusCode());
    }

    /**
     * Function to return a boolean value indicating whether
     * the provided status is matched by the configured setting.
     *
     * Currently supports:
     *
     * @param $option
     * @param $status
     *
     * @return bool
     */
    protected function matchesStatus($option, $status)
    {
        $configValue = Config::get($option);
        if (is_array($configValue)) {
            return Config::contains($option, $status);
        }

        return Str::is($configValue, $status);
    }
}
