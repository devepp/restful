<?php

namespace App\Reporting;

interface ReportInterface
{
	public function output(SelectionsInterface $selections, OutputFormatterInterface $formatter);

	public function form();
}