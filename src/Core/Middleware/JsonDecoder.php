<?php

namespace App\Core\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JsonDecoder implements MiddlewareInterface
{
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface{
 	// TODO: Implement process() method.

		if ($request->getHeader('Content-Type') != 'application/json') {
			return $handler->handle($request);
		}

		$data = json_decode($request->getBody(), true);

		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new BadRequestHttpException('invalid json body: ' . json_last_error_msg());
		}
		$json_request = $request->withParsedBody(is_array($data) ? $data : []);

		return $handler->handle($json_request);
	}
}