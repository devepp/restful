<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Filters;

use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollectionFunctions\TableFilterInterface;

class FilterCombo implements TableFilterInterface
{
	/** @var TableFilterInterface[] */
	private $filters;

	/**
	 * FilterCombo constructor.
	 * @param TableFilterInterface ...$filters
	 */
	public function __construct(...$filters)
	{
		if (count($filters) === 1 && \is_array($filters[0])) {
			$this->filters = $filters[0];
		} else {
			$this->filters = $filters;
		}
	}

	public function __invoke(Table $table)
	{
		foreach ($this->filters as $filter) {
			if ($filter($table) === false) {
				return false;
			}
		}

		return true;
	}
}