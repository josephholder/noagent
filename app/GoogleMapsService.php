<?php

namespace App;

use GuzzleHttp\Client;

class GoogleMapsService
{
    protected $method;

    protected $uri;

    protected $options;

    public function __construct($method, $uri = '', $options = [])
    {
        $this->setMethod($method);
        $this->setUri($uri);
        $this->setOptions($options);
    }

    /**
     * @return string
     */
    private function getUri()
    {
        return $this->uri;
    }

    /**
     * @param $uri
     */
    private function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    private function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    private function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    private function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    private function setOptions($options)
    {
        $this->options = $options;
    }

    public function send() {
        $client = new Client([
            'verify' => false, // required as the url is not running on an SSL Certificate
            'allow_redirect' => false,
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'base_uri' => env('GOOGLE_MAPS_API_URI'),
        ]);

        $response = $client->request(
            $this->getMethod(),
            $this->getUri(),
            $this->getOptions()
        );

        return [
            'code' => $response->getStatusCode(),
            'data' => $response->getBody()->getContents()
        ];
    }
}
