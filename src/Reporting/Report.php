<?php

namespace App\Reporting;

use JsonSerializable;

class Report
{
	private $id;
	private $user_id;
	private $module_id;
	private $name;
	private $access_users;
	private $access_profiles;


	public function __construct($id, $user_id, $module_id, $name, $access_users, $access_profiles, $report_resource = NULL, $selections = NULL)
	{
		$this->id = $id;
		$this->user_id = $user_id;
		$this->module_id = $module_id;
		$this->name = $name;
		$this->report_resource = $report_resource;
		$this->selections = $selections;
		$this->access_users = $access_users;
		$this->access_profiles = $access_profiles;
	}

	public function getID()
	{
		return $this->id;
	}

	public function getUserID()
	{
		return $this->user_id;
	}

	public function getModuleID()
	{
		return $this->module_id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getAccessUsers()
	{
		return $this->access_users;
	}

	public function getAccessProfiles()
	{
		return $this->access_profiles;
	}
}