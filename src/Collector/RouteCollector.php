<?php

namespace Vegetation\Camellia\Collector;

use Vegetation\Camellia\DataGenerator\DataGenerator;

class RouteCollector
{
    /**
     * @var string
     * 路由组
     */
    private $currentRouteGroupPrefix = '';

    /**
     * @var DataGenerator
     * 生产数据
     */
    protected $dataGenerator;

    /**
     * @param DataGenerator $dataGenerator
     * 构造函数
     */
    public function __construct(DataGenerator $dataGenerator,$cache){
        $this->currentRouteGroupPrefix = '';
        $this->dataGenerator = $dataGenerator;
        $this->dataGenerator->cache = $cache;
    }

    /**
     * @param $prefix
     * @param $callback
     * @return void
     * 新增路由组
     */
    public function addGroup($prefix,$callback){
        $previousGroupPrefix = $this->currentRouteGroupPrefix;
        $this->currentRouteGroupPrefix = $previousGroupPrefix . $prefix;
        $callback($this);
        $this->currentRouteGroupPrefix = $previousGroupPrefix;
    }

    /**
     * @param $method
     * @param $url
     * @param $handler
     * @return void
     * 新增路由
     */
    public function addRoute($method,$url,$handler){
        $url = $this->currentRouteGroupPrefix.$url;
        $method = strtoupper($method);
        $allowedMethods = ['GET','HEAD','POST','PUT','DELETE','OPTIONS','PATCH','OPTIONS','CONNECT','TRACE'];
        if(!in_array($method,$allowedMethods)){
            throw new \InvalidArgumentException("Invalid route method '$method'");
        }
        $this->dataGenerator->addRoute($method,$url,$handler);
    }

    /**
     * @param $url
     * @param $handler
     * @return void
     * get
     */
    public function get($url,$handler){
        $method = 'GET';
        $this->dataGenerator->addRoute($method,$url,$handler);
    }

    /**
     * @param $url
     * @param $handler
     * @return void
     * post
     */
    public function post($url,$handler){
        $method = 'POST';
        $this->dataGenerator->addRoute($method,$url,$handler);
    }

    /**
     * @param $url
     * @param $handler
     * @return void
     * put
     */
    public function put($url,$handler){
        $method = 'PUT';
        $this->dataGenerator->addRoute($method,$url,$handler);
    }

    /**
     * @param $url
     * @param $handler
     * @return void
     * delete
     */
    public function delete($url,$handler){
        $method = 'DELETE';
        $this->dataGenerator->addRoute($method,$url,$handler);
    }

    /**
     * @param $url
     * @param $handler
     * @return void
     * ootions
     */
    public function options($url,$handler){
        $method = 'OPTIONS';
        $this->dataGenerator->addRoute($method,$url,$handler);
    }

    /**
     * @param $url
     * @param $handler
     * @return void
     * patch
     */
    public function patch($url,$handler){
        $method = 'PATCH';
        $this->dataGenerator->addRoute($method,$url,$handler);
    }

    /**
     * @param $url
     * @param $handler
     * @return void
     * head
     */
    public function head($url,$handler){
        $method = 'HEAD';
        $this->dataGenerator->addRoute($method,$url,$handler);
    }

    /**
     * @param $url
     * @param $handler
     * @return void
     * trace
     */
    public function trace($url,$handler){
        $method = 'TRACE';
        $this->dataGenerator->addRoute($method,$url,$handler);
    }

    /**
     * @param $url
     * @param $handler
     * @return void
     * connect
     */
    public function connect($url,$handler){
        $method = 'CONNECT';
        $this->dataGenerator->addRoute($method,$url,$handler);
    }

    /**
     * @return mixed
     * 获取数据
     */
    public function getData()
    {
        return $this->dataGenerator->getData();
    }
}