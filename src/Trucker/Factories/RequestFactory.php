<?php

namespace Trucker\Factories;

use Guzzle\Http\Client;
use Illuminate\Container\Container;
use Trucker\Facades\Config;
use Trucker\Framework\FactoryDriver;

class RequestFactory extends FactoryDriver
{
    /**
     * Guzzle Client attached to request
     * returned by build().
     *
     * @var Client
     */
    protected $client;

    /**
     * Build a new FactoryDriver.
     *
     * @param Container $app
     *
     * @throws \Guzzle\Common\Exception\RuntimeException
     */
    public function __construct(Container $app)
    {
        parent::__construct($app);
        $this->client = new Client();
    }

    /**
     * Getter function to access the HTTP Client.
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Function to return a string representaion of the namespace
     * that all classes built by the factory should be contained within.
     *
     * @return string - namespace string
     */
    public function getDriverNamespace()
    {
        return "\Trucker\Requests";
    }

    /**
     * Function to return the interface that the driver's produced
     * by the factory must implement.
     *
     * @return string
     */
    public function getDriverInterface()
    {
        return "\Trucker\Requests\RequestableInterface";
    }

    /**
     * Function to return a string that should be suffixed
     * to the studly-cased driver name of all the drivers
     * that the factory can return.
     *
     * @return string
     */
    public function getDriverNameSuffix()
    {
        return 'Request';
    }

    /**
     * Function to return a string that should be prefixed
     * to the studly-cased driver name of all the drivers
     * that the factory can return.
     *
     * @return string
     */
    public function getDriverNamePrefix()
    {
        return '';
    }

    /**
     * Function to return an array of arguments that should be
     * passed to the constructor of a new driver instance.
     *
     * @return array
     */
    public function getDriverArgumentsArray()
    {
        return [$this->app, $this->client];
    }

    /**
     * Function to return the string representation of the driver
     * itslef based on a value fetched from the config file.  This
     * function will itself access the config, and return the driver
     * setting.
     *
     * @return string
     */
    public function getDriverConfigValue()
    {
        return Config::get('request.driver');
    }
}
