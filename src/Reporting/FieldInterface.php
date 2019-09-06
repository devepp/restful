<?php

namespace App\Reporting;

interface FieldInterface
{
	/** return string */
	public function name();
	/** return string */
	public function relationship();
	/** return string */
	public function label();
}