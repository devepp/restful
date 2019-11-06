<?php

namespace App\Reporting\Resources;

use App\Reporting\Processing\Selections;
use App\Reporting\ReportFieldInterface;
use App\Reporting\ReportFilterInterface;
use Psr\Http\Message\ServerRequestInterface;

class Resource implements ResourceInterface
{
	/** @var Table */
	private $table;

	/** @var string */
	private $name;

	/** @var ReportFieldInterface[] */
	private $fields;

	/** @var ReportFilterInterface[] */
	private $filters;

	/**
	 * Resource constructor.
	 * @param Table $table
	 * @param string $name
	 * @param array $fields
	 * @param array $filters
	 */
	public function __construct(Table $table, string $name, array $fields, array $filters)
	{
		$this->table = $table;
		$this->name = $name;
		$this->fields = $fields;
		$this->filters = $filters;
	}

	public static function builder(Table $baseTable, string $name)
	{
		return new ResourceBuilder($baseTable, $name);
	}

	public static function builderFromResource(ResourceInterface $resource)
	{
		$resourceBuilder = new ResourceBuilder($resource->table(), $resource->name());
		foreach ($resource->availableFields() as $field) {
			$resourceBuilder->addReportField($field);
		}
		foreach ($resource->availableFilters() as $filter) {
			$resourceBuilder->addReportFilter($filter);
		}

		return $resourceBuilder;
	}

	public function table()
	{
		return $this->table;
	}

	/**
	 * @inheritdoc
	 */
	public function name()
	{
		return $this->name;
	}

	/**
	 * @inheritdoc
	 */
	public function availableFields()
	{
		return $this->fields;
	}

	/**
	 * @inheritdoc
	 */
	public function availableFilters()
	{
		return $this->filters;
	}
}