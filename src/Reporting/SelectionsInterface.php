<?php

namespace App\Reporting;

interface SelectionsInterface
{
	/** return FieldInterface[] */
	public function selectedFields();

	/** return FilterInterface[] */
	public function selectedFilters();

	/** return Limit */
	public function limit();
}