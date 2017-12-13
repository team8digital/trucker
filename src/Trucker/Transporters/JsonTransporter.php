<?php

namespace Trucker\Transporters;

use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Trucker\Requests\RestRequest;

class JsonTransporter implements TransporterInterface
{
    /**
     * Function to set the appropriate headers on a request object
     * to facilitate a JSON transport.
     *
     * @param Request $request
     */
    public function setHeaderOnRequest(Request $request)
    {
        $request->setHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Function to convert a response object into an associative
     * array of data.
     *
     * @param Response $response
     *
     * @return array
     *
     * @throws \Guzzle\Common\Exception\RuntimeException
     */
    public function parseResponseToData(Response $response)
    {
        return $response->json();
    }

    /**
     * Function to parse the response string into an object
     * specific to JSON.
     *
     * @param Response $response
     *
     * @return mixed
     */
    public function parseResponseStringToObject(Response $response)
    {
        return json_decode($response->getBody(true));
    }

    /**
     * Set the request body for the given request.
     *
     * @param RestRequest $request
     * @param             $body
     */
    public function setRequestBody(RestRequest $request, $body)
    {
        $request->setBody(json_encode($body), 'application/json');
    }
}
