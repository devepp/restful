<?php

namespace App\Reporting\Processing;

use App\Reporting\ReportFieldInterface;
use App\Reporting\ReportFilterInterface;
use App\Reporting\Request\Limit;
use App\Reporting\Request\ReportRequest;
use App\Reporting\SelectedField;
use App\Reporting\SelectedFilter;
use App\Reporting\SelectionsInterface;

class Selections implements SelectionsInterface
{
	/** @var SelectedField[] */
	protected $selectedFields;
	/** @var SelectedFilter[] */
	protected $selectedFilters;
	/** @var Limit */
	protected $limit;

	/**
	 * Selections constructor.
	 * @param SelectedField[] $selectedFields
	 * @param SelectedFilter[] $selectedFilters
	 * @param Limit $limit
	 */
	public function __construct(array $selectedFields, array $selectedFilters, Limit $limit)
	{
		$this->selectedFields = $selectedFields;
		$this->selectedFilters = $selectedFilters;
		$this->limit = $limit;
	}

	/**
	 * @param ReportRequest $request
	 * @param ReportFieldInterface[] $availableFields
	 * @param ReportFilterInterface[] $availableFilters
	 * @return Selections
	 */
	public static function fromRequest(ReportRequest $request, $availableFields, $availableFilters)
	{
		$selectedFields = [];
		foreach ($availableFields as $field) {
			if($field->selected($request)) {
				$selectedFields[] = $field->selectField($request);
			}
		}

		$selectedFilters = [];
		foreach ($availableFilters as $filter) {
			if($filter->selected($request)) {
				$selectedFilters[] = $filter->selectFilter($request);
			}
		}

		$limit = new Limit($request->limit(), $request->offset());

		return new self($selectedFields, $selectedFilters, $limit);
	}

	public static function getMatchingField($fieldName, $reportFields)
	{
		/** @var ReportFieldInterface $reportField */
		foreach ($reportFields as $reportField) {
			if ($reportField->name() == $fieldName) {
				return $reportField;
			}
		}
	}

	/**
	 * @return SelectedField[]
	 */
	public function selectedFields()
	{
		return $this->selectedFields;
	}

	/**
	 * @return SelectedFilter[]
	 */
	public function selectedFilters()
	{
		return $this->selectedFilters;
	}

	/**
	 * @return Limit
	 */
	public function limit()
	{
		return $this->limit;
	}
}