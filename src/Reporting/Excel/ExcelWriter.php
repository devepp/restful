<?php

namespace App\Reporting\Excel;


interface ExcelWriter
{
	public function addRow(Row $row, Style $style);

	public function createSheet($name);
}