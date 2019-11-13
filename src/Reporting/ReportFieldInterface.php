<?php

namespace App\Reporting;

use App\Reporting\Resources\Table;
use JsonSerializable;

interface ReportFieldInterface extends JsonSerializable
{

	/**
	 * @return string
	 */
	public function name();

	public function fieldName();

	public function formatValue($value);



	public function id();

	public function label();

	public function defaultLabel();

	public function groupName();

	/**
	 * @param ReportRequest $request
	 * @return bool
	 */
	public function selected(ReportRequest $request);

	/**
	 * @param ReportRequest $request
	 * @return FieldInterface
	 */
	public function selectField(ReportRequest $request);

	/**
	 * @param Table $table
	 * @return bool
	 */
	public function requiresTable(Table $table);
}