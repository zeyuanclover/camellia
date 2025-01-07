<?php

namespace Plantation\Banana\Router;

use Plantation\Banana\Router\Dispatcher\RegDispather;

class Dispatcher
{
    const FOUND = 200;

    const NOT_FOUND = 404;

    const METHOD_NOT_ALLOWED = 405;

    protected $routes = [];

    /**
     * @param $routes
     * 构造函数
     */
    public function __construct($routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param $url
     * @return mixed
     * 验证url
     */
    public function dispatch($url){
        $class = new RegDispather($this->routes);
        return $class->match($url);
    }
}