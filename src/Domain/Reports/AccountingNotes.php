<?php

namespace App\Domain\Reports;

use App\Reporting\Resources\ReportConfig;
use App\Reporting\SelectionsInterface;
use App\Domain\Reports\WorkOrdersBaseReport;

class AccountingNotes extends WorkOrdersBaseReport
{
	CONST SLUG = 'accounting_notes';
	
	CONST TITLE = 'Accounting Notes';
	
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
		
		$config = new ReportConfig('Accounting Notes', $module->getTable('accounting_notes'));
		
		//TODO add other tables
		// $config->addTable($module->getTable(''));
		
		$config->generateFieldsAndFilters();
		
		return $config;
	}
	
	protected function processData(SelectionsInterface $selections)
	{
	}
	
}
