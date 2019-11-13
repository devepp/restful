<?php

namespace App\Reporting\Selectables;

use App\Reporting\Resources\Table;
use JsonSerializable;
use App\Reporting\DatabaseFields\DatabaseField;

abstract class AbstractSelectable implements JsonSerializable
{
	const ID = 'Abstract';

	const SELECTABLES = [
		Average::ID => Average::class,
		Count::ID => Count::class,
		Custom::ID => Custom::class,
		ListSelectable::ID => ListSelectable::class,
		Max::ID => Max::class,
		Min::ID => Min::class,
		Standard::ID => Standard::class,
		Sum::ID => Sum::class,
	];

	/** @var */
	protected $label_override;

	abstract protected function defaultLabel(DatabaseField $field);

	/**
	 * @param $field
	 * @param string|null $alias
	 * @return string
	 */
	abstract public function selectField($field);
	abstract public function alias($alias);

	public static function getSelectable($id)
	{
		$selectable_array = self::SELECTABLES;
		if (isset($selectable_array[$id])) {
			$selectable_class = $selectable_array[$id];
			return new $selectable_class;
		}
	}

	public static function getSelectables()
	{
		$selectables = [];
		foreach (self::SELECTABLES as $selectable_class) {
			$selectables[] = new $selectable_class;
		}
		return $selectables;
	}

	/**
	 * AbstractSelectable constructor.
	 * @param string $label_override
	 */
	public function __construct($label_override = null)
	{
		$this->label_override = $label_override;
	}

	public function jsonSerialize()
	{
		return [
			'name' => $this->name(),
		];
	}

	public function name()
	{
		return static::ID;
	}

	public function fieldName(Table $table, DatabaseField $field)
	{
		return $field->alias($table->alias());
	}

	public function label(DatabaseField $field)
	{
		if ($this->label_override) {
			return $this->label_override;
		}

		return $this->defaultLabel($field);
	}

}