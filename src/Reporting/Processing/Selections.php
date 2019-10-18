<?php

namespace App\Reporting\Processing;

use App\Reporting\Filters\Constraints\AbstractConstraint;
use App\Reporting\Form;
use App\Reporting\Resources\Limit;
use App\Reporting\Selectables\AbstractSelectable;
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
	 * @param $input
	 * @param Form $form
	 * @return Selections
	 */
	public static function FromInput($input, Form $form)
	{
		$fields = self::getFields($input, $form);
		$filters = self::getFilters($input, $form);
		$limit = self::getLimit($input);

		return new self($fields, $filters, $limit);
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

	/**
	 * @param $input
	 * @param Form $form
	 * @return SelectedFilter[]
	 */
	private static function getFilters($input, Form $form)
	{
		$selectedFilters = [];

		foreach ($input['selected_filters'] as $filter) {
			$selectable_filter = $form->getFilterByName($filter['name']);
			if ($selectable_filter) {
				$inputs = [];
				if (isset($filter['constraint']['input_1'])) {
					$inputs[] = $filter['constraint']['input_1'];
				}
				if (isset($filter['constraint']['input_2'])) {
					$inputs[] = $filter['constraint']['input_2'];
				}

				$selectedFilters[] = new SelectedFilter($selectable_filter->dbField(), AbstractConstraint::getConstraint($filter['constraint']['name']), $inputs);
			}
		}
		return $selectedFilters;
	}

	/**
	 * @param $input
	 * @param Form $form
	 * @return SelectedField[]
	 */
	private static function getFields($input, Form $form)
	{
		$selectedFields = [];

		foreach ($input['selected_fields'] as $field) {
			$selectable_field = $form->getFieldByName($field['name']);
			if ($selectable_field) {
				$selectedFields[] = new SelectedField($selectable_field->dbField(), AbstractSelectable::getSelectable($field['type']));
			}
		}
		return $selectedFields;
	}

	/**
	 * @param $input
	 * @return Limit
	 */
	private static function getLimit($input)
	{
		$limit = isset($input['limit']) ? $input['limit'] : 10;
		$offset = isset($input['offset']) ? $input['offset'] : 0;
		return new Limit($limit, $offset);
	}


}