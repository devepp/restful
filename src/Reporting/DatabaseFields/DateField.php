<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 12/28/2018
 * Time: 2:29 PM
 */

namespace App\Reporting\DatabaseFields;

use App\Reporting\Filters\Constraints\Between;
use App\Reporting\Filters\Constraints\Equals;
use App\Reporting\Filters\Constraints\GreaterThan;
use App\Reporting\Filters\Constraints\LessThan;
use App\Reporting\Selectables\ListSelectable;
use App\Reporting\Selectables\Max;
use App\Reporting\Selectables\Min;
use DateTime;

class DateField extends DatabaseField
{

	public function selectableOptions()
	{
		return [
			new Min(),
			new Max(),
			new ListSelectable(),
		];
	}

	public function filterConstraints()
	{
		return [
			new Equals(),
			new GreaterThan(),
			new LessThan(),
			new Between(),
		];
	}

	public function formatParameter($parameter, $prepend = null, $append = null)
	{
		$date = new DateTime($parameter);
		return '"'.$date->format('Y-m-d').$append.'"';
	}

	public function formatValueAsType($value)
	{
		$date = new \DateTimeImmutable($value);
		return $date->format('M j, Y');
	}


}