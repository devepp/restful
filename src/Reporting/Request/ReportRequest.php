<?php

namespace App\Reporting\Request;

use App\Reporting\Common\Values;
use Psr\Http\Message\ServerRequestInterface;

class ReportRequest
{
	/** @var RequestedFields */
	private $fields;

	/** @var RequestedFilters */
	private $filters;

	/** @var Groupings */
	private $groupings;

	/** @var Sorts */
	private $sorts;

	/** @var Limit|null */
	private $limit;

	/**
	 * ReportRequest constructor.
	 * @param RequestedFields $fields
	 * @param RequestedFilters $filters
	 * @param Groupings $groupings
	 * @param Sorts $sorts
	 * @param Limit|null $limit
	 */
	public function __construct(RequestedFields $fields, RequestedFilters $filters, Groupings $groupings, Sorts $sorts, Limit $limit = null)
	{
		$this->fields = $fields;
		$this->filters = $filters;
		$this->groupings = $groupings;
		$this->sorts = $sorts;
		$this->limit = $limit;
	}

	public static function fromRequest(ServerRequestInterface $request)
	{
		$fields = RequestedFields::fromRequestDataArray($request->getAttribute('selected_fields', []));
		$filters = RequestedFilters::fromRequestDataArray($request->getAttribute('selected_filters', []));
		$grouping = Groupings::fromRequestDataArray($request->getAttribute('groupings', []));
		$sorting = Sorts::fromRequestDataArray($request->getAttribute('sorts', []));
		$limit = Limit::fromRequest($request);

		return new self($fields, $filters, $grouping, $sorting, $limit);
	}

	public static function fromInputArray($requestData)
	{
		$data = Values::fromArray($requestData);

		$fields = RequestedFields::fromRequestDataArray($data->value('selected_fields', []));
		$filters = RequestedFilters::fromRequestDataArray($data->value('selected_filters', []));
		$grouping = Groupings::fromRequestDataArray($data->value('groupings', []));
		$sorting = Sorts::fromRequestDataArray($data->value('sorts', []));
		$limit = Limit::fromRequestDataArray($requestData);

		return new self($fields, $filters, $grouping, $sorting, $limit);
	}

	/**
	 * @return RequestedFields
	 */
	public function fields()
	{
		return $this->fields;
	}

	/**
	 * @return RequestedFilters
	 */
	public function filters()
	{
		return $this->filters;
	}

	/**
	 * @return Groupings
	 */
	public function groupings()
	{
		return $this->groupings;
	}

	/**
	 * @return Sorts
	 */
	public function sorts()
	{
		return $this->sorts;
	}

	/**
	 * @return Limit|null
	 */
	public function limit()
	{
		return $this->limit;
	}
}