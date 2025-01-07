<?php

namespace Plantation\Banana\Router\DataGenerator;

interface DataGenerator
{
    public function addRoute($method,$routeData,$handler);

    public function getData();
}