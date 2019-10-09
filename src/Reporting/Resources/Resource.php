<?php

namespace App\Reporting\Resources;

use App\Reporting\Filters\Filter;
use App\Reporting\Processing\QueryGroup;
use App\Reporting\ReportField;

class Resource
{
	/** @var QueryGroup[] */
	private $queryGroups;

	/** @var ReportField[] */
	private $fields;

	/** @var Filter[] */
	private $filters;

	/**
	 * Resource constructor.
	 * @param QueryGroup[] $queryGroups
	 * @param ReportField[] $fields
	 * @param Filter[] $filters
	 */
	public function __construct(array $queryGroups, array $fields, array $filters)
	{
		$this->queryGroups = $queryGroups;
		$this->fields = $fields;
		$this->filters = $filters;
	}

	public static function builder(Schema $schema, Table $baseTable)
	{
		return new ResourceBuilder($schema, $baseTable);
	}

	/**
	 * @return QueryGroup[]
	 */
	public function getQueryGroups(): array
	{
		return $this->queryGroups;
	}
}