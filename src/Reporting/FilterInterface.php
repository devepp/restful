<?php

namespace App\Reporting;

interface FilterInterface
{
	/** return string */
	public function name();
	/** return string */
	public function relationship();
	/** return string */
	public function label();
}