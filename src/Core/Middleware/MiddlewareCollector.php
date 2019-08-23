<?php

namespace App\Core\Middleware;

use Psr\Http\Server\MiddlewareInterface;

class MiddlewareCollector
{
    private $middlewares = [];

    public function add($middleware)
    {
        $class = new \ReflectionClass($middleware);
        if (! $class->implementsInterface(MiddlewareInterface::class)) {
            throw new \InvalidArgumentException('Middleware Collector expects all middleware to implement `Psr\Http\Server\MiddlewareInterface`. '.$middleware.' does not.');
        }

        $this->middlewares[] = $middleware;
    }

    public function addCollection(MiddlewareCollection $middlewareCollection)
    {
        foreach ($middlewareCollection->toArray() as $middleware) {
            $this->middlewares[] = $middleware;
        }
    }

    public function collection()
    {
        return new MiddlewareCollection($this->middlewares);
    }
}