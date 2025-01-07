<?php
use Plantation\Banana\Router\Collector\RouteCollector;
use Plantation\Banana\Router\DataGenerator;
function dispath($appName,callable $routeCallback,$cache = false){
    $dataGenerator = new DataGenerator\DataGeneratorSimple($appName);
    $object = new RouteCollector($dataGenerator,$cache);
    if($cache!==true){
        $dataGenerator->reset();
        $routeCallback($object);
    }
    $data = $object->getData();
    return new Dispatcher($data);
}