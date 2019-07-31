<?php

use App\Core\Container\Alpha;
use App\Core\Container\Container;
use Psr\Container\ContainerInterface;

require_once dirname(__DIR__) . '/vendor/autoload.php';

// echo 'You are a success!';

$entries = [
    Alpha::class => function () {
        return new Alpha();
    }
];

$c = new Container($entries);

$alpha = $c->get(Alpha::class);
echo $alpha->name;