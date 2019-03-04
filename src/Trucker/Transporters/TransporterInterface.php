<?php

/**
 * This file is part of Trucker
 *
 * (c) Brian Webb <bwebb@indatus.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Trucker\Transporters;

use Trucker\Requests\RestRequest;

interface TransporterInterface
{

    /**
     * Function to set the appropriate headers on a request object
     * to facilitate a particular transport language
     * 
     * @param GuzzleHttpMessageRequest $request
     */
    public function setHeaderOnRequest(\GuzzleHttp\Message\Request &$request);

    /**
     * Function to convert a response object into an associative
     * array of data
     * 
     * @param  GuzzleHttpMessageResponse $response
     * @return array
     */
    public function parseResponseToData(\GuzzleHttp\Message\Response $response);

    /**
     * Function to parse the response string into an object
     * specific to the type of transport mechanism used i.e. json, xml etc
     * 
     * @param  GuzzleHttpMessageResponse $response
     * @return stdClass
     */
    public function parseResponseStringToObject(\GuzzleHttp\Message\Response $response);

    /**
     * Set the request body for the given request.
     *
     * @param RestRequest $request
     * @param             $body
     */
    public function setRequestBody(RestRequest &$request, $body);
}
