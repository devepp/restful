<?php

namespace App\Domain\Reports;

use App\Reporting\Resources\ReportConfig;
use App\Reporting\Resources\ReportTemplate;
use App\Reporting\Resources\Resource;
use App\Reporting\Resources\ResourceInterface;
use App\Reporting\Resources\Schema;
use App\Reporting\SelectionsInterface;
use eBase\Modules\Slam\Reports\WorkOrdersBaseReport;
use JsonSerializable;

class WorkOrders extends ReportTemplate implements JsonSerializable
{
	use WorkOrdersSchema;

	CONST SLUG = 'work_orders';
	CONST TITLE = 'Work Orders';

	public function __construct()
	{
		$schema = $this->getSchema();

		$baseResource = Resource::builder($schema->getTable('work_orders'), 'Work Orders')
			->defaultFields()
			->defaultFilters()
			->build();

		$class = Resource::builder($schema->getTable('class'), 'Class')->defaultFields()->defaultFilters()->build();
		$reason = Resource::builder($schema->getTable('reason'), 'Reason')->defaultFields()->defaultFilters()->build();

		$facility = Resource::builder($schema->getTable('facility'), 'Facility')->defaultFields()->defaultFilters()->build();
		$area = Resource::builder($schema->getTable('area'), 'Area')->defaultFields()->defaultFilters()->build();

		$dispatchers = Resource::builder($schema->getTable('dispatcher'), 'Dispatcher')->defaultFields()->defaultFilters()->build();
		$serviceProviders = Resource::builder($schema->getTable('service_provider'), 'Service Provider')->defaultFields()->defaultFilters()->build();

		$jobs = Resource::builder($schema->getTable('jobs'), 'Jobs')->defaultFields(false)->build();
		$workTypes = Resource::builder($schema->getTable('work_types'), 'Work Types')->defaultFields(false)->build();
		$priority = Resource::builder($schema->getTable('priority'), 'Priority')->defaultFields()->defaultFilters(false)->build();

		$accountingNotes = Resource::builder($schema->getTable('accounting_notes'), 'Accounting Notes')->defaultFields(false)->build();
		$accountingNoteTypes = Resource::builder($schema->getTable('accounting_note_types'), 'Accounting Notes Types')->defaultFields(false)->build();

		$resources = [
			$class,
			$reason,
			$facility,
			$jobs,
			$workTypes,
			$dispatchers,
			$serviceProviders,
			$accountingNotes,
			$accountingNoteTypes,
		];

		parent::__construct($schema, $baseResource, $resources);
	}

	public function jsonSerialize()
	{
		return [
			'slug' => $this->slug(),
			'title' => $this->title(),
		];
	}


	public function slug()
	{
		return self::SLUG;
	}

	public function title()
	{
		return self::TITLE;
	}

	protected function getConfig()
	{
		try {
			$module = $this->getModuleConfiguration();

			$config = new ReportConfig('All Work Orders', $module->getTable('work_orders'));

			$config->addTable($module->getTable('ev'));
			$config->addTable($module->getTable('class'));
			$config->addTable($module->getTable('reason'));
			$config->addTable($module->getTable('facility'));
			$config->addTable($module->getTable('area'));
			$config->addTable($module->getTable('room'));
			$config->addTable($module->getTable('dispatchers'));
			$config->addTable($module->getTable('child_response'));
			$config->addTable($module->getTable('wo_jobs'));
			$config->addTable($module->getTable('wo_work_types'));
			$config->addTable($module->getTable('service_providers'));
			$config->addTable($module->getTable('wo_priorities'));
			$config->addTable($module->getTable('wo_service_group'));
			$config->addTable($module->getTable('wo_part_note'));
			$config->addTable($module->getTable('wo_accounting_notes'));
			$config->addTable($module->getTable('wo_accounting_note_type'));
			//		var_dump($config);
			//		die();
			$config->generateFieldsAndFilters();
			$config->addFilter($module->getTable('facility')->dbField('id'));

		} catch (\Exception $exception) {

			echo '<h3>';
			echo get_class($exception);
			echo '</h3>';
			echo '<p>';
			echo 'File: ';
			echo $exception->getFile();
			echo '</p>';
			echo '<p>';
			echo 'Line: ';
			echo $exception->getLine();
			echo '</p>';
			echo '<p>';
			echo $exception->getMessage();
			echo '</p>';
			echo '<pre>';
			echo $exception->getTraceAsString();
			echo '</pre>';
			exit();
		}

		return $config;
	}

	protected function processData(SelectionsInterface $selections)
	{

	}
}