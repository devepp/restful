<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 12/24/2018
 * Time: 9:52 AM
 */

namespace App\Reporting\Filters;

use App\Reporting\DatabaseFields\DatabaseField;
use JsonSerializable;

class Filter implements JsonSerializable
{
	/** @var DatabaseField */
	protected $db_field;

	/** @var string */
	protected $label;

	/**
	 * Filter constructor.
	 * @param DatabaseField $db_field
	 * @param string $label
	 */
	public function __construct(DatabaseField $db_field, $label = null)
	{
		$this->db_field = $db_field;

		$this->label = $label ? $label : ucwords(str_replace('_', ' ', $this->db_field->name()));
	}

	public function jsonSerialize()
	{
		return [
			'name' => $this->name(),
			'field_name' => $this->db_field->name(),
			'table_alias' => $this->db_field->tableAlias(),
			'table' => $this->tableAsCategory(),
			'label' => $this->label(),
			'constraints' => $this->constraints(),
			'url' => $this->url(),
		];
	}

	/**
	 * @return string
	 */
	public function name()
	{
		return $this->db_field->alias();
	}

	/**
	 * @return string
	 */
	public function label()
	{
		return $this->label;
	}


	public function dbField()
	{
		return $this->db_field;
	}

	public function tableAsCategory()
	{
		return ucwords(str_replace('_', ' ', $this->db_field->tableAlias()));
	}

	/**
	 * @return Constrains[]
	 */
	public function constraints()
	{
		return $this->db_field->filterConstraints();
	}


	public function url()
	{
		return $this->db_field->tableAlias();
	}


}