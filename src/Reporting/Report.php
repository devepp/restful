<?php

namespace App\Reporting;

use JsonSerializable;

class Report implements JsonSerializable
{
	public $id;
	public $module_id;
	public $group_name;
	public $name;
	public $description;
	public $slug;
	public $options;
	public $created_at;
	public $updated_at;
	public $deleted_at;

	public function JsonSerialize()
	{
		return [
			'id' => $this->id,
			'module_id' => $this->module_id,
			'group_name' => $this->group_name,
			'name' => $this->name,
			'description' => $this->description,
			'slug' => $this->slug,
			'options' => $this->options
		];
	}
}
