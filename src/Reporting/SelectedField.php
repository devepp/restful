<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 10:34 AM
 */

namespace App\Reporting;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Processing\QueryGroup;
use App\Reporting\Selectables\AbstractSelectable;
use JsonSerializable;

class SelectedField implements JsonSerializable
{
	/** @var DatabaseField */
	protected $field;

	/** @var string */
//	protected $label;

	/** @var AbstractSelectable */
	protected $selectable;

	/**
	 * SelectableField constructor.
	 * @param DatabaseField $db_field
	 * @param AbstractSelectable $selectable
	 */
	public function __construct(DatabaseField $db_field, AbstractSelectable $selectable)
	{
		$this->field = $db_field;
		$this->selectable = $selectable;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		return [
			'name' => $this->name(),
			'alias' => $this->fieldAlias(false),
			'title' => $this->title(),
			'label' => $this->label(),
		];
	}

	/**
	 * @return string
	 */
	public function name()
	{
		return $this->field->name();
	}

	/**
	 * @return string
	 */
	public function label()
	{
		return $this->field->alias();
	}

	/**
	 * @return string
	 */
	public function title()
	{
		return $this->field->title();
	}

	public function formatValueAsType($value)
	{
		return $this->field->formatValueAsType($value);
	}


	/**
	 * @return string
	 */
	public function table()
	{
		return $this->field->tableAlias();
	}


	/**
	 * @param QueryGroup $subQueryGroup
	 * @return string
	 */
	public function fieldSql($subQueryGroup)
	{
		return $this->selectable->fieldSql($this->field, $subQueryGroup);
	}


	/**
	 * @param QueryGroup $subQueryGroup
	 * @return string
	 */
	public function fieldAlias($subQueryGroup)
	{
		return $this->selectable->fieldAlias($this->field, $subQueryGroup);
	}


}