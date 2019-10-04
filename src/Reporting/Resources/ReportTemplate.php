<?php


namespace App\Reporting\Resources;


class ReportTemplate
{
	private $resource;

	private $reportFields;

	private $reportFilters;

	private $defaultFields;

	private $defaultFilters;

	/**
	 * ReportTemplate constructor.
	 * @param $resource
	 * @param $reportFields
	 * @param $reportFilters
	 * @param $defaultFields
	 * @param $defaultFilters
	 */
	public function __construct($resource, $reportFields, $reportFilters, $defaultFields, $defaultFilters)
	{
		$this->resource = $resource;
		$this->reportFields = $reportFields;
		$this->reportFilters = $reportFilters;
		$this->defaultFields = $defaultFields;
		$this->defaultFilters = $defaultFilters;
	}

	public static function builder($resource)
	{
		return new TemplateBuilder($resource);
	}

	/**
	 * @return mixed
	 */
	public function getResource()
	{
		return $this->resource;
	}

	/**
	 * @return mixed
	 */
	public function getReportFields()
	{
		return $this->reportFields;
	}

	/**
	 * @return mixed
	 */
	public function getReportFilters()
	{
		return $this->reportFilters;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultFields()
	{
		return $this->defaultFields;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultFilters()
	{
		return $this->defaultFilters;
	}



}