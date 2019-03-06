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
     * to facilitate a particular transport format
     * 
     * @param GuzzleMessageRequest $request
     */
    public function setHeaderOnRequest(\Guzzle\Http\Message\Request &$request);

    /**
     * Function to convert a response object into an associative
     * array of data
     * 
     * @param  GuzzleMessageResponse $response
     * @return array
     */
    public function parseResponseToData(\Guzzle\Http\Message\Response $response);

    /**
     * Function to parse the response string into an object
     * specific to the type of transport format used i.e. json, xml etc
     * 
     * @param  GuzzleMessageResponse $response
     * @return stdClass
     */
    public function parseResponseStringToObject(\Guzzle\Http\Message\Response $response);

    /**
     * Set the request body for the given request.
     *
     * @param RestRequest $request
     * @param             $body
     */
    public function setRequestBody(RestRequest &$request, $body);
}
