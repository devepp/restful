<?php

namespace Tests\Functional;

use App\Domain\Reports\WorkOrders;
use App\Reporting\DB\Connection;
use App\Reporting\DB\DbInterface;
use App\Reporting\ReportRequest;
use Helmich\JsonAssert\JsonAssertions;
use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{
	use JsonAssertions;

	public function testFields()
	{
		$reportTemplate = $this->getReportTemplate();

		$fields = $reportTemplate->fields();

//		$json = \json_decode(\json_encode($fields->asGroupedJsonArray()), true);
//		$this->assertJsonDocumentMatchesSchema($json, $this->nestedJsonFieldSchema());

		$this->assertJsonDocumentMatchesSchema($fields->asGroupedJsonArray(), $this->nestedJsonFieldSchema());

	}

	public function testFilters()
	{
		$reportTemplate = $this->getReportTemplate();

		$filters = $reportTemplate->filters();

//		$json = \json_decode(\json_encode($filters->asGroupedJsonArray()), true);
//		$this->assertJsonDocumentMatchesSchema($json, $this->nestedJsonFilterSchema());

		$this->assertJsonDocumentMatchesSchema($filters->asGroupedJsonArray(), $this->nestedJsonFilterSchema());
	}

	public function testGetData()
	{
		$reportTemplate = $this->getReportTemplate();

		$db = $this->createMock(Connection::class);


		$request = new ReportRequest();

		$data = $reportTemplate->getData($db, $request);
	}

	private function getReportTemplate()
	{
		return new WorkOrders();
	}

	private function nestedJsonFieldSchema()
	{
		return [
			'type' => 'array',
			'items' => [
				'type' => 'object',
				'properties' => [
					'name' => ['type' => 'string'],
					'fields' => [
						'type' => 'array',
						'items' => [
							'type' => 'object',
							'properties' => [
								'id' => ['type' => 'string'],
								'label' => ['type' => 'string'],
								'defaultLabel' => ['type' => 'string'],
								'groupName' => ['type' => 'string'],
								'modifier' => ['type' => ['string', 'null']],
								'availableModifiers' => [
									'type' => 'array',
									'items' => [
										'type' => 'object',
										'properties' => [
											'name' => ['type' => 'string'],
										],
										'required' => ['name']
									]
								],
							],
							'required' => ['id', 'label', 'defaultLabel', 'groupName', 'availableModifiers']
						],
					],
				],
				'required' => ['name', 'fields']
			]
		];
	}

	private function nestedJsonFilterSchema()
	{
		return [
			'type' => 'array',
			'items' => [
				'type' => 'object',
				'properties' => [
					'name' => ['type' => 'string'],
					'filters' => [
						'type' => 'array',
						'items' => [
							'type' => 'object',
							'properties' => [
								'id' => ['type' => 'string'],
								'label' => ['type' => 'string'],
								'defaultLabel' => ['type' => 'string'],
								'groupName' => ['type' => 'string'],
								'constraint' => [
									'type' => ['object', 'null'],
									'properties' => [
										'name' => ['type' => 'string'],
										'directive' => ['type' => ['string', 'null']],
										'required_inputs' => ['type' => 'integer'],
									],
									'required' => ['name', 'directive', 'required_inputs']
								],
								'constraints' => [
									'type' => 'array',
									'items' => [
										'type' => 'object',
										'properties' => [
											'name' => ['type' => 'string'],
											'directive' => ['type' => ['string', 'null']],
											'required_inputs' => ['type' => 'integer'],
										],
										'required' => ['name', 'directive', 'required_inputs']
									]
								],
							],
							'required' => ['id', 'label', 'defaultLabel', 'groupName', 'constraints']
						],
					],
				],
				'required' => ['name', 'filters']
			]
		];
	}
}