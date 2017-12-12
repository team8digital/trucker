<?php

namespace Trucker\Tests\Requests;

use Trucker\Facades\RequestFactory;
use Trucker\Tests\Helpers\GuzzleTestingTrait;
use Trucker\Tests\TruckerTestCase;

class RawRequestMethodsTest extends TruckerTestCase
{
    use GuzzleTestingTrait;

    public function testRawGet()
    {
        //some vars for our test
        $uri = '/users';
        $base_uri = 'http://some-api.com';
        $queryParams = ['foo' => 'bar', 'biz' => 'bang'];
        $response_body = json_encode(['id' => 123, 'name' => 'foo']);
        $headers = ['Content-Type' => 'application/json'];

        //mock the response we expect
        $this->mockHttpResponse(
            //
            //config overrides & return client
            //
            $this->initGuzzleRequestTest([
                'trucker::request.base_uri' => $base_uri,
                'trucker::request.driver' => 'rest',
            ]),
            //
            //expected status
            //
            200,
            //
            //HTTP response headers
            //
            [
                'Location' => $base_uri . '/' . $uri,
                'Content-Type' => 'application/json',
            ],
            //
            //response to return
            //
            $response_body
        );

        //execute what we're testing
        $request = RequestFactory::build();
        $rawResponse = $request->rawGet($uri, $queryParams, $headers);

        //get objects to assert on
        $history = $this->getHttpClientHistory();
        $request = $history->getLastRequest();
        $response = $history->getLastResponse();

        $this->makeGuzzleAssertions(
            'GET',
            $base_uri,
            $uri,
            $queryParams
        );

        //assert that the HTTP RESPONSE is what is expected
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($response_body, $response->getBody(true));
        $this->assertInstanceOf(
            \Trucker\Responses\RawResponse::class, $rawResponse
        );
    }

    public function testRawPost()
    {
        //some vars for our test
        $uri = '/users';
        $base_uri = 'http://some-api.com';
        $postParams = ['foo' => 'bar', 'biz' => 'bang'];
        $response_body = json_encode(['id' => 123, 'name' => 'foo']);
        $headers = ['Content-Type' => 'application/json'];

        //mock the response we expect
        $this->mockHttpResponse(
            //
            //config overrides & return client
            //
            $this->initGuzzleRequestTest([
                'trucker::request.base_uri' => $base_uri,
                'trucker::request.driver' => 'rest',
            ]),
            //
            //expected status
            //
            200,
            //
            //HTTP response headers
            //
            [
                'Location' => $base_uri . '/' . $uri,
                'Content-Type' => 'application/json',
            ],
            //
            //response to return
            //
            $response_body
        );

        //execute what we're testing
        $request = RequestFactory::build();
        $rawResponse = $request->rawPost($uri, $postParams, $headers);

        //get objects to assert on
        $history = $this->getHttpClientHistory();
        $request = $history->getLastRequest();
        $response = $history->getLastResponse();

        $this->makeGuzzleAssertions(
            'POST',
            $base_uri,
            $uri,
            [],
            $postParams
        );

        //assert that the HTTP RESPONSE is what is expected
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($response_body, $response->getBody(true));
        $this->assertInstanceOf(
            \Trucker\Responses\RawResponse::class, $rawResponse
        );
    }

    public function testRawPut()
    {
        //some vars for our test
        $uri = '/users/1';
        $base_uri = 'http://some-api.com';
        $postParams = ['foo' => 'bar', 'biz' => 'bang'];
        $response_body = json_encode(['id' => 123, 'name' => 'foo']);
        $headers = ['Content-Type' => 'application/json'];

        //mock the response we expect
        $this->mockHttpResponse(
            //
            //config overrides & return client
            //
            $this->initGuzzleRequestTest([
                'trucker::request.base_uri' => $base_uri,
                'trucker::request.driver' => 'rest',
            ]),
            //
            //expected status
            //
            200,
            //
            //HTTP response headers
            //
            [
                'Location' => $base_uri . '/' . $uri,
                'Content-Type' => 'application/json',
            ],
            //
            //response to return
            //
            $response_body
        );

        //execute what we're testing
        $request = RequestFactory::build();
        $rawResponse = $request->rawPut($uri, $postParams, $headers);

        //get objects to assert on
        $history = $this->getHttpClientHistory();
        $request = $history->getLastRequest();
        $response = $history->getLastResponse();

        $this->makeGuzzleAssertions(
            'PUT',
            $base_uri,
            $uri,
            [],
            $postParams
        );

        //assert that the HTTP RESPONSE is what is expected
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($response_body, $response->getBody(true));
        $this->assertInstanceOf(
            \Trucker\Responses\RawResponse::class, $rawResponse
        );
    }

    public function testRawPatch()
    {
        //some vars for our test
        $uri = '/users/1';
        $base_uri = 'http://some-api.com';
        $postParams = ['foo' => 'bar', 'biz' => 'bang'];
        $response_body = json_encode(['id' => 123, 'name' => 'foo']);
        $headers = ['Content-Type' => 'application/json'];

        //mock the response we expect
        $this->mockHttpResponse(
            //
            //config overrides & return client
            //
            $this->initGuzzleRequestTest([
                'trucker::request.base_uri' => $base_uri,
                'trucker::request.driver' => 'rest',
            ]),
            //
            //expected status
            //
            200,
            //
            //HTTP response headers
            //
            [
                'Location' => $base_uri . '/' . $uri,
                'Content-Type' => 'application/json',
            ],
            //
            //response to return
            //
            $response_body
        );

        //execute what we're testing
        $request = RequestFactory::build();
        $rawResponse = $request->rawPatch($uri, $postParams, $headers);

        //get objects to assert on
        $history = $this->getHttpClientHistory();
        $request = $history->getLastRequest();
        $response = $history->getLastResponse();

        $this->makeGuzzleAssertions(
            'PUT',
            $base_uri,
            $uri,
            [],
            $postParams
        );

        //assert that the HTTP RESPONSE is what is expected
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($response_body, $response->getBody(true));
        $this->assertInstanceOf(
            \Trucker\Responses\RawResponse::class, $rawResponse
        );
    }

    public function testRawDelete()
    {
        //some vars for our test
        $uri = '/users/1';
        $base_uri = 'http://some-api.com';
        $queryParams = ['foo' => 'bar', 'biz' => 'bang'];
        $response_body = json_encode(['id' => 123, 'name' => 'foo']);
        $headers = ['Content-Type' => 'application/json'];

        //mock the response we expect
        $this->mockHttpResponse(
            //
            //config overrides & return client
            //
            $this->initGuzzleRequestTest([
                'trucker::request.base_uri' => $base_uri,
                'trucker::request.driver' => 'rest',
            ]),
            //
            //expected status
            //
            200,
            //
            //HTTP response headers
            //
            [
                'Location' => $base_uri . '/' . $uri,
                'Content-Type' => 'application/json',
            ],
            //
            //response to return
            //
            $response_body
        );

        //execute what we're testing
        $request = RequestFactory::build();
        $rawResponse = $request->rawDelete($uri, $queryParams, $headers);

        //get objects to assert on
        $history = $this->getHttpClientHistory();
        $request = $history->getLastRequest();
        $response = $history->getLastResponse();

        $this->makeGuzzleAssertions(
            'DELETE',
            $base_uri,
            $uri,
            $queryParams
        );

        //assert that the HTTP RESPONSE is what is expected
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($response_body, $response->getBody(true));
        $this->assertInstanceOf(
            \Trucker\Responses\RawResponse::class, $rawResponse
        );
    }
}
