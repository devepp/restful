<?php

namespace App\Domain\Reports;

use App\Reporting\Resources\ReportConfig;
use App\Reporting\SelectionsInterface;
use App\Domain\Reports\WorkOrdersBaseReport;

class WorkOrders extends WorkOrdersBaseReport
{
	CONST SLUG = 'work_orders';
	CONST TITLE = 'Work Orders';

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