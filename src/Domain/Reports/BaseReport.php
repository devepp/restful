<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/22/2019
 * Time: 11:42 AM
 */

namespace App\Domain\Reports;


use App\Reporting\AbstractReport;
use App\Reporting\Filters\Constraints\Equals;
use App\Reporting\Resources\Condition;
use App\Reporting\Resources\ModuleConfiguration;
use App\Reporting\Resources\Relationship;
use App\Reporting\Resources\Table;

abstract class BaseReport extends AbstractReport
{
	protected function getModuleConfiguration()
	{
		$assets = new Table('slam_assets', 'assets');
		$clients = new Table('slam_clients', 'clients');
		$elements = new Table('slam_elements', 'elements');
		$codes = new Table('slam_uniformat_codes', 'codes');
		$recommendations = new Table('slam_recommendations', 'recommendations');

		$assets->setPrimaryKey('id');
		$assets->addForeignKey('client_id');
		$assets->addStringField('name');
		$assets->addStringField('client_reference_number');
		$assets->addStringField('description');
		$assets->addStringField('address');
		$assets->addStringField('city');
		$assets->addStringField('province');
		$assets->addStringField('postal');
		$assets->addStringField('country');
		$assets->addNumberField('gfa_footprint');
		$assets->addNumberField('floors');
		$assets->addNumberField('basement_levels');
		$assets->addNumberField('year_constructed');
		$assets->addStringField('ownership_type');
		$assets->addStringField('use');
		$assets->addStringField('replacement_value');
		$assets->addStringField('overall_summary');
		$assets->addStringField('architectural_summary');
		$assets->addStringField('mechanical_summary');
		$assets->addStringField('electrical_summary');
		$assets->addStringField('site_summary');

		$clients->setPrimaryKey('id');
		$clients->addStringField('name');
		$clients->addStringField('city');
		$clients->addStringField('province');

		$elements->setPrimaryKey('id');
		$elements->addForeignKey('asset_id');
		$elements->addForeignKey('uniformat_code_id');
		$elements->addStringField('name');
		$elements->addNumberField('qty');
		$elements->addNumberField('year_installed');

		$codes->setPrimaryKey('id');
		$codes->addStringField('code');
		$codes->addStringField('name');
		$codes->addNumberField('unit_cost');
		$codes->addStringField('units');
		$codes->addNumberField('typical_lifecycle');
		$codes->addNumberField('percent_renewal');
		$codes->addStringField('on');

		$recommendations->setPrimaryKey('id');
		$recommendations->addForeignKey('element_id');
		$recommendations->addStringField('name');
		$recommendations->addNumberField('recommendation_year');
		$recommendations->addNumberField('qty');
		$recommendations->addNumberField('unit_cost');
		$recommendations->addNumberField('cost');

		new Relationship($clients, $assets, [new Condition($assets->dbField('client_id'), new Equals(), [$clients->dbField('id')])]);
		new Relationship($assets, $elements, [new Condition($elements->dbField('asset_id'), new Equals(), [$assets->dbField('id')])]);
		new Relationship($elements, $recommendations, [new Condition($recommendations->dbField('element_id'), new Equals(), [$elements->dbField('id')])]);
		new Relationship($codes, $elements, [new Condition($elements->dbField('uniformat_code_id'), new Equals(), [$codes->dbField('id')])]);

		return new ModuleConfiguration([
			$assets,
			$elements,
			$recommendations,
			$clients,
			$codes,
		]);

	}
}