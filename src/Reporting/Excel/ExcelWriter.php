<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 7/2/2019
 * Time: 4:49 PM
 */

namespace App\Reporting\Excel;


interface ExcelWriter
{
	public function addRow(Row $row, Style $style);

	public function createSheet($name);
}