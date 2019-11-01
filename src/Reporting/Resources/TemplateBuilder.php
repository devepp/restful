<?php


namespace App\Reporting\Resources;


use App\Reporting\Filters\Filter;
use App\Reporting\ReportField;
use App\Reporting\ReportFieldInterface;
use App\Reporting\ReportFilterInterface;

class TemplateBuilder
{
	private $resource;

	private $reportFields;

	private $reportFilters;

	private $defaultFields;

	private $defaultFilters;

	/**
	 * TemplateBuilder constructor.
	 * @param $resource
	 */
	public function __construct($resource)
	{
		$this->resource = $resource;
	}

	public function build()
	{
		return new ReportTemplate($this->resource, $this->reportFields, $this->reportFilters, $this->defaultFields, $this->defaultFilters);
	}

	public function withReportField(ReportFieldInterface $field)
	{
		$clone  = clone $this;
		$clone->reportFields[$field->name()] = $field;
		return $clone;
	}

	public function withReportFilter(ReportFilterInterface $filter)
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


}