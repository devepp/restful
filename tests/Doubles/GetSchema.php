<?php

namespace Tests\Doubles;

use App\Reporting\Resources\Table;
use App\Reporting\Resources\Schema;
use App\Reporting\Resources\TableName;

trait GetSchema
{


	protected function getSchema()
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
			->addTable($workOrders)
			->addTable($facilities)
			->addTable($authors)
			->addTable($dispatchers)
			->addTable($classes)
			->addTable($jobs)
			->addTable($classes)
			->addTable($invoices)
			->addTable($invoiceTypes)
			->build();
	}

	protected function getWoTable($alias = 'wo_orders')
	{
		return Table::builder('wo_orders')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addManyToOneRelationship(new TableName('facilities'),'facility_id', 'work_orders.facility_id = facilities.id')
			->addManyToOneRelationship(new TableName('users', 'authors'), 'user_id','work_orders.user_id = authors.id')
			->addManyToOneRelationship(new TableName('users', 'dispatchers'), 'dispatcher_id', 'work_orders.dispatcher_id = dispatchers.id')
			->addManyToOneRelationship(new TableName('wo_types', 'classes'), 'type_id', 'work_orders.type_id = classes.id')
			->build();
	}

	protected function get_facilities($alias = 'facilities')
	{
		return Table::builder('facilities')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addStringField('name')
			->addStringField('facility_number')
			->build();
	}

	protected function get_users($alias = 'users')
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

	protected function get_wo_types($alias = 'class')
	{
		return Table::builder('wo_types')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addStringField('name')
			->build();
	}

	protected function get_wo_jobs($alias = 'wo_jobs')
	{
		return Table::builder('wo_jobs')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addManyToOneRelationship(new TableName('wo_orders', 'work_orders'), 'work_order_id', 'jobs.type_id = work_orders.id')
			->addStringField('subject')
			->addStringField('description')
			->addNumberField('assigned_to')
			->addDateTimeField('created_on')
			->addStringField('state')
			->build();
	}

	protected function get_wo_accounting_notes($alias = 'wo_accounting_notes')
	{
		return Table::builder('wo_accounting_notes')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addManyToOneRelationship(new TableName('wo_accounting_note_type', 'invoice_types'), 'accounting_note_type_id', 'invoices.accounting_note_type_id = invoice_types.id')
			->addNumberField('price')
			->addNumberField('total')
			->addManyToOneRelationship(new TableName('wo_jobs', 'jobs'), 'job_id', 'invoices.job_id = jobs.id')
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