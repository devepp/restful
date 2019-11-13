<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 3/26/2019
 * Time: 1:28 PM
 */

namespace App\Reporting;


class TabularData implements \JsonSerializable
{
	/** @var SelectedFieldCollection */
	protected $columns;

	/** @var [] */
	protected $data;

	/**
	 * TabularData constructor.
	 * @param $columns
	 * @param $data
	 */
	public function __construct($columns, $data)
	{
		$this->columns = $columns;
		$this->data = $data;
	}

	public function data()
	{
		return $this->data;
	}

	public function rowValues()
	{
		foreach ($this->data as $row) {
			$row_array = (array)$row;
			$row_values = [];
			foreach ($this->columns as $reportField) {
				$value = $row_array[$reportField->fieldAlias(false)];
				$row_values[] = $reportField->formatValueAsType($value);
			}
			yield $row_values;
		}
	}

	public function columns()
	{
		return $this->columns;
	}

	public function headerTextAll()
	{
		$headers = [];
		foreach ($this->columns as $reportField) {
			$headers[] = $reportField->title();
		}
		return $headers;
	}

	public function jsonSerialize()
	{
		return [
			'column_headers' => $this->columns,
			'data' => $this->data,
		];
	}


	private function isAssoc($arr)
	{
		if (array() === $arr) return false;
		return array_keys($arr) !== range(0, count($arr) - 1);
	}


}