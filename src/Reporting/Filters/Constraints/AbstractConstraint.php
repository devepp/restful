<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/9/2019
 * Time: 8:23 AM
 */

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;
use JsonSerializable;
use App\Reporting\Filters\Constrains;

abstract class AbstractConstraint implements Constrains, JsonSerializable
{
	const NAME = 'Abstract';

	const CONSTRAINTS = [
		Between::NAME => Between::class,
		Contains::NAME => Contains::class,
		EndsWith::NAME => EndsWith::class,
		Equals::NAME => Equals::class,
		GreaterThan::NAME => GreaterThan::class,
		LessThan::NAME => LessThan::class,
		NotBetween::NAME => NotBetween::class,
		NotContains::NAME => NotContains::class,
		NotEqual::NAME => NotEqual::class,
		NotOneOf::NAME => NotOneOf::class,
		OneOf::NAME => OneOf::class,
		StartsWith::NAME => StartsWith::class,
	];

	abstract public function filterSql(DatabaseField $db_field, $inputs = []);
	abstract public function directive();	// return string - name of directive to use
	abstract public function requiredInputs();	//return int - number of inputs required by constraint

	/**
	 * @param $name
	 * @return AbstractConstraint
	 */
	public static function getConstraint($name)
	{
		$constraint_array = self::CONSTRAINTS;
		if (isset($constraint_array[$name])) {
			$constraint_class = $constraint_array[$name];
			return new $constraint_class;
		}
	}

	public static function getConstraints()
	{
		$constraints = [];
		foreach (self::CONSTRAINTS as $constraint_class) {
			$constraints[] = new $constraint_class;
		}
		return $constraints;
	}

	public function jsonSerialize()
	{
		return [
			'name' => $this->name(),
			'directive' => $this->directive(),
			'required_inputs' => $this->requiredInputs(),
		];
	}

	public function name()
	{
		return static::NAME;
	}
}