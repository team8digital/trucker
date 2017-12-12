<?php

namespace Trucker\Tests\Requests;

use Guzzle\Http\Client;
use Guzzle\Http\EntityBody;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Guzzle\Http\QueryString;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Mockery as m;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Trucker\Facades\Config;
use Trucker\Finders\Conditions\QueryConditionInterface;
use Trucker\Finders\Conditions\QueryResultOrderInterface;
use Trucker\Requests\Auth\AuthenticationInterface;
use Trucker\Requests\RestRequest;
use Trucker\Tests\Stubs\User;
use Trucker\Tests\TruckerTestCase;

class RestRequestTest extends TruckerTestCase
{
    public function testGetOption()
    {
        $config = $this->prophesize(Repository::class);
        $config
            ->get(Argument::exact('trucker::transporter.driver'))
            ->willReturn('json');

        $app = $this->prophesize(Container::class);
        $app
            ->offsetGet(Argument::exact('config'))
            ->willReturn($config->reveal());

        new RestRequest($app->reveal());

        $transporter = Config::get('transporter.driver');

        $this->assertEquals('json', $transporter);
    }

    public function testSetTransportLanguage()
    {
        $request = $this->simpleMockRequest([
            [
                'method' => 'setHeaders',
                'args' => [[
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]],
            ],
        ]);

        $r = $request->createRequest('http://example.com', '/users');

        $this->assertInstanceOf(Request::class, $r);
    }

    public function testCreateNewRequest()
    {
        $request = $this->simpleMockRequest([
            [
                'method' => 'setHeaders',
                'args' => [[
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]],
            ],
        ]);
        $result = $request->createRequest('http://example.com', '/users');
        $this->assertInstanceOf(Request::class, $result);
    }

    public function testSetPostParameters()
    {
        $request = $this->simpleMockRequest([
            [
                'method' => 'setHeaders',
                'args' => [[
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]],
            ],
            ['method' => 'setPostField', 'args' => ['biz', 'banng']],
        ]);

        $request->createRequest('http://example.com', '/users', 'GET');
        $request->setPostParameters(['biz' => 'banng']);
    }

    public function testSetGetParameters()
    {
        $mQuery = $this->prophesize(QueryString::class);
        $mQuery->add(
            Argument::exact('foo'),
            Argument::exact('bar')
        );

        $request = $this->simpleMockRequest([
            [
                'method' => 'setHeaders',
                'args' => [[
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]],
            ],
            ['method' => 'getQuery', 'return' => $mQuery],
        ]);

        $request->createRequest('http://example.com', '/users', 'GET');
        $request->setGetParameters(['foo' => 'bar']);
    }

    public function testSetFileParameters()
    {
        $request = $this->simpleMockRequest([
            [
                'method' => 'setHeaders',
                'args' => [[
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]],
            ],
            ['method' => 'addPostFile', 'args' => ['fileOne', '/path/to/fileOne']],
        ]);

        $request->createRequest('http://example.com', '/users', 'GET');
        $request->setFileParameters(['fileOne' => '/path/to/fileOne']);
    }

    public function testSettingModelProperties()
    {
        $request = $this->simpleMockRequest([
            [
                'method' => 'setHeaders',
                'args' => [[
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]],
            ],
            ['method' => 'setPostField', 'args' => ['foo', 'bar']],
            ['method' => 'setPostField', 'args' => ['biz', 'bang']],
            ['method' => 'addPostFile', 'args' => ['fOne', '/path/to/file/one']],
            ['method' => 'addPostFile', 'args' => ['fTwo', '/path/to/file/two']],
        ]);

        $attributes = [
            'foo' => 'bar',
            'biz' => 'bang',
            'roOne' => 'roOneVal',
            'roTwo' => 'roTwoVal',
            'fOne' => '/path/to/file/one',
            'fTwo' => '/path/to/file/two',
        ];

        $mUser = $this->prophesize(User::class);
        $mUser->getReadOnlyFields()->willReturn(['roOne', 'roTwo']);
        $mUser->attributes()->willReturn($attributes);
        $mUser->getFileFields()->willReturn(['fOne', 'fTwo']);

        $request->createRequest('http://example.com', '/users');
        $request->setModelProperties($mUser->reveal());
    }

    public function testSettingHeaders()
    {
        $request = $this->simpleMockRequest([
            [
                'method' => 'setHeaders',
                'args' => [[
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]],
            ],
            ['method' => 'setHeader', 'args' => ['Cache-Control', 'no-cache, must-revalidate']],
        ]);

        $headers = ['Cache-Control' => 'no-cache, must-revalidate'];
        $request->createRequest('http://example.com', '/users', 'GET', $headers);
    }

