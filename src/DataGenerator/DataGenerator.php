<?php

namespace Vegetation\Camellia\DataGenerator;

interface DataGenerator
{
    public function addRoute($method,$routeData,$handler);

    public function getData();
}