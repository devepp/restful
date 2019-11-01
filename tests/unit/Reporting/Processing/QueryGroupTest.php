<?php

namespace Tests\Unit\Reporting\Processing;

use App\Reporting\DB\QueryBuilder\QueryBuilderFactory;
use App\Reporting\Processing\QueryGroup;
use App\Reporting\Processing\Selections;
use App\Reporting\ReportField;
use App\Reporting\Resources\Limit;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Resources\TableCollectionFunctions\Filters\Filter;
use App\Reporting\Selectables\Average;
use App\Reporting\Selectables\Max;
use App\Reporting\Selectables\Standard;
use App\Reporting\Selectables\Sum;
use App\Reporting\SelectedField;
use App\Reporting\SelectionsInterface;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\GetTable;

class QueryGroupTest extends TestCase
{
	use GetTable;

	/**
	 * @dataProvider provider
	 *
	 * @param QueryGroup $group
	 * @param SelectionsInterface $selections
	 * @param $expectedQueryStatement
	 */
	public function testGetQuery(QueryGroup $group, SelectionsInterface $selections, $expectedQueryStatement)
	{
		$query = $group->getQuery(new QueryBuilderFactory(), $selections);

		$this->assertSame($expectedQueryStatement, $query->getStatementExpression());
	}

	public function provider()
	{
		return [
			[$this->getNonNestedQueryGroup(), $this->getStandardSelections(), 'SELECT `assetsAlias`.`name` assetsAlias__name, `elementsAlias`.`name` elementsAlias__name, `recommendationsAlias`.`name` recommendationsAlias__name FROM recommendations recommendationsAlias LEFT JOIN elements elementsAlias ON elementsAlias.recommendations_id = recommendationsAlias.id LEFT JOIN assets assetsAlias ON assetsAlias.elements_id = elementsAlias.id'],
			[$this->getNestedQueryGroup(), $this->getAggregateSelections(), 'SELECT `assetsAlias`.`name` assetsAlias__name, elementsAlias_aggregate.elementsAlias__cost__sum, recommendationsAlias_aggregate.recommendationsAlias__cost__average FROM assets assetsAlias LEFT JOIN (SELECT IFNULL(SUM(`elementsAlias`.`cost`), 0) elementsAlias__cost__sum, `assetsAlias`.`id` assetsAlias__id FROM assets assetsAlias LEFT JOIN elements elementsAlias ON elementsAlias.assets_id = assetsAlias.id) elementsAlias_aggregate ON elementsAlias_aggregate.assetsAlias__id = assetsAlias.id LEFT JOIN (SELECT AVG(`recommendationsAlias`.`cost`) recommendationsAlias__cost__average, `assetsAlias`.`id` assetsAlias__id FROM assets assetsAlias LEFT JOIN elements elementsAlias ON elementsAlias.assets_id = assetsAlias.id LEFT JOIN recommendations recommendationsAlias ON recommendationsAlias.elements_id = elementsAlias.id) recommendationsAlias_aggregate ON recommendationsAlias_aggregate.assetsAlias__id = assetsAlias.id'],
		];
	}

	private function getTableCollection()
	{
		$tables = [
			$this->getTable('assets', ['oneToMany' => 'elements']),
			$this->getTable('elements', ['manyToOne' => 'assets', 'oneToMany' => 'recommendations']),
			$this->getTable('recommendations', ['manyToOne' => 'elements']),
		];

		$tableCollection = new TableCollection($tables);

		return $tableCollection;
	}

	private function getNonNestedQueryGroup()
	{
		$tables = $this->getTableCollection();

		$nodeTables = [
			$tables->getTable('assetsAlias'),
			$tables->getTable('elementsAlias'),
			$tables->getTable('recommendationsAlias'),
		];

		$pathTables = [
			$tables->getTable('recommendationsAlias'),
		];

		return new QueryGroup(
			$tables->getTable('recommendationsAlias'),
			TableCollection::fromArray($nodeTables),
			TableCollection::fromArray($pathTables)
		);
	}

	private function getStandardSelections()
	{
		$assetsTable = $this->getTable('assets');
		$elementsTable = $this->getTable('elements');
		$recommendationsTable = $this->getTable('recommendations');
		$selections = new Selections(
			[
				new SelectedField(
					new ReportField(
						$assetsTable,
						$assetsTable->dbField('name'),
						'name'
					),
					new Standard(),
					'Name'
				),
				new SelectedField(
					new ReportField(
						$elementsTable,
						$elementsTable->dbField('name'),
						'elementName'
					),
					new Standard(),
					'Element Name'
				),
				new SelectedField(
					new ReportField(
						$recommendationsTable,
						$recommendationsTable->dbField('name'),
						'recommendationName'
					),
					new Standard(),
					'Recommendation Name'
				)
			],
			[],
			new Limit(5, 0)
		);

		return $selections;
	}



	private function getNestedQueryGroup()
	{
		$tables = $this->getTableCollection();

		return new QueryGroup(
			$tables->getTable('assetsAlias'),
			$tables->filter(Filter::byAliases(['assetsAlias'])),
			$tables->filter(Filter::byAliases(['assetsAlias'])),
			[
				new QueryGroup(
					$tables->getTable('elementsAlias'),
					$tables->filter(Filter::byAliases(['elementsAlias'])),
					$tables->filter(Filter::byAliases(['assetsAlias', 'elementsAlias']))
				),
				new QueryGroup(
					$tables->getTable('recommendationsAlias'),
					$tables->filter(Filter::byAliases(['recommendationsAlias'])),
					$tables->filter(Filter::byAliases(['assetsAlias', 'elementsAlias', 'recommendationsAlias']))
				)
			]
		);
	}

	private function getAggregateSelections()
	{
		$assetsTable = $this->getTable('assets');
		$elementsTable = $this->getTable('elements');
		$recommendationsTable = $this->getTable('recommendations');
		$selections = new Selections(
			[
				new SelectedField(
					new ReportField(
						$assetsTable,
						$assetsTable->dbField('name'),
						'name'
					),
					new Standard(),
					'Name'
				),
				new SelectedField(
					new ReportField(
						$elementsTable,
						$elementsTable->dbField('cost'),
						'cost'
					),
					new Sum(),
					'Total Element Cost'
				),
				new SelectedField(
					new ReportField(
						$recommendationsTable,
						$recommendationsTable->dbField('cost'),
						'recommendationCost'
					),
					new Average(),
					'Average Recommendation Cost'
				)
			],
			[],
			new Limit(5, 0)
		);

		return $selections;
	}
}