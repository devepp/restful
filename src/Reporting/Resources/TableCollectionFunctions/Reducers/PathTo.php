<?php

namespace App\Reporting\Resources\TableCollectionFunctions\Reducers;

use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Resources\TableCollectionFunctions\Filters\DirectlyRelatedTo;
use App\Reporting\Resources\TableCollectionFunctions\TableReducerInterface;

class PathTo
{

	/**
	 * @param Table $pathFrom
	 * @param Table $pathTo
	 * @param TableCollection $availableTables
	 * @param TableCollection|null $pathSoFar
	 * @return TableCollection|null
	 * @throws \Exception
	 */
	public function __invoke(Table $pathFrom, Table $pathTo, TableCollection $availableTables, TableCollection $pathSoFar = null)
	{
		if ($pathSoFar === null) {
			$pathSoFar = new TableCollection();
		}

		if (!$pathSoFar->hasTable($pathFrom)) {
			$pathSoFar = $pathSoFar->addTable($pathFrom);
		}

		if ($pathFrom->alias() === $pathTo->alias()) {
			return $pathSoFar;
		}

		$relatedTables = $availableTables->filter(new DirectlyRelatedTo($pathFrom));
		$possiblePaths = [];

		foreach ($relatedTables as $relatedTable) {
			if (!$pathSoFar->hasTable($relatedTable)) {
				$pathFinder = new PathTo();
				$path = $pathFinder($relatedTable, $pathTo, $availableTables, $pathSoFar);
				if ($path) {
					$possiblePaths[] = $path;
				}
			}
		}

		$shortestPath = null;
		/** @var TableCollection $possiblePath */
		foreach ($possiblePaths as $possiblePath) {
			if ($shortestPath === null) {
				$shortestPath = $possiblePath;
			} else {
				/** @var TableCollection $shortestPath */
				$shortestPath = ($shortestPath->count() < $possiblePath->count()) ? $shortestPath : $possiblePath;
			}
		}

		return $shortestPath;
	}

}