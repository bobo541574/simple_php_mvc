<?php

return function (\Framework\Routing\Router $router) {
    $router->add('GET', '/', fn () => "Hello World!");

    $router->add('GET', '/product/view/{product}', function () use ($router) {
        $parameters = $router->current()->parameters();

        return "Product is {$parameters['product']}";
    });
};