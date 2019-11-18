<?php

namespace App\Reporting\Request;

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

	public static function defaultLimit()
	{
		return new self(20, null);
	}

	public static function fromRequestDataArray($requestLimitData)
	{
		$limit = null;
		if (isset($requestFilterData['limit'])) {
			$limit = $requestLimitData['limit'];
		}
		if (isset($requestFilterData['perPage'])) {
			$limit = $requestLimitData['perPage'];
		}

		if ($limit) {
			$offset = null;
			if (isset($requestFilterData['offset'])) {
				$offset = $requestLimitData['offset'];
			}
			if (isset($requestLimitData['page'])) {
				$page = $requestLimitData['page'];
				$offset = ($page - 1) * $limit;
			}

			return new self($limit, $offset);
		}
	}

	public static function fromRequestDataArrayOrDefault($requestLimitData)
	{
		$limit = self::fromRequestDataArray($requestLimitData);

		if ($limit) {
			return $limit;
		}

		return self::defaultLimit();
	}

	public static function fromRequest(ServerRequestInterface $request)
	{
		$limit = $request->getAttribute('perPage');

		if ($limit) {
			$page = $request->getAttribute('page', 1);
			$offset = ($page - 1) * $limit;

			return new self($limit, $offset);
		}
	}

	public static function fromRequestOrDefault(ServerRequestInterface $request)
	{
		$limit = self::fromRequest($request);

		if ($limit) {
			return $limit;
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