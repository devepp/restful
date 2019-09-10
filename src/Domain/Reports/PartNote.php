<?php

namespace App\Domain\Reports;

use App\Reporting\Resources\ReportConfig;
use App\Reporting\SelectionsInterface;
use App\Domain\Reports\WorkOrdersBaseReport;

class PartNote extends WorkOrdersBaseReport
{
	CONST SLUG = 'part_note';
	
	CONST TITLE = 'Part Note';
	
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
		
		$config = new ReportConfig('Part Note', $module->getTable('part_note'));
		
		//TODO add other tables
		// $config->addTable($module->getTable(''));
		
		$config->generateFieldsAndFilters();
		
		return $config;
	}
	
	protected function processData(SelectionsInterface $selections)
	{
	}
	
}
