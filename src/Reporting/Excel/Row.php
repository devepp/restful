<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 7/2/2019
 * Time: 5:03 PM
 */

namespace App\Reporting\Excel;


class Row
{
	private $rowData;

	public static function fromArray($data = [])
	{
		return new static($data);
	}

	protected function __construct($data = [])
	{
		$this->rowData = $data;
	}
}