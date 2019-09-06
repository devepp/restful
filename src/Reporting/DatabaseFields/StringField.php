<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 12/28/2018
 * Time: 2:29 PM
 */

namespace App\Reporting\DatabaseFields;

use App\Reporting\Filters\Constraints\Contains;
use App\Reporting\Filters\Constraints\EndsWith;
use App\Reporting\Filters\Constraints\Equals;
use App\Reporting\Filters\Constraints\NotContains;
use App\Reporting\Filters\Constraints\NotEqual;
use App\Reporting\Filters\Constraints\StartsWith;
use App\Reporting\ReportField;
use App\Reporting\Selectables\ListSelectable;

class StringField extends DatabaseField
{

	public function aggregateFieldHtml($table_alias_name)
	{
		if ($this->selectable()) {
			return '<label><input type="checkbox" name="' . $table_alias_name . '_' . $this->name . '" class="report_field_select">' . $this->label . '</label>';
		}
	}

	public function filterHtml($table_alias_name)
	{
		return '';
	}

	/**
	 * @param string $table_alias_name
	 * @param bool $descendant
	 * @return array|ReportField[]
	 */
	public function reportFields($table_alias_name, $descendant)
	{
		if (!$descendant) {
			return [
				new ReportField($table_alias_name.'_'.$this->name, $this->label),
			];
		}

		return [
			new ReportField($table_alias_name.'_'.$this->name.'_list', $this->label.' (List)'),
		];
	}

	public function selectableOptions()
	{
		return [
			new ListSelectable(),
		];
	}

	public function filterConstraints()
	{
		return [
			new Equals(),
			new StartsWith(),
			new EndsWith(),
			new Contains(),
			new NotEqual(),
			new NotContains(),
		];
	}

	public function formatParameter($parameter, $prepend = null, $append = null)
	{
		return '"'.$prepend.$parameter.$append.'"';
	}
}