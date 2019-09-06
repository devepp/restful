<?php

namespace App\Reporting;


interface ProviderRepositoryInterface
{

	/**
	 * @param string $providerSlug
	 * @return ProviderInterface
	 */
	public function getBySlug($providerSlug);

	/**
	 * @return ProviderInterface[]
	 */
	public function getAll();
}