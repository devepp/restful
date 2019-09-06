<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 12/28/2018
 * Time: 2:29 PM
 */

namespace App\Reporting\DatabaseFields;

use App\Reporting\ReportField;
use App\Reporting\Selectables\ListSelectable;

class PrimaryKey extends DatabaseField
{

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
		return [];
	}

	public function selectableOptions()
	{
		return [];
	}

	public function filterConstraints()
	{
		return [];
	}

	public function useAsField()
	{
		return false;
	}

	public function useAsFilter()
	{
		return false;
	}

	public function formatParameter($parameter, $prepend = null, $append = null)
	{
		return $parameter;
	}


}