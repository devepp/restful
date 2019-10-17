<?php

use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;
use PHPUnit\Framework\TestCase;

class ReportingTest extends TestCase
{
	/**
	 * @dataProvider pathProvider
	 */
	public function testReporting($firstTableAlias, $secondTableAlias, $expectedPath)
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
		];
	}

	private function getSchema()
	{
		$workOrders = $this->getWoTable('work_orders');
		$facilities = $this->get_facilities();
		$authors = $this->get_users('authors');
		$dispatchers = $this->get_users('dispatchers');
		$classes = $this->get_wo_types('classes');
		$jobs = $this->get_wo_jobs('jobs');
		$invoices = $this->get_wo_accounting_notes('invoices');
		$invoiceTypes = $this->get_wo_accounting_note_type('invoice_types');

		return Schema::builder()
			->addManyToOneRelationship($workOrders, $facilities, 'work_orders.facility_id = facilities.id')
			->addManyToOneRelationship($workOrders, $authors, 'work_orders.user_id = authors.id')
			->addManyToOneRelationship($workOrders, $dispatchers, 'work_orders.dispatcher_id = dispatchers.id')
			->addManyToOneRelationship($workOrders, $classes, 'work_orders.type_id = classes.id')
			->addManyToOneRelationship($jobs, $workOrders, 'jobs.type_id = work_orders.id')
			->addManyToOneRelationship($invoices, $jobs, 'invoices.job_id = jobs.id')
			->addManyToOneRelationship($invoices, $invoiceTypes, 'invoices.accounting_note_type_id = invoice_types.id')
			->build();
	}

	private function getWoTable($alias = 'wo_orders')
	{
		return Table::builder('wo_orders')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('facility_id')
			->addForeignKey('user_id')
			->addForeignKey('type_id')
			->addForeignKey('dispatcher_id')
			->build();
	}

	private function get_facilities($alias = 'facilities')
	{
		return Table::builder('facilities')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addStringField('name')
			->addStringField('facility_number')
			->build();
	}

	private function get_users($alias = 'users')
	{
		return Table::builder('users')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addStringField('username')
			->addStringField('first_name')
			->addStringField('last_name')
			->addStringField('email')
			->build();
	}

	private function get_wo_types($alias = 'class')
	{
		return Table::builder('wo_types')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addStringField('name')
			->build();
	}

	private function get_wo_jobs($alias = 'wo_jobs')
	{
		return Table::builder('wo_jobs')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('work_order_id')
			->addStringField('subject')
			->addStringField('description')
			->addForeignKey('priority_id')
			->addNumberField('assigned_to')
			->addDateTimeField('created_on')
			->addStringField('state')
			->build();
	}

	private function get_wo_accounting_notes($alias = 'wo_accounting_notes')
	{
		return Table::builder('wo_accounting_notes')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('accounting_note_type_id')
			->addNumberField('price')
			->addNumberField('total')
			->addForeignKey('job_id')
			->build();
	}

	protected function get_wo_accounting_note_type($alias = 'wo_accounting_note_type')
	{
		return Table::builder('wo_accounting_note_type')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addStringField('name')
			->build();
	}
}