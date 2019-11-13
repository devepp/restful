<?php

namespace App\Reporting;

class AbstractProviderRepository implements ProviderRepositoryInterface
{
	protected $providers;

	/**
	 * AbstractProviderRepository constructor.
	 * @param array $providers
	 */
	public function __construct($providers = [])
	{
		$this->providers = $providers;
	}

	/**
	 * @param string $provider_slug
	 * @return ProviderInterface
	 */
	public function getBySlug($provider_slug)
	{
		if (array_key_exists($provider_slug, $this->providers)) {
			$provider_class = $this->providers[$provider_slug];
			return new $provider_class;
		}
	}

	public function getAll()
	{
		$providers = [];
		foreach ($this->providers as $provider_class) {
			$providers[] = new $provider_class;
		}
		return $providers;
	}

	public function getAllList()
	{
		// TODO: Implement getAllList() method.
	}


}