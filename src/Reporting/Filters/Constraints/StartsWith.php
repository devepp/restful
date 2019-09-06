<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 4:02 PM
 */

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;


class StartsWith extends AbstractConstraint
{
	const NAME = 'StartsWith';


	public function filterSql(DatabaseField $db_field, $inputs = [])
	{
		return '`'.$db_field->tableAlias().'`.`'.$db_field->name().'` LIKE '.$db_field->formatParameter($inputs[0], null, '%');
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