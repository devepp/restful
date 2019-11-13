<?php

namespace App\Reporting\Resources;

use App\Reporting\Processing\Selections;
use App\Reporting\ReportFieldCollection;
use App\Reporting\ReportFieldInterface;
use App\Reporting\ReportFilterCollection;
use App\Reporting\ReportFilterInterface;
use Psr\Http\Message\ServerRequestInterface;

class Resource implements ResourceInterface
{
	/** @var Table */
	private $table;

	/** @var string */
	private $name;

	/** @var ReportFieldCollection */
	private $fields;

	/** @var ReportFilterCollection */
	private $filters;

	/**
	 * Resource constructor.
	 * @param Table $table
	 * @param string $name
	 * @param ReportFieldCollection $fields
	 * @param ReportFilterCollection $filters
	 */
	public function __construct(Table $table, $name, ReportFieldCollection $fields, ReportFilterCollection $filters)
	{
		$this->table = $table;
		$this->name = $name;
		$this->fields = $fields;
		$this->filters = $filters;
	}

	public static function builder(Table $baseTable, $name)
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