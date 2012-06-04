<?php

$container = new Pimple;

foreach (array('form', 'validator', 'templating') as $service) {
    require_once __DIR__.'/providers/'.$service.'.php';
}

return $container;
