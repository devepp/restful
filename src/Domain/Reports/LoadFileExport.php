<?php

namespace App\Domain\Reports;

use eBase\Modules\Slam\Abstracts\AbstractReport;
use eBase\Modules\Slam\Recommendations\Recommendation;

class LoadFileExport extends AbstractReport
{
	protected $title = 'Load File';

	protected $headers = [
		[
			'heading' => 'SYSTEM_EID',
			'width' => 25,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'assetID',
			'width' => 25,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Building Name',
			'width' => 25,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Assessed?',
			'width' => 25,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'U2Code',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'u2Cat',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'SYSTEM_NAME (Input)',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'SYSTEM_NAME',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'SYSTEM_DESCRIPTION',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'REVIEWER\'S SYSTEM_DESCRIPTION',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Year of Install',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'lifetime',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Plan Item',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Years Remaining',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Remaining Life (FCAPX)',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Action Year',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'quantity',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'unit',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Unit Cost',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'currency',
		],
		[
			'heading' => 'percentRenew',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Base Adjustment Factor',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Difficulty Factor',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Reason for Difficulty',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'adjustmentFactor',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Total Cost',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'currency',
		],
		[
			'heading' => 'Legacy',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
	];

	// --------------------------------------------------------------------

	/**
	 * @param $sheet
	 * @param $row_index
	 * @param Recommendation $recommendation
	 */
//	protected function writeDataRow($sheet, $row_index, $recommendation)
//	{
//		$col_index = 0;
//		$recommendation_attributes = $recommendation->getAttributes();
//
//		$element = $recommendation->getElement();
//		$element_attributes = $element->getAttributes();
//
//		$code = $element->getUniformatCode();
//
//		$asset = $element->getAsset();
//		$asset_attributes = $asset->getAttributes();
//
//		$type = $recommendation->getType();
//
//		$current_year = intval(date('Y'));
//		$years_remaining = $element_attributes['year_installed'] + $element_attributes['typical_lifecycle'] - $current_year;
//		$years_remaining_action = $recommendation->getYear() - $current_year;
//		$adjustment_factor = number_format($element_attributes['soft_cost_factor'] * $element_attributes['difficulty_factor'], 1);
//
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $element_attributes['import_ref_id']);
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $asset_attributes['import_ref_id']);
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $asset->getName());
//		$this->excel_helper->write($sheet, $col_index++, $row_index, null);
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $code->getCode());
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $code->getCode().' - '.$code->getName());
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $element->getName());
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $element->getName());
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $element_attributes['description']);
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $recommendation_attributes['description']);
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $element->getYearInstalled());
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $element_attributes['typical_lifecycle']);
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $type->getName());
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $years_remaining);
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $years_remaining_action);
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $recommendation->getYear());
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $recommendation->getQty());
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $element_attributes['units']);
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $recommendation->getUnitCost());
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $element_attributes['percent_renewal']);
//		$this->excel_helper->write($sheet, $col_index++, $row_index, number_format($element_attributes['soft_cost_factor'], 1));
//		$this->excel_helper->write($sheet, $col_index++, $row_index, number_format($element_attributes['difficulty_factor'], 1));
//		$this->excel_helper->write($sheet, $col_index++, $row_index, null);
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $adjustment_factor);
//		$this->excel_helper->write($sheet, $col_index++, $row_index, $recommendation->getCost()->cost());
//		$this->excel_helper->write($sheet, $col_index++, $row_index, null);
//	}

	// --------------------------------------------------------------------

	/**
	 * @param $sheet
	 * @param $row_index
	 * @param Recommendation $recommendation
	 */
	protected function writeDataRow($sheet, $row_index, $record)
	{
		$col_index = 0;

		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['element_ref_id']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['asset_ref_id']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['asset_name']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['assessed']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['uniformat_code']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['uniformat_code_name']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['element_name']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['element_name_modified']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['element_description']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['recommendation_description']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['year_installed']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['typical_lifecycle']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['type_name']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['expected_remaining_useful_life']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['observed_remaining_useful_life']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['recommendation_year']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['qty']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['units']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['unit_cost']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['percent_renewal']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['soft_cost_factor']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['difficulty_factor']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['difficulty_reason']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['adjustment_factor']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['recommendation_cost']);
		$this->excel_helper->write($sheet, $col_index++, $row_index, $record['legacy']);
	}

	// --------------------------------------------------------------------
}