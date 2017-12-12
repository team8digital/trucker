<?php


namespace Trucker\Transporters;

use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Trucker\Requests\RestRequest;

interface TransporterInterface
{
    /**
     * Function to set the appropriate headers on a request object
     * to facilitate a particular transport language.
     *
     * @param Request $request
     */
    public function setHeaderOnRequest(Request $request);

    /**
     * Function to convert a response object into an associative
     * array of data.
     *
     * @param Response $response
     *
     * @return array
     */
    public function parseResponseToData(Response $response);

    /**
     * Function to parse the response string into an object
     * specific to the type of transport mechanism used i.e. json, xml etc.
     *
     * @param Response $response
     *
     * @return mixed
     */
    public function parseResponseStringToObject(Response $response);

    /**
     * Set the request body for the given request.
     *
     * @param RestRequest $request
     * @param             $body
     */
    public function setRequestBody(RestRequest $request, $body);
}
