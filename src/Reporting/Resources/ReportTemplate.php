<?php


namespace App\Reporting\Resources;

use App\Reporting\DB\QueryBuilderFactoryInterface;
use App\Reporting\Processing\Selections;
use App\Reporting\SelectionsInterface;
use Psr\Http\Message\ServerRequestInterface;

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

	public function availableRelatedResources()
	{
		return $this->availableResources;
	}

	public function nestedFields()
	{
		$fields = [];

		foreach ($this->resources as $resource) {
			$fields[$resource->name()] = [
				'name' => $resource->name(),
				'fields' => $resource->availableFields(),
			];
		}

		return $fields;
	}

	/**
	 * @inheritdoc
	 */
	public function availableFields()
	{
		$fields = [];
		foreach ($this->resources as $resource) {
			$fields[] = $resource->availableFields();
		}

		return $fields;
	}

	public function nestedFilters()
	{
		$filters = [];
		foreach ($this->resources as $resource) {
			$filters[$resource->name()] = [
				'name' => $resource->name(),
				'filters' => $resource->availableFilters(),
			];
		}

		return $filters;
	}

	/**
	 * @inheritdoc
	 */
	public function availableFilters()
	{
		$filters = [];
		foreach ($this->resources as $resource) {
			$filters[] = $resource->availableFilters();
		}

		return $filters;
	}

	public function getQuery(QueryBuilderFactoryInterface $queryBuilderFactory, ServerRequestInterface $request)
	{
		$selections = Selections::fromRequest($request, $this);

		$queryGroup = $this->schema->getQueryGroup($this->getRootTable(), $this->getTables($request));

		return $queryGroup->getQuery($queryBuilderFactory, $selections)->getQuery();
	}

	private function getRootTable()
	{
		return $this->baseResource->table();
	}

	private function getTables(ServerRequestInterface $request)
	{
		$tables = TableCollection::fromArray([$this->getRootTable()]);

		foreach ($this->resources as $resource) {
			if ($this->resourceRequired($resource, $request)) {
				$tables->addTable($resource->table());
			}
		}

		return $tables;
	}

	private function resourceRequired(ResourceInterface $resource, ServerRequestInterface $request)
	{
		foreach ($this->availableFields() as $field) {
			if ($field->selected($request)) {
				return true;
			}
		}

		foreach ($this->availableFilters() as $filter) {
			if ($filter->selected($request)) {
				return true;
			}
		}

		return false;
	}

}