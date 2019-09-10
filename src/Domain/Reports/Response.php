<?php

namespace App\Domain\Reports;

use App\Reporting\Resources\ReportConfig;
use App\Reporting\SelectionsInterface;
use App\Domain\Reports\WorkOrdersBaseReport;

class Response extends WorkOrdersBaseReport
{
	CONST SLUG = 'response';
	
	CONST TITLE = 'Response';
	
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
		
		$config = new ReportConfig('Response', $module->getTable('response'));
		
		//TODO add other tables
		// $config->addTable($module->getTable(''));
		
		$config->generateFieldsAndFilters();
		
		return $config;
	}
	
	protected function processData(SelectionsInterface $selections)
	{
	}
	
}
