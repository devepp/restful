<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/17/2019
 * Time: 9:57 AM
 */

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
	protected $selected_fields;
	/** @var SelectedFilter[] */
	protected $selected_filters;
	/** @var Limit */
	protected $limit;

	/**
	 * Selections constructor.
	 * @param $input
	 * @param Form $form
	 */
	public function __construct($input, Form $form)
	{
		$this->selected_fields = $this->getFields($input, $form);
		$this->selected_filters = $this->getFilters($input, $form);
		$this->limit = $this->getLimit($input);
	}


	/**
	 * @return SelectedField[]
	 */
	public function selectedFields()
	{
		return $this->selected_fields;
	}

	/**
	 * @return SelectedFilter[]
	 */
	public function selectedFilters()
	{
		return $this->selected_filters;
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
	protected function getFilters($input, Form $form)
	{
		$selected_filters = [];

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

				$selected_filters[] = new SelectedFilter($selectable_filter->dbField(), AbstractConstraint::getConstraint($filter['constraint']['name']), $inputs);
			}
		}
		return $selected_filters;
	}

	/**
	 * @param $input
	 * @param Form $form
	 * @return SelectedField[]
	 */
	protected function getFields($input, Form $form)
	{
		$selected_fields = [];

		foreach ($input['selected_fields'] as $field) {
			$selectable_field = $form->getFieldByName($field['name']);
			if ($selectable_field) {
				$selected_fields[] = new SelectedField($selectable_field->dbField(), AbstractSelectable::getSelectable($field['type']));
			}
		}
		return $selected_fields;
	}

	/**
	 * @param $input
	 * @return Limit
	 */
	protected function getLimit($input)
	{
		$limit = isset($input['limit']) ? $input['limit'] : 10;
		$offset = isset($input['offset']) ? $input['offset'] : 0;
		return new Limit($limit, $offset);
	}


}