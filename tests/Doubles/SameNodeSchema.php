<?php

namespace Tests\Doubles;

use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;

class SameNodeSchema extends Schema
{
	private $getTableCount = 0;

	private $table1;

	private $table2;

	public function __construct(Table $table1, Table $table2)
	{
		$this->table1 = $table1;
		$this->table2 = $table2;
	}

	public function getRelationshipPath($tableAlias, $otherTableAlias)
	{
		return ['',''];
	}

	public function getTable($tableAlias)
	{
		if($this->getTableCount === 0) {
			$this->getTableCount++;
//			echo 'getting FIRST table'.\PHP_EOL;
			return $this->table1;
		}
//		echo 'getting SECOND table'.\PHP_EOL;
		return $this->table2;
	}
}