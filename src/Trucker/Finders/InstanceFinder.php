<?php

namespace Trucker\Finders;

use Illuminate\Container\Container;
use Trucker\Facades\AuthFactory;
use Trucker\Facades\Config;
use Trucker\Facades\RequestFactory;
use Trucker\Facades\ResponseInterpreterFactory;
use Trucker\Facades\UrlGenerator;
use Trucker\Resource\Model;

/**
 * Class for finding model instances over the remote API.
 *
 * @author Brian Webb <bwebb@indatus.com>
 */
class InstanceFinder
{
    /**
     * The IoC Container.
     *
     * @var Container
     */
    protected $app;

    /**
     * Build a new InstanceFinder.
     *
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Function to find an instance of an Entity record.
     *
     * @param Model $model     model to use for URL generation etc
     * @param int   $id        The primary identifier value for the record
     * @param array $getParams Array of GET parameters to pass
     *
     * @return Model An instance of the entity requested
     */
    public function fetch($model, $id, array $getParams = [])
    {
        $instance = null;

        //get a request object
        $request = RequestFactory::build();

        //init the request
        $request->createRequest(
            Config::get('request.base_uri'),
            UrlGenerator::getInstanceUri($model, [':id' => $id]),
            'GET'
        );

        //add auth if it is needed
        if ($auth = AuthFactory::build()) {
            $request->authenticate($auth);
        }

        //set any get parameters on the request
        $request->setGetParameters($getParams);

        //actually send the request
        $response = $request->sendRequest();

        if (!ResponseInterpreterFactory::build()->success($response)) {
            return null;
        }

        //kraft the response into an object to return
        $data = $response->parseResponseToData();
        $instance = new $model($data);

        //inflate the ID property that should be guarded
        $id = $instance->getIdentityProperty();
        if (array_key_exists($id, $data)) {
            $instance->{$id} = $data[$id];
        }

        return $instance;
    }
}
