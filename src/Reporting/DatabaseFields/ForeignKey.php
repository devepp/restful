<?php

namespace App\Reporting\DatabaseFields;

use App\Reporting\Filters\Constraints\NotOneOf;
use App\Reporting\Filters\Constraints\OneOf;
use App\Reporting\ReportField;
use App\Reporting\Resources\TableName;
use App\Reporting\Selectables\ListSelectable;

class ForeignKey extends DatabaseField
{
	/** @var TableName */
	private $relationshipAlias;

	/**
	 * ForeignKey constructor.
	 * @param $fieldName
	 * @param TableName $relationshipAlias
	 */
	public function __construct($fieldName, TableName $relationshipAlias)
	{
		parent::__construct($fieldName);
		$this->relationshipAlias = $relationshipAlias;
	}

	public function filterHtml($table_alias_name)
	{
		return '';
	}

	public function url()
	{
		return $this->relationshipAlias->alias();
	}

	/**
	 * @param string $table_alias_name
	 * @param bool $descendant
	 * @return array|ReportField[]
	 */
	public function reportFields($table_alias_name, $descendant)
	{
		return [];
	}

	public function selectableOptions()
	{
		return [];
	}

	public function filterConstraints()
	{
		return [
			new OneOf(),
			new NotOneOf(),
		];
	}

	public function useAsField()
	{
		return false;
	}

	public function useAsFilter()
	{
		return false;
	}

	public function formatParameter($parameter, $prepend = null, $append = null)
	{
		return $parameter;
	}

}