<?php

namespace App\Reporting;

use App\Reporting\Resources\Table;
use JsonSerializable;
use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Selectables\AbstractSelectable;
use Psr\Http\Message\ServerRequestInterface;

class ReportField implements JsonSerializable, ReportFieldInterface
{
	/** @var Table */
	protected $table;
	/** @var DatabaseField */
	protected $field;
	/** @var string */
	protected $label;
	/** @var AbstractSelectable[] */
	protected $selectables;

	/**
	 * ReportField constructor.
	 * @param Table $table
	 * @param DatabaseField $field
	 * @param AbstractSelectable[] $selectables
	 * @param null $label
	 */
	public function __construct(Table $table, DatabaseField $field, $selectables, $label = null)
	{
		$this->table = $table;
		$this->field = $field;
		$this->selectables = $selectables;
		$this->label = $label === null ? ucwords(str_replace('_', ' ', $this->field->name())) : $label;

//		if ($selectable_overrides === null) {
//			$this->selectables = $field->selectables();
//		} else {
//			$this->selectables = array_uintersect( $selectable_overrides, $field->selectables(), function ($a, $b) {
//				return strcmp(get_class($a), get_class($b));
//			});
//		}
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		return [
			'id' => $this->id(),
			'label' => $this->label(),
			'defaultLabel' => $this->defaultLabel(),
			'groupName' => $this->groupName(),
			'availableModifiers' => $this->selectables,
			'modifier' => $this->type(),
		];
	}

	public function id()
	{
		return $this->table->alias().'__'.$this->field->alias();
	}

	public function defaultLabel()
	{
		return $this->label;
	}

	public function label()
	{
		return $this->label;
	}

	public function groupName()
	{
		return ucwords(str_replace('_', ' ', $this->table->alias()));
	}

	public function name()
	{
		return $this->table->alias().'__'.$this->field->alias();
	}

	public function table()
	{
		return $this->table;
	}

	public function fieldName()
	{
		return $this->field->name();
	}

	public function formatValue($value)
	{
		return $this->field->formatValueAsType($value);
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
		return $this->table->alias();
	}

	public function tableAsCategory()
	{
		return ucwords(str_replace('_', ' ', $this->table->alias()));
	}

	/**
	 * @param Table $table
	 * @return bool
	 */
	public function needsTable(Table $table)
	{
		return $this->table->alias() == $table->alias();
	}

	public function selected(ReportRequest $request)
	{
		foreach ($request->fields() as $selectedField) {
			if ($selectedField['name'] === $this->name()) {
				return true;
			}
		}

		return false;
	}

	public function selectField(ReportRequest $request)
	{
		foreach ($request->fields() as $selectedField) {
			if ($selectedField['name'] === $this->name()) {
				return new SelectedField($this, AbstractSelectable::getSelectable($selectedField['type']), $selectedField['label']);
			}
		}

		throw new \LogicException('field was not selected by request');
	}

	public function requiresTable(Table $table)
	{
		return $table->alias() === $this->table->alias();
	}
}