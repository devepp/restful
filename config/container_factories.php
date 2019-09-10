<?php

use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use duncan3dc\Cache\FilesystemPool;

$factories = [
    CacheInterface::class => function (ContainerInterface $container) {
        return $container->get(FilesystemPool::class);
    },
    FilesystemPool::class => function (ContainerInterface $container) {
        return new FilesystemPool($container->get('tempDirectory'));
    },
    'tempDirectory' => function (ContainerInterface $container) {
        return sys_get_temp_dir();
    },

];