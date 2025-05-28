<?php

namespace App\Core;

class Request {

    public string $uri;
    public string $method;
    public array $query;
    public array $request;

    public function __construct($server, $get, $post) {
        $requestUri = $_SERVER['REQUEST_URI'];
        $basePath = '/app-loove/api';
        $path = str_replace($basePath, '', $requestUri);

        $this->uri = $path;
        $this->method = $server['REQUEST_METHOD'];
        $this->query = $get;
        $this->request = $post;
    }
}