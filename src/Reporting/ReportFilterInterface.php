<?php

namespace App\Reporting;

use JsonSerializable;
use Psr\Http\Message\ServerRequestInterface;

interface ReportFilterInterface extends JsonSerializable
{
	/**
	 * @param ServerRequestInterface $request
	 * @return bool
	 */
	public function selected(ServerRequestInterface $request);

	/**
	 * @param ServerRequestInterface $request
	 * @return FilterInterface
	 */
	public function selectFilter(ServerRequestInterface $request);
}