<?php


namespace App\Core\Middleware;


use App\Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
	protected $factory;

	/**
	 * AuthMiddleware constructor.
	 * @param ResponseFactoryInterface $factory
	 */
	public function __construct(ResponseFactoryInterface $factory)
	{
		$this->factory = $factory;
	}


	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$authentication = $request->getHeader('Authentication');
		$authentication = explode(' ', $authentication);
		$token = $authentication[1];

		if ($this->checkAuthentication($token)) {
			return $handler->handle($request);
		} else {
			return $this->factory->createResponse(418, "You can't touch this");
		}
	}

	// --------------------------------------------------------------------

	private function checkAuthentication($token)
	{
		return ($token === "kjsfduihsgduihfgduihsagd78yuihs");
	}
}