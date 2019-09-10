<?php

namespace App\Domain\Reports;

use App\Reporting\Resources\ReportConfig;
use App\Reporting\SelectionsInterface;

class AwesomeEntityReport extends BaseReport
{

	protected function getConfig()
	{
		$module = $this->getModuleConfiguration();

		$config = new ReportConfig('Assets', $module->getTable('elements'));

		$config->addTable($module->getTable('assets'));
		$config->addTable($module->getTable('clients'));
//		$config->addTable($module->getTable('elements'));
		$config->addTable($module->getTable('codes'));
		$config->addTable($module->getTable('recommendations'));
//		var_dump($config);
//		die();
		$config->generateFieldsAndFilters();

		return $config;
	}

	protected function processData(SelectionsInterface $selections)
	{

	}
}