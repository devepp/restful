<?php

namespace App\Reporting\Filters\Constraints;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use JsonSerializable;
use App\Reporting\Filters\Constrains;

abstract class AbstractConstraint implements Constrains
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

	/**
	 * @inheritdoc
	 */
	abstract public function filterSql(SelectQueryBuilderInterface $queryBuilder,  $field, $inputs = []);

	/**
	 * @return string
	 */
	abstract public function directive();	// return string - name of directive to use

	/**
	 * @return int
	 */
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

	public function inputArrayFromRequestData($constraintData)
	{
		$inputs = [];

		for ($i = 0; $i < $this->requiredInputs(); $i++) {
			$inputs[] = $constraintData['input_'.($i+1)];
		}

		return $inputs;
	}
}