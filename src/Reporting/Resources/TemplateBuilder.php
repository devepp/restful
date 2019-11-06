<?php

namespace App\Reporting\Resources;

use App\Reporting\Filters\Filter;
use App\Reporting\ReportField;
use App\Reporting\ReportFieldInterface;
use App\Reporting\ReportFilterInterface;

class TemplateBuilder
{
	/** @var Schema */
	private $schema;
	/** @var ResourceInterface */
	private $resource;

	/** @var ResourceInterface[] */
	private $resources;

	private $reportFields;

	private $reportFilters;

	private $defaultFields;

	private $defaultFilters;

	/**
	 * TemplateBuilder constructor.
	 * @param Schema $schema
	 * @param ResourceInterface $baseResource
	 */
	public function __construct(Schema $schema, ResourceInterface $baseResource)
	{
		$this->schema = $schema;
		$this->resource = $baseResource;
	}

	public function build()
	{
		return new ReportTemplate($this->schema, $this->resource, $this->resources);
	}

	public function withReportField(ReportFieldInterface $field, $resourceName)
	{
		$clone  = clone $this;
		$clone->reportFields[$field->name()] = $field;
		return $clone;
	}

	public function withReportFilter(ReportFilterInterface $filter, $resourceName)
	{
		$clone  = clone $this;
		$clone->reportFilters[$filter->name()] = $filter;
		return $clone;
	}

	public function withDefaultField(ReportField $field)
	{
		$clone  = clone $this;
		$clone->defaultFields[$field->name()] = $field;
		return $clone;
	}

	public function withDefaultFilter(Filter $filter)
	{
		$clone  = clone $this;
		$clone->defaultFilters[$filter->name()] = $filter;
		return $clone;
	}

	public function withResource(ResourceInterface $resource)
	{
		$clone  = clone $this;
		$clone->resources[] = $resource;
		return $clone;
	}


}