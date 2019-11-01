<?php

namespace App\Reporting\Filters;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\FilterInterface;
use App\Reporting\Filters\Constraints\AbstractConstraint;
use App\Reporting\ReportFilterInterface;
use App\Reporting\Resources\Table;
use App\Reporting\SelectedFilter;
use JsonSerializable;
use Psr\Http\Message\ServerRequestInterface;

class Filter implements JsonSerializable, ReportFilterInterface
{
	/** @var Table */
	protected $table;
	/** @var DatabaseField */
	protected $dbField;

	/** @var string */
	protected $label;

	public function __construct(Table $table, DatabaseField $dbField, $label = null)
	{
		$this->table = $table;
		$this->dbField = $dbField;

		$this->label = $label ? $label : ucwords(str_replace('_', ' ', $this->dbField->name()));
	}

	public function jsonSerialize()
	{
		return [
			'name' => $this->name(),
			'field_name' => $this->dbField->name(),
			'table_alias' => $this->table->alias(),
			'table' => $this->tableAsCategory(),
			'label' => $this->label(),
			'constraints' => $this->constraints(),
			'url' => $this->url(),
		];
	}

	/**
	 * @return string
	 */
	public function name()
	{
		return $this->dbField->alias($this->table->alias());
	}

	/**
	 * @return string
	 */
	public function label()
	{
		return $this->label;
	}


	public function dbField()
	{
		return $this->dbField;
	}

	public function tableAsCategory()
	{
		return ucwords(str_replace('_', ' ', $this->table->alias()));
	}

	/**
	 * @return Constrains[]
	 */
	public function constraints()
	{
		return $this->dbField->filterConstraints();
	}


	public function url()
	{
		return $this->table->alias();
	}

	public function selected(ServerRequestInterface $request)
	{
		$selectedFilters = $request->getAttribute('selected_filters', []);

		foreach ($selectedFilters as $selectedFilter) {
			if ($selectedFilter['name'] === $this->name()) {
				return true;
			}
		}

		return false;
	}

	public function selectFilter(ServerRequestInterface $request)
	{
		$selectedFilters = $request->getAttribute('selected_filters', []);

		foreach ($selectedFilters as $selectedFilter) {
			if ($selectedFilter['name'] === $this->name()) {
				$constraint = AbstractConstraint::getConstraint($selectedFilter['constraint']['name']);
				$inputs = $constraint->inputArrayFromRequestData($selectedFilter['constraint']);

				return new SelectedFilter($this, $constraint, $inputs);
			}
		}

		throw new \LogicException('filter was not selected by request');
	}

}