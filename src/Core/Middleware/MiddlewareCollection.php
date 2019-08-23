<?php

namespace App\Core\Middleware;


use Psr\Http\Server\MiddlewareInterface;

class MiddlewareCollection
{
    private $middlewares = [];

    /**
     * MiddlewareCollection constructor.
     * @param array $middlewares
     */
    public function __construct(array $middlewares)
    {
        array_map([$this, 'addMiddleware'], $middlewares);
    }

    public function toArray()
    {
        return $this->middlewares;
    }

    private function addMiddleware($middleware)
    {
        $class = new \ReflectionClass($middleware);
        if (! $class->implementsInterface(MiddlewareInterface::class)) {
            throw new \InvalidArgumentException('Middleware Collection expects are middleware to implement `Psr\Http\Server\MiddlewareInterface`. '.$middleware.' does not.');
        }

        $this->middlewares[] = $middleware;
    }
}