    public function testSettingBody()
    {
        $request = \Guzzle\Http\Message\RequestFactory::getInstance()->create('PUT', 'http://www.test.com/');
        $request->setBody(EntityBody::factory('test'));
        $this->assertEquals(4, (string) $request->getHeader('Content-Length'));
        $this->assertFalse($request->hasHeader('Transfer-Encoding'));
    }

    public function testAddingErrorHandler()
    {
        $dispatcher = $this->prophesize(EventDispatcher::class);
        $dispatcher->addListener(Argument::any(), Argument::any())->shouldBeCalled();

        $request = $this->simpleMockRequest([
            [
                'method' => 'setHeaders',
                'args' => [[
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]],
            ],
            ['method' => 'getEventDispatcher', 'return' => $dispatcher->reveal()],
        ]);

        $func = function ($event, $request) {
            return true;
        };

        $r = $request->createRequest('http://example.com', '/users', 'GET');
        $request->addErrorHandler(200, $func, true);
    }

    public function testAddQueryCondition()
    {
        $request = $this->simpleMockRequest([
            [
                'method' => 'setHeaders',
                'args' => [[
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]],
            ],
        ]);
        $r = $request->createRequest('http://example.com', '/users', 'GET');

        $c = $this->prophesize(QueryConditionInterface::class);
        $c
            ->addToRequest(Argument::exact($r))
            ->shouldBeCalled();

        $request->addQueryCondition($c->reveal());
    }

    public function testAddQueryResultOrder()
    {
        $request = $this->simpleMockRequest([
            [
                'method' => 'setHeaders',
                'args' => [[
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]],
            ],
        ]);
        $r = $request->createRequest('http://example.com', '/users', 'GET');

        $o = $this->prophesize(QueryResultOrderInterface::class);
        $o
            ->addToRequest(Argument::exact($r))
            ->shouldBeCalled();

        $request->addQueryResultOrder($o->reveal());
    }

    public function testAddAuthentication()
    {
        $request = $this->simpleMockRequest([
            [
                'method' => 'setHeaders',
                'args' => [[
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]],
            ],
        ]);
        $r = $request->createRequest('http://example.com', '/users', 'GET');

        $auth = $this->prophesize(AuthenticationInterface::class);
        $auth->authenticateRequest(Argument::exact($r))->shouldBeCalled();

        $request->authenticate($auth->reveal());
    }

    public function testSendRequest()
    {
        $request = $this->simpleMockRequest([
            [
                'method' => 'setHeaders',
                'args' => [[
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]],
            ],
            ['method' => 'send', 'return' => $this->prophesize(Response::class)->reveal()],
        ]);

        $request->createRequest('http://example.com', '/users', 'GET');
        $request->sendRequest();
    }

    public function testHttpMethodParam()
    {
        $request = $this->simpleMockRequest(
            [
                [
                    'method' => 'setHeaders',
                    'args' => [[
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ]],
                ],
                ['method' => 'setPostField', 'args' => ['_method', 'PUT']],
            ],
            'http://example.com',
            '/users/1',
            'post'
        );

        $request->createRequest('http://example.com', '/users/1', 'PUT', [], '_method');
    }

    /**
     * @param array  $shouldReceive
     * @param string $baseUrl
     * @param string $uri
     * @param string $method
     *
     * @return RestRequest
     */
    private function simpleMockRequest(
        array $shouldReceive = [],
        $baseUrl = 'http://example.com',
        $uri = '/users',
        $method = 'get'
    ) {
        $mockRequest = m::mock('Guzzle\Http\Message\Request');

        foreach ($shouldReceive as $sr) {
            $mr = $mockRequest->shouldReceive($sr['method']);

            if (array_key_exists('args', $sr)) {
                call_user_func_array([$mr, 'with'], $sr['args']);
            }

            if (array_key_exists('return', $sr)) {
                $mr->andReturn($sr['return']);
            }

            $mr->times(array_key_exists('times', $sr) ? $sr['times'] : 1);
        }

        $client = $this->prophesize(Client::class);
        $client->setBaseUrl(Argument::exact($baseUrl))->shouldBeCalled();
        $client->{$method}(Argument::exact($uri))->willReturn($mockRequest);

        return new \Trucker\Requests\RestRequest($this->app, $client->reveal());
    }
}
