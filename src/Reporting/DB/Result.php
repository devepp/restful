<?php

namespace App\Reporting\DB;

use PDOStatement;
use PDO;

class Result
{
	/** @var PDOStatement */
	private $statement;

	/**
	 * Result constructor.
	 * @param PDOStatement $statement
	 */
	public function __construct(PDOStatement $statement)
	{
		$this->statement = $statement;
	}

	public function next($fetchStyle)
	{
		return $this->statement->fetch($fetchStyle);
	}

	public function iterator($fetchStyle)
	{
		while ($array = $this->statement->fetch($fetchStyle)) {
			yield $array;
		}
	}

	public function all($fetchStyle, $fetchArgument = null, $constructorArgs = [])
	{
		return $this->statement->fetchAll($fetchStyle, $fetchArgument, $constructorArgs);
	}

	public function nextAsAssociative()
	{
		return $this->statement->fetch(PDO::FETCH_ASSOC);
	}

	public function iteratorOfAssociativeArrays()
	{
		while ($array = $this->statement->fetch(PDO::FETCH_ASSOC)) {
			yield $array;
		}
	}

	public function nextAsObject()
	{
		return $this->statement->fetch(PDO::FETCH_OBJ);
	}

	public function iteratorOfObjects()
	{
		while ($object = $this->statement->fetch(PDO::FETCH_OBJ)) {
			yield $object;
		}
	}

	public function nextAsClass($className)
	{
		return $this->statement->fetch(PDO::FETCH_CLASS, $className);
	}

	public function iteratorOfClassObjects($className)
	{
		while ($object = $this->statement->fetch(PDO::FETCH_CLASS, $className)) {
			yield $object;
		}
	}
}