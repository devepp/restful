<?php


namespace App\Reporting\Resources;

use App\Reporting\DB\DbInterface;
use App\Reporting\DB\Query;
use App\Reporting\FieldInterface;
use App\Reporting\FilterInterface;
use App\Reporting\ReportFieldCollection;
use App\Reporting\ReportFilterCollection;
use App\Reporting\Request\ReportRequest;
use App\Reporting\SelectedFieldCollection;
use App\Reporting\SelectedFilterCollection;
use App\Reporting\TabularData;

class ReportTemplate implements ReportTemplateInterface
{
	/** @var Schema */
	private $schema;
	/** @var ResourceInterface */
	private $baseResource;

	/** @var ResourceInterface[] */
	private $availableResources;

	/** @var ResourceInterface[] */
	private $resources;

	/**
	 * ReportTemplate constructor.
	 * @param Schema $schema
	 * @param ResourceInterface $baseResource
	 * @param array $resources
	 */
	public function __construct(Schema $schema, ResourceInterface $baseResource, array $resources)
	{
		$this->schema = $schema;
		$this->baseResource = $baseResource;
		$this->availableResources = $resources;
		$this->resources = \array_merge([$baseResource], $resources);
	}

	public static function builder(Schema $schema, ResourceInterface $baseResource)
	{
		return new TemplateBuilder($schema, $baseResource);
	}

	public function fields()
	{
		$fields = new ReportFieldCollection();
		foreach ($this->resources as $resource) {
			$fields = $fields->withFields($resource->availableFields());
		}
		return $fields;
	}

	public function filters()
	{
		$filters = new ReportFilterCollection();
		foreach ($this->resources as $resource) {
			$filters = $filters->withFilters($resource->availableFilters());
		}
		return $filters;
	}

	public function getData(DbInterface $db, ReportRequest $request)
	{
		$fields = $this->fields()->getSelected($request);
		$filters = $this->filters()->getSelected($request);

		$queryGroup = $this->schema->getQueryGroup($this->getRootTable(), $this->getTables($fields, $filters));

		$queryBuilder = $queryGroup->getQuery($db, $fields, $filters, $this->getLimit($request), $request->groupings(), $request->sort());

		/** @var Query $query */
		$query = $queryBuilder->getQuery();

		$data = $db->execute($query);

		return new TabularData($fields, $data->all(\PDO::FETCH_ASSOC));
	}

	private function getLimit(ReportRequest $request)
	{
		return new Limit($request->limit(), $request->offset());
	}

	private function getRootTable()
	{
		return $this->baseResource->table();
	}

	private function getTables(SelectedFieldCollection $selectedFields, SelectedFilterCollection $selectedFilters)
	{
		$tables = TableCollection::fromArray([$this->getRootTable()]);

		foreach ($this->resources as $resource) {
			if ($this->resourceRequired($resource, $selectedFields, $selectedFilters)) {
				$tables = $tables->addTable($resource->table());
			}
		}

		return $tables;
	}

	private function resourceRequired(ResourceInterface $resource, SelectedFieldCollection $selectedFields, SelectedFilterCollection $selectedFilters)
	{
		/** @var FieldInterface $field */
		foreach ($selectedFields as $field) {
			if ($field->requiresTable($resource->table())) {
				return true;
			}
		}

		/** @var FilterInterface $filter */
		foreach ($selectedFilters as $filter) {
			if ($filter->requiresTable($resource->table())) {
				return true;
			}
		}

		return false;
	}

}