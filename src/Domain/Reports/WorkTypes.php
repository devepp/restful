<?php

namespace App\Domain\Reports;

use App\Reporting\Resources\ReportConfig;
use App\Reporting\SelectionsInterface;
use App\Domain\Reports\WorkOrdersBaseReport;

class WorkTypes extends WorkOrdersBaseReport
{
	CONST SLUG = 'work_types';
	
	CONST TITLE = 'Work Types';
	
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
		$module = $this->getModuleConfiguration();
		
		$config = new ReportConfig('Work Types', $module->getTable('work_types'));
		
		//TODO add other tables
		// $config->addTable($module->getTable(''));
		
		$config->generateFieldsAndFilters();
		
		return $config;
	}
	
	protected function processData(SelectionsInterface $selections)
	{
	}
	
}
