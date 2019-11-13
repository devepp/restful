<?php

namespace App\Reporting\DatabaseFields;

use App\Reporting\ReportField;
use App\Reporting\Resources\Table;
use App\Reporting\Selectables\Standard;

abstract class DatabaseField
{
	/** @var string */
	protected $name;

	abstract public function selectableOptions();
	abstract public function filterConstraints();
	abstract public function formatParameter($parameter, $prepend = null, $append = null);

	/**
	 * DatabaseField constructor.
	 * @param string $name
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}

	public function toString($tableAlias)
	{
		return '`'.$tableAlias.'`.`'.$this->name().'`';
	}

	public function name()
	{
		return $this->name;
	}

	public function alias()
	{
		return $this->name();
	}

	public function url()
	{
		return null;
	}

	public function title($tableAlias)
	{
		return ucwords(str_replace('_', ' ',$tableAlias.' '.$this->name()));
	}

	public function selectables()
	{

		return $this->selectableOptions();

		return [new Standard()];
	}

	public function useAsField()
	{
		return true;
	}

	public function useAsFilter()
	{
		return true;
	}

	public function constraints(Table $table)
	{
		return [new Standard()];
	}

	public function formatValueAsType($value)
	{
		return $value;
	}

}