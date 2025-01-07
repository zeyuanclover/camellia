<?php

namespace Vegetation\Camellia\DataGenerator;

use Vegetation\Fern\Cache;
use Vegetation\Fern\Adapter\RedisSingle;

class DataGeneratorSimple implements DataGenerator
{
    /**
     * @param $appName
     * 构造函数
     */
    public function __construct($appName){
        $this->appName = $appName;
    }
    /**
     * @var
     * cache
     */
    public $cache;

    /**
     * @var
     * app name
     */
    private $appName;

    /**
     * @return mixed
     * 获得所有路由数据
     */
    public function getData()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $cache = new Cache(new RedisSingle($_SERVER['container']->get('redis')));
        return $cache->get('CloverRoute-'.$this->appName.'['.$method.']');
    }

    /**
     * @param $method
     * @param $routeData
     * @param $handler
     * @return mixed
     * @throws \Exception
     * 新增路由
     */
    public function addRoute($method, $routeData, $handler)
    {
        if(!isset($_SERVER['container'])){
            throw new \Exception("redis 未载入！");
        }

        $cache = new Cache(new RedisSingle($_SERVER['container']->get('redis')));
        $methodData = $cache->get('CloverRoute-'.$this->appName.'['.$method.']');

        $patterns = $match = $this->matchBraces($routeData);
        $routeUri = $routeData;
        $vals = [];
        if($match){
            foreach ($match as $k=>$value) {
                if(strpos($value,':')!==false){
                    $match[$k] = '('.substr($value,strpos($value,':')+1,-1).')';
                    $vk = substr($value,strpos($value,'{')+1,strpos($value,':')-1);
                    $vals[$vk] = $vk;
                }else{
                    $match[$k] = '([^/]+)';
                    $vk = substr($value,strpos($value,'{')+1,strpos($value,'}')-1);
                    $vals[$vk] = $vk;
                }
            }

            foreach ($patterns as $key=>$val){
                $patterns[$key] = '{'.$val.'}';
                $routeUri = str_replace($val,$match[$key],$routeUri);
            }

            $routeUri = str_replace('.','\\.',$routeUri);

            $routeUri = '~^(?|'.$routeUri.')$~';
        }

        //'~^(?|/admin/article/un_publish\\.html/([^/]+))$~',
        if(!$methodData){
            $methodData[] = [
                'urlPattern' => $routeData,
                'handler' => $handler,
                'regex'=>$match,
                'regegxVal'=>$vals,
                'reg'=>$routeUri,
            ];
        }else{
            $p = [];
            foreach ($methodData as $k=>$methodDataItem) {
                $p[] = $methodDataItem['urlPattern'];
                if($methodDataItem['handler']!=$handler&&$methodDataItem['urlPattern']==$routeData){
                    $methodData[$k]['handler'] = $handler;
                }
            }

            if(!in_array($routeData, $p)){
                $methodData[] = [
                    'urlPattern' => $routeData,
                    'handler' => $handler,
                    'regex'=>$match,
                    'regegxVal'=>$vals,
                    'reg'=>$routeUri,
                ];
            }
        }

        return $cache->set('CloverRoute-'.$this->appName.'['.$method.']', $methodData,0);
    }

    /**
     * @return mixed
     * reset
     */
    public function reset()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $cache = new Cache(new RedisSingle($_SERVER['container']->get('redis')));
        return $cache->set('CloverRoute-'.$this->appName.'['.$method.']', [],0);
    }

    /**
     * @param $text
     * @return string[]
     * 大括号内容匹配
     */
    function matchBraces($text) {
        $pattern = '/\{(((?>[^{}]+)|(?R))*)\}/';
        preg_match_all($pattern, $text, $matches);
        return $matches[0];
    }
}