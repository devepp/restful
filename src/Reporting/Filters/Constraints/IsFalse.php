<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 4:01 PM
 */

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;



class IsFalse extends AbstractConstraint
{
	const NAME = 'Is False';


	public function filterSql(DatabaseField $db_field, $inputs = [])
	{
		return '`'.$db_field->tableAlias().'`.`'.$db_field->name().'` = 0';
	}

	public function requiredInputs()
	{
		return 0;
	}

	public function directive()
	{
		return null;
	}

}