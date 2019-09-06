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
use App\Reporting\ReportField;
use App\Reporting\Selectables\Average;
use App\Reporting\Selectables\ListSelectable;
use App\Reporting\Selectables\Max;
use App\Reporting\Selectables\Min;
use App\Reporting\Selectables\Sum;

class NumberField extends DatabaseField
{

	public function filterHtml($table_alias_name)
	{
		return '';
	}

	public function reportFields($table_alias_name, $descendant)
	{
		if (!$descendant) {
			return [
				new ReportField($table_alias_name.'_'.$this->name, $this->label),
			];
		}

		return [
			new ReportField($table_alias_name.'_'.$this->name.'_sum', $this->label.' (Sum)'),
			new ReportField($table_alias_name.'_'.$this->name.'_avg', $this->label.' (Avg)'),
			new ReportField($table_alias_name.'_'.$this->name.'_max', $this->label.' (Max)'),
			new ReportField($table_alias_name.'_'.$this->name.'_min', $this->label.' (Min)'),
			new ReportField($table_alias_name.'_'.$this->name.'_list', $this->label.' (List)'),
		];
	}

	public function selectableOptions()
	{
		return [
			new Sum(),
			new Average(),
			new Max(),
			new Min(),
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
		return $append.$parameter.$prepend;
	}

	public function formatValueAsType($value)
	{
		if (is_numeric($value)) {
			return $value + 0;
		}
		return 0;
	}


}