<?php

namespace App\Reporting\Resources;

use App\Reporting\Filters\Filter;
use App\Reporting\Processing\QueryGroup;
use App\Reporting\ReportField;

class Resource
{
	/** @var QueryGroup */
	private $queryGroup;

	/** @var ReportField[] */
	private $fields;

	/** @var Filter[] */
	private $filters;

	/**
	 * Resource constructor.
	 * @param QueryGroup $queryGroup
	 * @param ReportField[] $fields
	 * @param Filter[] $filters
	 */
	public function __construct(QueryGroup $queryGroup, array $fields, array $filters)
	{
		$this->queryGroup = $queryGroup;
		$this->fields = $fields;
		$this->filters = $filters;
	}

	public static function builder(Schema $schema, Table $baseTable)
	{
		return new ResourceBuilder($schema, $baseTable);
	}

	/**
	 * @return QueryGroup
	 */
	public function getQueryGroup()
	{
		return $this->queryGroup;
	}
}