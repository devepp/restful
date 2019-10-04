<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/15/2019
 * Time: 1:56 PM
 */

namespace App\Reporting\Resources;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Filters\Constraints\AbstractConstraint;
use App\Reporting\Filters\Constraints\Equals;

class Condition
{
	/** @var DatabaseField */
	protected $db_field;

	/** @var AbstractConstraint */
	protected $constraint;

	/** @var mixed[] */
	protected $constraint_parameters;

	public static function equals(DatabaseField $dbField, $constraintParameters)
	{
		return new self($dbField, new Equals(), $constraintParameters);
	}

	/**
	 * Condition constructor.
	 * @param DatabaseField $db_field
	 * @param AbstractConstraint $constraint
	 * @param $constraint_parameters
	 */
	public function __construct(DatabaseField $db_field, AbstractConstraint $constraint, $constraint_parameters)
	{
		$this->db_field = $db_field;
		$this->constraint = $constraint;
		$this->constraint_parameters = $constraint_parameters;
	}


	public function sql()
	{
		return $this->constraint->filterSql($this->db_field, $this->constraint_parameters);
	}


}