<?php
namespace Vegetation\Camellia;
use Vegetation\Camellia\Collector\RouteCollector;
use Vegetation\Camellia\DataGenerator\DataGeneratorSimple;

new RouteCollector($appName);
exit;
function dispath($appName,callable $routeCallback,$cache = false){
    $dataGenerator = new DataGeneratorSimple($appName);
    $object = new RouteCollector($dataGenerator,$cache);
    if($cache!==true){
        $dataGenerator->reset();
        $routeCallback($object);
    }
    $data = $object->getData();
    return new Dispatcher($data);
}