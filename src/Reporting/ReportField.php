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
	 * @param null $label
	 * @param null $selectable_overrides
	 */
	public function __construct(Table $table, DatabaseField $field, $label = null, $selectable_overrides = null)
	{
		$this->table = $table;
		$this->field = $field;
		$this->label = $label === null ? ucwords(str_replace('_', ' ', $this->field->name())) : $label;

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
			'table_alias' => $this->table->alias(),
			'table' => $this->tableAsCategory(),
			'label' => $this->label(),
			'options' => $this->selectables,
			'type' => $this->type(),
		];
	}

	public function name()
	{
		return $this->field->alias();
	}

	public function table()
	{
		return $this->table;
	}

	public function label()
	{
		return $this->label;
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
		return ucwords(str_replace('_', ' ', $this->field->tableAlias()));
	}

	/**
	 * @param Table $table
	 * @return bool
	 */
	public function needsTable(Table $table)
	{
		return $this->table->alias() == $table->alias();
	}

	public function selected(ServerRequestInterface $request)
	{
		$selectedFields = $request->getAttribute('selected_fields', []);

		foreach ($selectedFields as $selectedField) {
			if ($selectedField['name'] === $this->name()) {
				return true;
			}
		}

		return false;
	}

	public function selectField(ServerRequestInterface $request)
	{
		$selectedFields = $request->getAttribute('selected_fields', []);

		foreach ($selectedFields as $selectedField) {
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