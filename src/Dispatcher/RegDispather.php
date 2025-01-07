<?php

namespace Plantation\Banana\Router\Dispatcher;

use FastRoute\Route;
use Plantation\Banana\Router\Dispatcher;

class RegDispather
{
    /**
     * @var array
     * routes
     */
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
     * @param $uri
     * @return array|void
     * 匹配uri
     */
    public function match($uri){
        //print_r($this->routes);
        foreach ($this->routes as $route) {
            if($route['urlPattern']==$uri){
                return ['status'=>Dispatcher::FOUND,'handler'=> $route['handler'], 'urlParameters' => ''];
            }

            //echo $route['reg']='~^(?|/admin/login\\.html(\\d+)/([^/]+))$~';
            if ($route['regex']){
                if (!preg_match($route['reg'], $uri, $matches)) {
                    continue;
                }else{
                    unset($matches[0]);
                    $matches = array_values($matches);

                    $vals = [];
                    $i = 0;
                    foreach ($route['regegxVal'] as $val){
                        $vals[$val] = $matches[$i];
                        $i++;
                    }
                    return ['status'=>Dispatcher::FOUND,'handler'=> $route['handler'], 'urlParameters' => $vals];
                }
            }
        }
        return ['status'=>Dispatcher::NOT_FOUND,'handler'=> '', 'urlParameters' => ''];
    }
}