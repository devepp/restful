<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/7/2019
 * Time: 3:33 PM
 */

namespace App\Reporting;

use JsonSerializable;
use App\Reporting\Filters\Filter;

class Form implements JsonSerializable
{
	/** @var ReportField[] */
	protected $selectable_fields;

	/** @var Filter[] */
	protected $selectable_filters;

	/**
	 * Form constructor.
	 * @param ReportField[] $selectable_fields
	 * @param Filter[] $selectable_filters
	 */
	public function __construct($selectable_fields, $selectable_filters)
	{
		$this->selectable_fields = $selectable_fields;
		$this->selectable_filters = $selectable_filters;
	}

	public function jsonSerialize()
	{
		return [
			'fields' => $this->fields(),
			'filters' => $this->filters(),
		];
	}

	/**
	 * @return ReportField[]
	 */
	public function fields()
	{
		return $this->selectable_fields;
	}

	/**
	 * @return array
	 */
	public function fieldsByCategory()
	{
		$categories = [];
		foreach ($this->fields() as $field) {
			$categories[$field->tableAsCategory()]['name'] = $field->tableAsCategory();
			$categories[$field->tableAsCategory()]['fields'][] = $field;
		}
		return $categories;
	}

	/**
	 * @return Filter[]|null
	 */
	public function filters()
	{
		return $this->selectable_filters;
	}

	/**
	 * @return array
	 */
	public function filtersByCategory()
	{
		$categories = [];
		foreach ($this->filters() as $filter) {
			$categories[$filter->tableAsCategory()]['name'] = $filter->tableAsCategory();
			$categories[$filter->tableAsCategory()]['filters'][] = $filter;
		}
		return $categories;
	}

	/**
	 * @param $name
	 * @return ReportField
	 */
	public function getFieldByName($name)
	{
		foreach ($this->fields() as $field) {
			if ($field->name() == $name) {
				return $field;
			}
		}
	}

	/**
	 * @param $name
	 * @return Filter
	 */
	public function getFilterByName($name)
	{
		foreach ($this->filters() as $filter) {
			if ($filter->name() == $name) {
				return $filter;
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function parseJson($json)
	{
		return $json;
		return json_decode($json);
	}


}