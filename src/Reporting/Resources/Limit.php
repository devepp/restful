<?php

namespace App\Reporting\Resources;

use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use Psr\Http\Message\ServerRequestInterface;

class Limit
{
	/** @var int */
	private $numberOfRecords;

	/** @var int */
	private $offset;

	/**
	 * Limit constructor.
	 * @param int $numberOfRecords
	 * @param int $offset
	 */
	public function __construct($numberOfRecords, $offset)
	{
		$this->numberOfRecords = $numberOfRecords;
		$this->offset = $offset;
	}

	/**
	 * @return Limit
	 */
	public static function defaultLimit()
	{
		return new self(20, 0);
	}

	/**
	 * @param ServerRequestInterface $request
	 * @return Limit
	 */
	public static function fromRequestOrDefault(ServerRequestInterface $request)
	{
		$limit = $request->getAttribute('perPage');

		if ($limit) {
			$page = $request->getAttribute('page', 1);
			$offset = ($page - 1) * $limit;

			return new self($limit, $offset);
		}

		return self::defaultLimit();
	}

	/**
	 * @return int
	 */
	public function numberOfRecords()
	{
		return $this->numberOfRecords;
	}

	/**
	 * @return int
	 */
	public function offset()
	{
		return $this->offset;
	}

	/**
	 * @return int
	 */
	public function sql()
	{
		return 'LIMIT '.$this->offset().','.$this->numberOfRecords();
	}

	/**
	 * @param SelectQueryBuilderInterface $queryBuilder
	 * @return SelectQueryBuilderInterface
	 */
	public function appendToQuery(SelectQueryBuilderInterface $queryBuilder)
	{
		return $queryBuilder->limit($this->numberOfRecords, $this->offset);
	}

}