<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 4:05 PM
 */

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;

class Between extends AbstractConstraint
{
	const NAME = 'Between';


	public function filterSql(DatabaseField $db_field, $inputs = [])
	{
		return '`'.$db_field->tableAlias().'`.`'.$db_field->name().'` BETWEEN '.$db_field->formatParameter($inputs[0]).' AND '.$db_field->formatParameter($inputs[1]);
	}

	public function requiredInputs()
	{
		return 2;
	}

	public function directive()
	{
		return 'eb-report-filter-double-text';
	}


}