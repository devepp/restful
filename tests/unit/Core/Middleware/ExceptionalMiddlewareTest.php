<?php

use App\Core\Exceptions\HTTP\NotFoundException;
use PHPUnit\Framework\TestCase;
use App\Core\Middleware\ExceptionalMiddleware;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ExceptionalMiddlewareTest extends TestCase
{
	public function testCatchNotFoundException()
	{
		$responseFactory = $this->createMock(ResponseFactoryInterface::class);
		$response = $this->createMock(ResponseInterface::class);
		$responseFactory->method('createResponse')->willReturn($response);

		$middleware	= new ExceptionalMiddleware($responseFactory);

		$request = $this->createMock(ServerRequestInterface::class);
		$handler = $this->createMock(RequestHandlerInterface::class);
		$handler->method('handle')->willThrowException(new NotFoundException());

		$middleware->process($request, $handler);
		$this->assertTrue(true);
	}
}