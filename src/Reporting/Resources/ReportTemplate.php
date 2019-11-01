<?php


namespace App\Reporting\Resources;

class ReportTemplate implements ReportTemplateInterface
{
	/** @var ResourceInterface */
	private $baseResource;

	/** @var ResourceInterface[] */
	private $resources;

	/**
	 * ReportTemplate constructor.
	 * @param ResourceInterface $baseResource
	 * @param ResourceInterface[] $resources
	 */
	public function __construct(ResourceInterface $baseResource, array $resources)
	{
		$this->baseResource = $baseResource;
		$this->resources = $resources;
	}

	public static function builder($resource)
	{
		return new TemplateBuilder($resource);
	}

	public function availableRelatedResources()
	{
		return $this->resources;
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

}