<?php

use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use duncan3dc\Cache\FilesystemPool;
use App\Repositories\AssetRepositoryInterface;
use App\Repositories\CachedAssetRepository;

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
    AssetRepositoryInterface::class => function (ContainerInterface $container) {
        return $container->get(CachedAssetRepository::class);
    },

];