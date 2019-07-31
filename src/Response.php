<?php


namespace App;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
	protected $statusCode;
	protected $reasonPhrase;
	protected $headers;
	protected $body;
	protected $protocolVersion;

	public function withStatus($code, $reasonPhrase = '')
	{
		$this->statusCode = $code;
		$this->reasonPhrase = $reasonPhrase;
	}

	public function getReasonPhrase()
	{
		return $this->reasonPhrase;
	}

	public function getProtocolVersion()
	{
		return $this->protocolVersion;
	}

	public function withProtocolVersion($version)
	{
		$this->protocolVersion = $version;
	}

	public function getHeaders()
	{
		return $this->headers;
	}

	public function getStatusCode()
	{
		return $this->statusCode;
	}

	public function hasHeader($name)
	{
		return array_key_exists($name, $this->headers);
	}

	public function getHeader($name)
	{
		return $this->hasHeader($name) ? $this->headers[$name] : null;
	}

	public function getHeaderLine($name)
	{
		// TODO: Implement getHeaderLine() method.
	}

	public function withHeader($name, $value)
	{
		$this->headers[$name] = $value;
	}

	public function withAddedHeader($name, $value)
	{
		$this->withHeader($name, $value);
	}

	public function withoutHeader($name)
	{
		unset($this->headers[$name]);
	}

	public function getBody()
	{
		return $this->body;
	}

	public function withBody(StreamInterface $body)
	{
		$this->body = $body;
	}
}