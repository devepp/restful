<?php

namespace App\Reporting;

use App\Reporting\Resources\Table;
use JsonSerializable;
use Psr\Http\Message\ServerRequestInterface;

interface ReportFieldInterface extends JsonSerializable
{

	/**
	 * @return string
	 */
	public function name();

	/**
	 * @param ServerRequestInterface $request
	 * @return bool
	 */
	public function selected(ServerRequestInterface $request);

	/**
	 * @param ServerRequestInterface $request
	 * @return FieldInterface
	 */
	public function selectField(ServerRequestInterface $request);

	/**
	 * @param Table $table
	 * @return bool
	 */
	public function requiresTable(Table $table);
}