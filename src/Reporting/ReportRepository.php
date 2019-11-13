<?php

namespace App\Reporting;

use CI;
use eBase\Database\Model;
use App\Reporting\Report;

class ReportRepository extends Model
{
	public $result_type = Report::class;

	protected $use_update_timestamps = true;

	// --------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct('ebase_reports er');
	}

	// --------------------------------------------------------------------

	public function _fetch($limit = null, $offset = '')
	{
		$this->db->where('er.deleted_at', null);

		$result = parent::_fetch($limit, $offset);

		foreach ($result as $row) {
			$row->options = json_decode($row->options);
		}

		return $result;
	}

	// --------------------------------------------------------------------

	public function save($data, $id = null)
	{
		if (array_key_exists('options', $data)) {
			$data['options'] = json_encode($data['options']);
		}

		return parent::save($data, $id);
	}

	// --------------------------------------------------------------------

	public function remove($id)
	{
		return $this->save([
			'deleted_at' => date('Y-m-d H:i:s')
		], $id);
	}

	// --------------------------------------------------------------------

	public function findAllowed($module_id, $user_id)
	{
		// TODO implement permissions
		return $this
			->select('er.id, IF(LENGTH(er.group_name), er.group_name, "(Ungrouped)") group_name, er.name, er.description, er.slug')
			->order_by('group_name, er.name')
			->get_all();
	}

	// --------------------------------------------------------------------
}
