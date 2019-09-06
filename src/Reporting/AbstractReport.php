<?php

namespace App\Reporting;


use App\Reporting\Processing\Selections;
use App\Reporting\Resources\ReportConfig;
use App\Reporting\Selectables\AbstractSelectable;
use App\Reporting\Selectables\Standard;
use App\Reporting\Filters\Constraints\AbstractConstraint;
use App\Reporting\TabularData;
use JsonSerializable;

abstract class AbstractReport implements ReportInterface, ProviderInterface, JsonSerializable
{
	/** @var ReportConfig */
	protected $config;
	protected $selected_fields;
	protected $selected_filters;
	protected $data;

	abstract public function slug();
	abstract public function title();
	abstract protected function getConfig();
	abstract protected function processData(SelectionsInterface $selections);

	public function output(SelectionsInterface $selections, OutputFormatterInterface $formatter)
	{
		$tabular_data = $this->processData($selections);

		return $formatter->output($selections, $tabular_data);
	}

	public function form()
	{
		return $this->config()->form();
	}

	public function processInput($input, $db = false)
	{
		$form = $this->form();

		$selections = new Selections($input, $form);

		$config = $this->config();

//		var_dump($config);

		$sql = $config->generateSql($selections);

		if ($db === false) {
			return $sql;
		}

		return new TabularData($selections->selectedFields(), $db->query($sql)->result());
	}

	public function jsonSerialize()
	{
		return [
			'slug' => $this->slug(),
			'title' => $this->title(),
		];
	}

	protected function config()
	{
		if (!$this->config) {
			$this->config = $this->getConfig();
		}
		return $this->config;
	}

}