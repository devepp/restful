<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Reducers;

use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Resources\TableCollectionFunctions\TableFilterInterface;
use App\Reporting\Resources\TableCollectionFunctions\TableMapInterface;
use App\Reporting\Resources\TableCollectionFunctions\TableReducerInterface;

/**
 * This is effectively a type of transducer
 * Class FilterAndMap
 * @package App\Reporting\Resources\TableCollectionFunctions\Reducers
 */
class FilterAndMap implements TableReducerInterface
{
	private $callbacks;

	public function __construct(...$callbacks)
	{
		if (count($callbacks) === 1 && \is_array($callbacks[0])) {
			$this->callbacks = $callbacks[0];
		} else {
			$this->callbacks = $callbacks;
		}
	}

	public function __invoke(TableCollection $collectedTables, Table $table)
	{
		foreach ($this->callbacks as $callback) {
			if ($callback instanceof TableFilterInterface && !$callback($table)) {
				return $collectedTables;
			}
			if ($callback instanceof TableMapInterface) {
				$table = $callback($table);
			}
		}

		$collectedTables->addTable($table);

		return $collectedTables;
	}
}