<?php

namespace Tests\Doubles;

use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableName;

trait GetTable
{

	/**
	 * @param $name
	 * @param Table[] $relatedTables
	 * @return Table
	 */
	protected function getTable($name, $relatedTables = [])
	{
		$tableBuilder = Table::builder($name)
			->setAlias($name.'Alias')
			->setPrimaryKey('id')
			->addStringField('name')
			->addNumberField('cost')
			->addBooleanField('is_true');

		foreach ($relatedTables as $relationship => $relatedTable) {
			$relatedTable = $this->getTableName($relatedTable);

			if ($relationship === 'manyToOne') {
				$tableBuilder = $tableBuilder->addManyToOneRelationship($relatedTable, $name.'Alias.'.$relatedTable->name() . '_id = ' . $relatedTable->alias() . '.id', $relatedTable->name() . '_id');
			}
			if ($relationship === 'oneToMany') {
				$tableBuilder = $tableBuilder->addOneToManyRelationship($relatedTable, $name.'Alias.'.$relatedTable->name() . '_id = ' . $relatedTable->alias() . '.id');
			}
			if ($relationship === 'oneToOne') {
				$tableBuilder = $tableBuilder->addOneToOneRelationship($relatedTable, $name.'Alias.'.$relatedTable->name() . '_id = ' . $relatedTable->alias() . '.id');
			}
		}

		return $tableBuilder->build();
	}

	protected function getTableName($name)
	{
		return new TableName($name, $name.'Alias');
	}
}