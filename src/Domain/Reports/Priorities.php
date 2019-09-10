<?php

namespace App\Domain\Reports;

use App\Reporting\Resources\ReportConfig;
use App\Reporting\SelectionsInterface;
use App\Domain\Reports\WorkOrdersBaseReport;

class Priorities extends WorkOrdersBaseReport
{
	CONST SLUG = 'priorities';
	
	CONST TITLE = 'Priorities';
	
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
		
		$config = new ReportConfig('Priorities', $module->getTable('priorities'));
		
		//TODO add other tables
		// $config->addTable($module->getTable(''));
		
		$config->generateFieldsAndFilters();
		
		return $config;
	}
	
	protected function processData(SelectionsInterface $selections)
	{
	}
	
}
