<?php

namespace App\Reporting\Processing;

use App\Reporting\Filters\Constraints\AbstractConstraint;
use App\Reporting\Form;
use App\Reporting\Resources\Limit;
use App\Reporting\Resources\ReportTemplateInterface;
use App\Reporting\Selectables\AbstractSelectable;
use App\Reporting\SelectedField;
use App\Reporting\SelectedFilter;
use App\Reporting\SelectionsInterface;
use Psr\Http\Message\ServerRequestInterface;

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
	 * @param ServerRequestInterface $request
	 * @param ReportTemplateInterface $reportTemplate
	 * @return Selections
	 */
	public static function fromRequest(ServerRequestInterface $request, ReportTemplateInterface $reportTemplate)
	{
		$selectedFields = [];
		foreach ($reportTemplate->availableFields() as $field) {
			if ($field->selected($request)) {
				$selectedFields[] = $field->selectField($request);
			}
		}

		$selectedFilters = [];
		foreach ($reportTemplate->availableFilters() as $filter) {
			if($filter->selected($request)) {
				$selectedFilters[] = $filter->selectFilter($request);
			}
		}

		$limit = Limit::fromRequestOrDefault($request);

		return new self($selectedFields, $selectedFilters, $limit);
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