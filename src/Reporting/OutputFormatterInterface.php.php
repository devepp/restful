<?php

namespace App\Reporting;

interface OutputFormatterInterface
{
	public function output(SelectionsInterface $selections, TabularData $tabular_data);
}