<?php

namespace Tests\Functional;

use App\Reporting\Resources\ReportTemplate;
use App\Reporting\Resources\Resource;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\GetSchema;

class PathTest extends TestCase
{
	use GetSchema;

	/**
	 * @dataProvider pathProvider
	 *
	 * @param $firstTableAlias
	 * @param $secondTableAlias
	 * @param $expectedPath
	 */
	public function testPath($firstTableAlias, $secondTableAlias, $expectedPath)
	{
		$schema = $this->getSchema();

		$path = $schema->getRelationshipPath($firstTableAlias, $secondTableAlias);

		$this->assertEquals($expectedPath, $path);
	}

	public function pathProvider()
	{
		return [
			['work_orders', 'invoices', ['work_orders', 'jobs', 'invoices']],
			['facilities', 'invoice_types', ['facilities', 'work_orders', 'jobs', 'invoices', 'invoice_types']],
			['authors', 'work_orders', ['authors', 'work_orders']],
			['work_orders', 'dispatchers', ['work_orders', 'dispatchers']],
			['jobs', 'dispatchers', ['jobs', 'work_orders', 'dispatchers']],
			['work_orders', 'jobs', ['work_orders', 'jobs']],
			['invoices', 'invoice_types', ['invoices', 'invoice_types']],
			['facilities', 'work_orders', ['facilities', 'work_orders']],
		];
	}

	public function testReport()
	{
		$reportTemplate = $this->getReportTemplate();


//		$reportTemplate->getQuery()

//		\var_dump(\json_encode($reportTemplate->nestedFields()));
//		\var_dump(\json_encode($reportTemplate->nestedFilters()));
		$this->assertTrue(true);
	}

	private function getReportTemplate()
	{
		$woResource = $this->getResource('work_orders', 'Work Orders');
		$facilityResource = $this->getResource('facilities', 'Facilities');
		$jobResource = $this->getResource('jobs', 'Facilities');
		$dispatchers = $this->getResource('dispatchers', 'Dispatchers');

		$templateBuilder = ReportTemplate::builder($this->getSchema(), $woResource)
			->withResource($facilityResource)
			->withResource($jobResource)
			->withResource($dispatchers);

		return $templateBuilder->build();
	}

	private function getResource($tableAlias, $resourceName, $addFields = true, $addFilters = true)
	{
		$schema = $this->getSchema();
		$table = $schema->getTable($tableAlias);
		$resourceBuilder = Resource::builder($table, $resourceName);
		if ($addFields) {
			$resourceBuilder = $resourceBuilder->defaultFields();
		}
		if ($addFilters) {
			$resourceBuilder = $resourceBuilder->defaultFilters();
		}

		$resource = $resourceBuilder->build();

		return $resource;
	}
}