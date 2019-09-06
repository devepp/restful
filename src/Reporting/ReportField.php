<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/4/2019
 * Time: 4:09 PM
 */

namespace App\Reporting;

use JsonSerializable;
use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Selectables\AbstractSelectable;

class ReportField implements JsonSerializable
{
	/** @var DatabaseField */
	protected $field;

	/** @var AbstractSelectable[] */
	protected $selectables;

	/**
	 * ReportField constructor.
	 * @param DatabaseField $field
	 * @param null|array $selectable_overrides
	 */
	public function __construct(DatabaseField $field, $selectable_overrides = null)
	{
		$this->field = $field;

		if ($selectable_overrides === null) {
			$this->selectables = $field->selectables();
		} else {
			$this->selectables = array_uintersect( $selectable_overrides, $field->selectables(), function ($a, $b) {
				return strcmp(get_class($a), get_class($b));
			});
		}
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		return [
			'name' => $this->field->alias(),
			'field_name' => $this->field->name(),
			'table_alias' => $this->field->tableAlias(),
			'table' => $this->tableAsCategory(),
			'label' => ucwords(str_replace('_', ' ', $this->field->name())),
			'options' => $this->selectables,
			'type' => $this->type(),
		];
	}

	public function name()
	{
		return $this->field->alias();
	}


	public function dbField()
	{
		return $this->field;
	}

	public function type()
	{
		if (count($this->selectables) === 1) {
			return $this->selectables[0]->name();
		}
	}

	public function tableAlias()
	{
		return $this->field->tableAlias();
	}

	public function tableAsCategory()
	{
		return ucwords(str_replace('_', ' ', $this->field->tableAlias()));
	}


	public function selectableFields()
	{
		$selectable_fields = [];

		foreach ($this->selectables as $selectable) {
			$selectable_fields[] = new SelectableField($selectable->fieldName($this->field), $selectable->label($this->field));
		}

		return $selectable_fields;
	}


	public function fieldHtml($table_alias_name)
	{
		if ($this->selectable()) {
			return '<label><input type="checkbox" name="' . $table_alias_name . '_' . $this->name . '" class="report_field_select">' . $this->label . '</label>';
		}
	}

	public function aggregateFieldHtml($table_alias_name)
	{
		if ($this->selectable()) {
			return '<label><input type="checkbox" name="' . $table_alias_name . '_' . $this->name . '" class="report_field_select">' . $this->label . '</label>';
		}
	}



}