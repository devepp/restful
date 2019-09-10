<?php

namespace App\Domain\Reports;

use eBase\Modules\Slam\Abstracts\AbstractReport;
use eBase\Modules\Slam\Recommendations\Recommendation;

class AllRecommendations extends AbstractReport
{
	protected $title = 'All Recommendations';

	protected $field_names = [
		'asset_name',
		'asset_city',
		'element_id',
		'element_name',
		'element_description',
		'element_year_installed',
		'element_last_assessment',
		'element_condition_name',
		'element_condition_narrative',
		'element_qty',
		'units',
		'typical_lifecycle',
		'observed_renewal_year',
		'soft_cost_factor',
		'regional_factor',
		'difficulty_factor',
		'renewal_cost',
		'uniformat_code',
		'uniformat_name',
		'recommendation_name',
		'recommendation_type',
		'recommendation_status',
		'recommendation_description',
		'recommendation_priority',
		'recommendation_mvp_score',
		'recommendation_year',
		'recommendation_qty',
		'units',
		'recommendation_unit_cost',
		'recommendation_cost',
	];

	protected $headers = [
		[
			'heading' => 'Asset Name',
			'width' => 25,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Asset City',
			'width' => 25,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Element ID',
			'width' => 12,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Element Name',
			'width' => 40,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Element Description',
			'width' => 60,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Element Year Installed',
			'width' => 20,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Element Last Assessment',
			'width' => 20,
			'heading_format' => 'header_left',
			'data_format' => 'date',
		],
		[
			'heading' => 'Element Condition',
			'width' => 30,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Element Condition Narrative',
			'width' => 60,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Element Qty',
			'width' => 15,
			'heading_format' => 'header_left',
			'data_format' => 'numeric',
		],
		[
			'heading' => 'Element Units',
			'width' => 15,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Element Typical Lifecycle',
			'width' => 20,
			'heading_format' => 'header_left',
			'data_format' => 'numeric',
		],
		[
			'heading' => 'Element Observed Renewal Year',
			'width' => 20,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Soft Cost Factor',
			'width' => 12,
			'heading_format' => 'header_left',
			'data_format' => 'numeric',
		],
		[
			'heading' => 'Regional Factor',
			'width' => 12,
			'heading_format' => 'header_left',
			'data_format' => 'numeric',
		],
		[
			'heading' => 'Difficulty Factor',
			'width' => 12,
			'heading_format' => 'header_left',
			'data_format' => 'numeric',
		],
		[
			'heading' => 'Element Renewal Cost',
			'width' => 30,
			'heading_format' => 'header_left',
			'data_format' => 'currency',
		],
		[
			'heading' => 'Uniformat Code',
			'width' => 20,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Uniformat Name',
			'width' => 30,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Recommendation Name',
			'width' => 30,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Recommendation Type',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Recommendation Status',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Recommendation Description',
			'width' => 60,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Recommendation Priority',
			'width' => 20,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'MVP Score',
			'width' => 20,
			'heading_format' => 'header_left',
			'data_format' => 'numeric',
		],
		[
			'heading' => 'Recommendation Year',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Qty',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'numeric',
		],
		[
			'heading' => 'Units',
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
			'heading' => 'Recommendation Cost',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'currency',
		],
	];

	// --------------------------------------------------------------------
}