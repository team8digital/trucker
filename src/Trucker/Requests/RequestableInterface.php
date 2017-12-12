<?php

namespace Trucker\Requests;

use Illuminate\Container\Container;
use Trucker\Finders\Conditions\QueryConditionInterface;
use Trucker\Finders\Conditions\QueryResultOrderInterface;
use Trucker\Requests\Auth\AuthenticationInterface;
use Trucker\Resource\Model;

/**
 * Interface to dictate management of query conditions for a request.
 */
interface RequestableInterface
{
    public function __construct(Container $app, $client = null);

    public function getClient();

    public function createRequest($baseUri, $path, $httpMethod = 'GET', array $requestHeaders = [], $httpMethodParam = null);

    public function setHeaders(array $requestHeaders = []);

    public function setBody($body, $contentType = null);

    public function setPostParameters(array $params = []);

    public function setGetParameters(array $params = []);

    public function setFileParameters(array $params = []);

    public function setModelProperties(Model $model);

    public function setTransportLanguage();

    public function addErrorHandler($httpStatus, \Closure $func, $stopPropagation = true);

    public function addQueryCondition(QueryConditionInterface $condition);

    public function addQueryResultOrder(QueryResultOrderInterface $resultOrder);

    public function authenticate(AuthenticationInterface $auth);

    public function sendRequest();
}
