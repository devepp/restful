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

	public function next($fetchStyle = null)
	{
		if (isset($fetchStyle)) {
			return $this->statement->fetch($fetchStyle);
		}

		return $this->statement->fetch();
	}

	public function iterator($fetchStyle)
	{
		while ($array = $this->statement->fetch($fetchStyle)) {
			yield $array;
		}
	}

	public function all($fetchStyle = null, $fetchArgument = null, $constructorArgs = null)
	{
		if (isset($constructorArgs) && isset($fetchArgument) && isset($fetchStyle)) {
			return $this->statement->fetchAll($fetchStyle, $fetchArgument, $constructorArgs);
		}

		if (isset($fetchArgument) && isset($fetchStyle)) {
			return $this->statement->fetchAll($fetchStyle, $fetchArgument);
		}

		if (isset($fetchStyle)) {
			return $this->statement->fetchAll($fetchStyle);
		}

		return $this->statement->fetchAll();
	}

	public function nextAsAssociative()
	{
		return $this->statement->fetch(PDO::FETCH_ASSOC);
	}

	public function asAssociativeArrayIterator()
	{
		while ($array = $this->statement->fetch(PDO::FETCH_ASSOC)) {
			yield $array;
		}
	}

	public function nextAsObject()
	{
		return $this->statement->fetch(PDO::FETCH_OBJ);
	}

	public function asObjectIterator()
	{
		while ($object = $this->statement->fetch(PDO::FETCH_OBJ)) {
			yield $object;
		}
	}

	public function nextAsClass($className)
	{
		return $this->statement->fetch(PDO::FETCH_CLASS, $className);
	}

	public function asClassIterator($className)
	{
		while ($object = $this->statement->fetch(PDO::FETCH_CLASS, $className)) {
			yield $object;
		}
	}

	public function asMappedIterator(callable $map)
	{
		while ($array = $this->statement->fetch(PDO::FETCH_ASSOC)) {
			yield $map($array);
		}
	}
}