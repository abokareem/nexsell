<?php namespace Ddedic\Nexsell;

use Guzzle\Http\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Api {

    /**
     * Repository config.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Router
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Request
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Remote client.
     *
     * @var \Guzzle\Http\Client
     */
    protected $remoteClient;

    /**
     * @var array HTTP response codes and messages
     */
    protected $statuses;

    /**
     * Instance API.
     *
     * @param Repository $config  $router
     * @param Router     $router
     * @param Request    $request
     * @param Client     $remote
     */
    public function __construct(Repository $config, Router $router, Request $request, Client $remoteClient)
    {
        $this->config = $config->get('nexsell::api-responses');
        
        $this->statuses = $this->config['statuses'];

        $this->router = $router;

        $this->request = $request;

        $this->remoteClient = $remoteClient;
    }

    /**
     * Create API response.
     *
     * @param  mixed   $messages
     * @param  integer $code
     * @return string
     */
    public function createResponse($messages, $code = 200)
    {
        return $this->make($messages, $code);
    }

    /**
     * Custom API response.
     *
     * @param  mixed   $messages
     * @param  integer $code
     * @return string
     */
    public function deviseResponse($messages, $code = 200)
    {
        return $this->make($messages, $code, true);
    }

    /**
     * Make json data format.
     *
     * @param  mixed   $data
     * @param  integer $code
     * @param  boolean $overwrite
     * @return string
     */
    public function make($data, $code, $overwrite = false)
    {
        // Status returned.
        $status = (preg_match('/^(1|2|3)/', $code)) ? 'success' : 'error';

        // Change object to array.
        if (is_object($data))
        {
            $data = $data->toArray();
        }

        // Data as a string.
        if (is_string($data))
        {
            $data = array('message' => $data);
        }

        // Overwrite response format.
        if ($overwrite === true)
        {
            $response = $data;
        }
        else
        {
            $message = isset($this->statuses[$code]) ? $this->statuses[$code] : $this->statuses[40];

            // Custom return message.
            if (isset($data['message']))
            {
                $message = $data['message'];

                unset($data['message']);
            }

            // Available data response.
            $response = array(
                'status'     => $status,
                'code'       => isset($this->statuses[$code]) ? $code : 40,
                'message'    => $message,
                'data'       => $data,
                'pagination' => null
            );

            // Merge if data has anything else.
            if (isset($data['data']))
            {
                $response = array_merge($response, $data);
            }

            // Remove empty array.
            $response = array_filter($response, function($value)
            {
                return ! is_null($value);
            });

            // Remove empty data.
            if (empty($response['data']))
            {
                unset($response['data']);
            }
        }

        // Header response.
        $header = ($this->config['httpResponse']) ? $code : 200;

        return Response::json($response, $header);
    }

    /**
     * Remote client for http request.
     *
     * @return Client
     */
    public function getRemoteClient()
    {
        return $this->remoteClient;
    }

    /**
     * Call internal URI with parameters.
     *
     * @param  string $uri
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
     */
    public function invoke($uri, $method, $parameters = array())
    {
        // Request URI.
        $uri = '/'.ltrim($uri, '/');

        // Parameters for GET, POST
        $parameters = ($parameters) ? current($parameters) : array();

        try
        {
            // store the original request data and route
            $originalInput = $this->request->input();

            $originalRoute = $this->router->getCurrentRoute();

            // create a new request to the API resource
            $request = $this->request->create($uri, strtoupper($method), $parameters);

            // replace the request input...
            $this->request->replace($request->input());

            $dispatch = $this->router->dispatch($request);

            if (method_exists($dispatch, 'getOriginalContent'))
            {
                $response = $dispatch->getOriginalContent();
            }
            else
            {
                $response = $dispatch->getContent();
            }

            // Decode json content.
            if ($dispatch->headers->get('content-type') == 'application/json')
            {
                if (function_exists('json_decode') and is_string($response))
                {
                    $response = json_decode($response, true);
                }
            }

            // replace the request input and route back to the original state
            $this->request->replace($originalInput);
            $this->router->setCurrentRoute($originalRoute);

            return $response;
        }
        catch (NotFoundHttpException $e) { }
    }

    /**
     * Invoke with remote uri.
     *
     * @param  string $uri
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
     */
    public function invokeRemote($uri, $method, $parameters = array())
    {
        $remoteClient = $this->getRemoteClient();

        // Parameters for GET, POST
        $parameters = ($parameters) ? current($parameters) : array();

        // Make request.
        $request = $remoteClient->createRequest($method, $uri, array(), $parameters, array());

        // Send request.
        
        try {
            
            $response = $request->send();    

        } catch (\Guzzle\Common\Exception\GuzzleException $e) {
            
            //dd($e->getResponse()->getReasonPhrase());
            $response = array(
                'status'     => 'error',
                'code'       => $e->getResponse()->getStatusCode(),
                'message'    => $e->getResponse()->getReasonPhrase()
            );

            return Response::json($response);            
        }


        // Body responsed.
        $body = (string) $response->getBody();


        // Decode json content.
        if ($response->getContentType() == 'application/json')
        {
            if (function_exists('json_decode') and is_string($body))
            {
                $body = json_decode($body, true);
            }
        }

        return $body;
    }

    /**
     * Alias call method.
     *
     * @return mixed
     */
    public function __call($method, $parameters = array())
    {
        if (in_array($method, array('get', 'post', 'put', 'delete')))
        {
            $uri = array_shift($parameters);

            if (preg_match('/^http(s)?/', $uri))
            {
                return $this->invokeRemote($uri, $method, $parameters);
            }

            return $this->invoke($uri, $method, $parameters);
        }
    }

}