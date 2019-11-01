<?php

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;
use Tests\Doubles\GetSchema;

class ReportingTest extends TestCase
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
}