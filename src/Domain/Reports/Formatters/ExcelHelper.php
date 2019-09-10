<?php

namespace App\Domain\Reports\Formatters;

use eBase\Support\PHPExcel\Helper;

class ExcelHelper extends Helper
{
	// --------------------------------------------------------------------

	public function setFormats($formats)
	{
		if (is_array($formats)) {
			return $this->formats = $formats;
		}
	}

	// --------------------------------------------------------------------
}