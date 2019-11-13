<?php


namespace App\Reporting;


use Psr\Http\Message\ServerRequestInterface;

class ReportRequest
{
	private $requestedFields;

	private $requestedFilters;

	private $requestedGrouping;

	private $requestedSorting;

	private $requestedLimit;

	private $requestedOffset;

	/**
	 * ReportRequest constructor.
	 * @param $requestedFields
	 * @param $requestedFilters
	 * @param $requestedLimit
	 * @param $requestedGrouping
	 * @param $requestedSorting
	 */
	public function __construct($requestedFields, $requestedFilters, $requestedGrouping, $requestedSorting, $requestedLimit, $requestedOffset)
	{
		$this->requestedFields = $requestedFields;
		$this->requestedFilters = $requestedFilters;
		$this->requestedLimit = $requestedLimit;
		$this->requestedGrouping = $requestedGrouping;
		$this->requestedSorting = $requestedSorting;
	}

	/**
	 * @param ServerRequestInterface $request
	 * @return ReportRequest
	 */
	public static function fromRequest(ServerRequestInterface $request)
	{
		$fields = $request->getAttribute('selected_fields', []);
		$filters = $request->getAttribute('selected_filters', []);
		$grouping = $request->getAttribute('grouping', []);
		$sorting = $request->getAttribute('sort', []);

		$limit = $request->getAttribute('limit', null);
		if ($limit) {
			$offset = $request->getAttribute('offset', null);
		} else {
			$limit = $request->getAttribute('perPage', null);
			$page = $request->getAttribute('page', 1);
			$offset = $limit && $page ? $limit-1 * $page : null;
		}

		return new self($fields, $filters, $grouping, $sorting, $limit, $offset);
	}

	/**
	 * @return array
	 */
	public function fields()
	{
		return $this->requestedFields;
	}

	/**
	 * @return array
	 */
	public function filters()
	{
		return $this->requestedFilters;
	}

	/**
	 * @return array
	 */
	public function groupings()
	{
		return $this->requestedGrouping;
	}

	/**
	 * @return array
	 */
	public function sort()
	{
		return $this->requestedGrouping;
	}

	/**
	 * @return string
	 */
	public function limit()
	{
		return $this->requestedLimit;
	}

	/**
	 * @return string
	 */
	public function offset()
	{
		return $this->requestedOffset;
	}
}