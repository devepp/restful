<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 4:04 PM
 */

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;



class LessThan extends AbstractConstraint
{
	const NAME = 'LessThan';


	public function filterSql(DatabaseField $db_field, $inputs = [])
	{
		return '`'.$db_field->tableAlias().'`.`'.$db_field->name().'` < '.$db_field->formatParameter($inputs[0]);
	}

	public function requiredInputs()
	{
		return 1;
	}

	public function directive()
	{
		return 'eb-report-filter-single-text';
	}

}