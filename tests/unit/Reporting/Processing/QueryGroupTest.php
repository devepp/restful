<?php

namespace Tests\Unit\Reporting\Processing;

use App\Reporting\DB\Connection;
use App\Reporting\DB\QueryBuilder\QueryBuilderFactory;
use App\Reporting\DB\QueryBuilder\Select;
use App\Reporting\Processing\QueryGroup;
use App\Reporting\Processing\Selections;
use App\Reporting\Resources\Limit;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Selectables\Standard;
use App\Reporting\SelectedField;
use App\Reporting\SelectionsInterface;
use PHPUnit\Framework\TestCase;

class QueryGroupTest extends TestCase
{
	/**
	 * @dataProvider provider
	 *
	 * @param Table $table
	 * @param TableCollection $tables
	 * @param SelectionsInterface $selections
	 * @param $expectedQueryStatement	 *
	 */
	public function testGetQuery(Table $table, TableCollection $tables, SelectionsInterface $selections, $expectedQueryStatement)
	{
		$group = new QueryGroup($table, $tables, []);

		$query = $group->getQuery(new QueryBuilderFactory(), $selections);

		$this->assertSame($expectedQueryStatement, $query->getStatementExpression());
	}

	public function provider()
	{
		return [
			[$this->getTable('assets'), $this->getTableCollection(), $this->getSelections(), 'SELECT `assetsAlias`.`name` FROM assets assetsAlias LEFT JOIN elements elementsAlias ON  LEFT JOIN recommendations recommendationsAlias ON '],
		];
	}

	/**
	 * @param $name
	 * @param Table[] $relatedTables
	 * @return Table
	 */
	private function getTable($name, $relatedTables = [])
	{
		$tableBuilder = Table::builder($name)
			->setAlias($name.'Alias')
			->addStringField('name');

		foreach ($relatedTables as $relatedTable) {
//			$tableBuilder->addForeignKey($relatedTable->name().'_id');
		}

		return $tableBuilder->build();
	}

	private function getTableCollection()
	{
		$tables = new TableCollection([
			$this->getTable('assets'),
			$this->getTable('elements', [$this->getTable('assets')]),
			$this->getTable('recommendations', [$this->getTable('elements')]),
		]);

		return $tables;
	}

	private function getSelections()
	{
		$table = $this->getTable('assets');
		$selections = new Selections([new SelectedField($table, $table->dbField('name'), new Standard())],[], new Limit(5, 0));

		return $selections;
	}
}