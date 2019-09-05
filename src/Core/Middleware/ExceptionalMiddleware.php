<?php

namespace App\Core\Middleware;

use App\Core\Exceptions\HTTP\NotFoundException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ExceptionalMiddleware implements MiddlewareInterface
{
	/** @var ResponseFactoryInterface */
	private $responseFactory;

	/**
	 * ExceptionalMiddleware constructor.
	 * @param ResponseFactoryInterface $responseFactory
	 */
	public function __construct(ResponseFactoryInterface $responseFactory)
	{
		$this->responseFactory = $responseFactory;
	}


	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		try {
			return $handler->handle($request);
		} catch(NotFoundException $exception) {
			return $this->responseFactory->createResponse(404, $exception->getMessage());
		}
	}

}