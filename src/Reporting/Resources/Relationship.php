<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 12/28/2018
 * Time: 1:19 PM
 */

namespace App\Reporting\Resources;


class Relationship
{
	/** @var Table */
	protected $parent;
	/** @var Table */
	protected $child;
	/** @var Condition[] */
	protected $join_conditions;

	/**
	 * Relationship constructor.
	 * @param Table $parent
	 * @param Table $child
	 * @param Condition[] $join_conditions
	 */
	public function __construct(Table $parent, Table $child, $join_conditions)
	{
		$this->parent = $parent;
		$this->child = $child;
		$this->join_conditions = $join_conditions;

		$parent->isParentOf($child, $this);
		$child->isChildOf($parent, $this);
	}

	public function joinConditionsSql()
	{
		$condition_strings = [];
		foreach ($this->join_conditions as $condition) {
			$condition_strings[] = $condition->sql();
		}

		$sql = implode(' AND ', $condition_strings).' ';
		return $sql;
	}


}