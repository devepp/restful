<?php

namespace App\Reporting;

use CI;
use App\Reporting\Report;

class ReportRepository
{
	// --------------------------------------------------------------------

	public function find($id)
	{
		$report = CI::$APP->db->select('er.*, eru.user_id, concat(u.first_name, ," ", u.last_name) created_by, ep.name profile_name, em.label module')
			->from('ebase_reports er')
			->join('ebase_reports_users eru', 'er.id = eru.report_id')
			->join('ebase_reports_profiles erp', 'er.id = erp.report_id')
			->join('users u', 'er.user_id = u.id')
			->join('ebase_profiles ep', 'erp.profile_id = ep.id')
			->join('ebase_modules em', 'em.id = er.module_id')
			->where('er.id', $id)
			->get()
			->row();

		if (!$report) {
			$result = (object) ['status_code' => 404, 'message' => 'Record not found', 'id' => $id];
			return $result;
		}

		$access_users = CI::$APP->db->select('eru.*, concat(u.first_name, " ", u.last_name) name')
			->from('ebase_reports_users eru')
			->join('users u', 'eru.user_id = u.id')
			->where('eru.report_id', $id)
			->get()
			->result();

		foreach ($access_users as $user) {
			$report->access_users[] = array('user_id' => $user->user_id, 'modify' => $user->modify, 'name' => $user->name);
		}

		$access_profiles =  CI::$APP->db->select('erp.*, ep.name')
			->from('ebase_reports_profiles erp')
			->join('ebase_profiles ep', 'erp.profile_id = ep.id')
			->where('erp.report_id', $id)
			->get()
			->result();

		foreach ($access_profiles as $profile) {
			$report->access_profiles[] = array('profile_id' => $profile->profile_id, 'modify' => $profile->modify, 'name' => $profile->name);
		}
		
		$report = $this->check_report_access($report);

		return $report;
	}

	// --------------------------------------------------------------------

	public function find_all($module = false, $include_deleted = false, $include_no_access = false )
	{
		$report_results = (object) ['reports' => array(), 'message' => '' , 'status_code' => ''];
		$reports = array();

		CI::$APP->db->select('*')
			->from('ebase_reports');
		if ($module) {
			CI::$APP->db->where('module_id', $module);
		}
		$ids = CI::$APP->db->get()->result();

		foreach ($ids as $id) {
			$report = $this->find($id->id);
			if ($report->message === 1) {
				$reports[] = $report;
			}
			if ($include_deleted) {
				if ($report->message === 404) {
					$reports[] = $report;
				}	
			}
			if ($include_no_access) {
				if ($report->message === 403) {
					$reports[] = $report;
				}
			}
		}

		if (count($reports) < 1) {
			$report_results->status_code = 404;
			$report_results->message = "Reports Deleted";
		} else {
			$report_results->status_code = 200;
			$report_results->message = 1;
		}
		
		$report_results->reports = $reports;

		return $report_results;
	}

	// --------------------------------------------------------------------
	
	private function check_report_access($report)
	{
		$result = (object) ['status_code' => 200];
		$report->status_code = $result->status_code;
		$access = 0;
		$message = 1;
		//VALUDATE STATUS
		if ($report->deleted_at) {
			$result->id = $report->id;
			$result->status_code = 404;
			$result->message = "Report Deleted";
			return $result;
		}
		// VALIDATE PROFILE ACCESS
		$user_profiles = CI::$APP->user->profiles;
		
		$access_profiles = [];
		foreach ($report->access_profiles as $profiles) {
			$access_profiles[] = $profiles['profile_id'];
		}

		foreach ($user_profiles as $profile_key => $profile_name) {
			if (in_array($profile_key, $access_profiles)) {
				$access = 1;
			}
		}
		// VALIDATE USER ACCESS
		$user_id = CI::$APP->user->id;
		if ($report->user_id == $user_id || $user_id == 1) {
			$access = 1;
		}
		// CHECK ACCESS RESULT
		if ($access === 0) {
			$result->id = $report->id;
			$result->status_code = 403;
			$result->message = "No Access";
			return $result;
		}
		$report->message = $message;

		return $report;
	}

	// --------------------------------------------------------------------
	public function save(Report $report)
	{

		$report_data = array(
			'module_id' => $report->getModuleID(),
			'name' => $report->getName(),
			'report_resource' => $report->report_resource,
			'selections' => $report->selections,
			'updated_at' => date('Y-m-d h:i:s', time())
		);

		
		$id = $report->getID();

		if ($id > 0) {
			CI::$APP->db->where('id', $report->getID());
			CI::$APP->db->update('ebase_reports', $report_data);
			$report_data['id'] =  $report->getID();
		} else {
			$report_data['created_at'] = date('Y-m-d h:i:s', time());
			$report_data['user_id'] = $report->getUserID();
			CI::$APP->db->insert('ebase_reports', $report_data);
			$report_data['id'] = CI::$APP->db->insert_id();
		}
		
		$this->process_user_access($id, $report->getAccessUsers());
		$this->process_profile_access($id, $report->getAccessProfiles());
		
		$result = (object) ['id' => $report_data['id'], 'status_code' => 200, 'message' => 1];

		return $result;
	}
	
	// --------------------------------------------------------------------
	private function process_user_access($id, $report_users)
	{
		// DELETE SHARE USERS
		$this->delete_user_access($id);
		// ADD USER ACCESS
		foreach ($report_users as $user) {
			$insert = array(
				'user_id' => $user['user_id'],
				'report_id' => $id,
				'modify' => $user['modify']
			);
			
			CI::$APP->db->insert('ebase_reports_users', $insert);
		}
		return;
	}

	// --------------------------------------------------------------------
	private function process_profile_access($id, $report_profiles)
	{
		// DELETE SHARE PROFILES
		$this->delete_profile_access($id);
		// ADD PROFILE ACCESS
		foreach ($report_profiles as $profile) {
			$insert = array(
				'profile_id' => $profile['profile_id'],
				'report_id' => $id,
				'modify' => $profile['modify']
			);
			
			CI::$APP->db->insert('ebase_reports_profiles', $insert);
		}
		return;
	}

	// --------------------------------------------------------------------
	public function delete_report($id)
	{
		// DELETE SHARE USER
		$this->delete_user_access($id);
		// DELETE SHARE PROFILE
		$this->delete_profile_access($id);
		
		// DELETE REPORT
		CI::$APP->db->where('id', $id);
		CI::$APP->db->update('ebase_reports', array('deleted_at' => date('Y-m-d h:i:s', time())));
		
		$result = (object) ['id' => $id, 'status_code' => 200, 'message' => 1];

		return $result;
	}

	// --------------------------------------------------------------------
	private function delete_user_access($id)
	{
		CI::$APP->db->delete('ebase_reports_users', array('report_id' => $id));
		return;
	}

	// --------------------------------------------------------------------
	private function delete_profile_access($id)
	{
		CI::$APP->db->delete('ebase_reports_profiles', array('report_id' => $id));
		return;
	}

	// --------------------------------------------------------------------
}