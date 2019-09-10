<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 10/24/2018
 * Time: 8:46 AM
 */

namespace App\Domain\Reports;

use eBase\Modules\Slam\Abstracts\AbstractReport;

class RecommendationCostsByAssetByYear extends AbstractReport
{
	protected $title = 'Recommendation Costs By Asset By Year';

	protected $field_names = [
		'name',
		'address',
		'city',
		'fci_cost_year_one',
		'fci_cost_year_two',
		'fci_cost_year_three',
		'fci_cost_year_four',
		'fci_cost_year_five',
		'fci_cost_five_year',
	];

	protected $headers = [
		[
			'heading' => 'Asset Name',
			'width' => 25,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'Address',
			'width' => 55,
			'heading_format' => 'header_left',
			'data_format' => 'border',
		],
		[
			'heading' => 'City',
			'width' => 25,
			'heading_format' => 'header_left',
			'data_format' => 'currency',
		],
		[
			'heading' => '2018',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'currency',
		],
		[
			'heading' => '2019',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'currency',
		],
		[
			'heading' => '2020',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'currency',
		],
		[
			'heading' => '2021',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'currency',
		],
		[
			'heading' => '2022',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'currency',
		],
		[
			'heading' => 'Total',
			'width' => 22,
			'heading_format' => 'header_left',
			'data_format' => 'currency',
		],
	];
}