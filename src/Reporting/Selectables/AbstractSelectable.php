<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 9:52 AM
 */

namespace App\Reporting\Selectables;

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


	/** @var string */
	protected $label_override;

	abstract protected function defaultLabel(DatabaseField $field);
	abstract public function fieldSql(DatabaseField $field, $subQueryGroup);
	abstract public function fieldAlias(DatabaseField $field, $subQueryGroup);

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

	public function fieldName(DatabaseField $field)
	{
		return $field->alias();
	}

	public function label(DatabaseField $field)
	{
		if ($this->label_override) {
			return $this->label_override;
		}

		return $this->defaultLabel($field);
	}

}