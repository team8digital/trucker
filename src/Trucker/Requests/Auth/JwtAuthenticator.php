<?php

/**
 * This file is part of Trucker
 *
 * (c) Brian Webb <bwebb@indatus.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Trucker\Requests\Auth;

use Illuminate\Container\Container;
use Trucker\Facades\Config;
use Trucker\Requests\Auth\AuthenticationInterface;

class JwtAuthenticator implements AuthenticationInterface
{
    /**
     * The IoC Container
     *
     * @var Illuminate\Container\Container
     */
    protected $app;

    /**
     * Constructor, likely never called in implementation
     * but rather through the Factory
     *
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Function to add the necessary authentication
     * to the request
     *
     * @param GuzzleHttp\Message\Request $request Request passed by reference
     * @return  void
     */
    public function authenticateRequest(&$request)
    {
        if (!Config::get('auth.jwt')) {
            $username = Config::get('auth.basic.username');
            $password = Config::get('auth.basic.password');
            $language = Config::get('request.language') ?? 'en';

            $client = new Client([
                'base_uri' => Config::get('request.base_uri'),
                'X-localization' => $language
            ]);
            try {
                $response = $client->post('/auth', [
                    'form_params' => [
                        'api_username'=>$username,
                        'api_password'=>$password,
                        'username'=>'lincoln.watsica',
                        'password'=>'1234',

                    ]
                ]);
                $body = (string) $response->getBody();
                $body = json_decode($body);
                Config::set('auth.jwt', $body->token);
            } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                $response = $e->getResponse();
            }
        }
        if (Config::get('auth.jwt')) {
            return ['Authorization' => 'Bearer '.Config::get('auth.jwt')];
        }

        return [];
    }
}
