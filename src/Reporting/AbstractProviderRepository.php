<?php

namespace App\Reporting;

class AbstractProviderRepository implements ProviderRepositoryInterface
{
	CONST PROVIDERS = [];

	/**
	 * @param string $provider_slug
	 * @return ProviderInterface
	 */
	public function getBySlug($provider_slug)
	{
		if (array_key_exists($provider_slug, static::PROVIDERS)) {
			$provider_class = static::PROVIDERS[$provider_slug];
			return new $provider_class;
		}
	}

	public function getAll()
	{
		$providers = [];
		foreach (static::PROVIDERS as $provider_class) {
			$providers[] = new $provider_class;
		}
		return $providers;
	}

	public function getAllList()
	{
		// TODO: Implement getAllList() method.
	}


}