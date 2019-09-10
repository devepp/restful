<?php

namespace eBase\Modules;

use App\Reporting\AbstractReport;
use App\Reporting\Resources\ModuleConfiguration;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\Relationship;
use App\Reporting\Resources\Condition;
use App\Reporting\Filters\Constraints\Equals;

class SlamReportsWorkOrdersBaseReport extends AbstractReport
{
	protected function getModuleConfiguration()
	{
		$orders = $this->get_wo_orders('orders');
		$events = $this->get_events('events');
		$types = $this->get_wo_types('types');
		$facilities = $this->get_facilities('facilities');
		$jobs = $this->get_wo_jobs('jobs');
		$accounting_notes = $this->get_wo_accounting_notes('accounting_notes');
		$accounting_note_type = $this->get_wo_accounting_note_type('accounting_note_type');
		$users = $this->get_users('users');
		$work_types = $this->get_wo_work_types('work_types');
		$reasons = $this->get_wo_reasons('reasons');
		$facility_areas = $this->get_facility_areas('facility_areas');
		$priorities = $this->get_wo_priorities('priorities');
		$response = $this->get_wo_response('response');
		$facility_rooms = $this->get_facility_rooms('facility_rooms');
		$service_group = $this->get_wo_service_group('service_group');
		$part_note = $this->get_wo_part_note('part_note');
		
		//TODO Define Relationships here
		
		
		return new ModuleConfiguration([
			$orders,
			$events,
			$types,
			$facilities,
			$jobs,
			$accounting_notes,
			$accounting_note_type,
			$users,
			$work_types,
			$reasons,
			$facility_areas,
			$priorities,
			$response,
			$facility_rooms,
			$service_group,
			$part_note,
		]);
	}
	
	protected function get_wo_orders($alias = 'orders')
	{
		$wo_orders = new Table('wo_orders', $alias);
		
		$wo_orders->setPrimaryKey('id');
		$wo_orders->addForeignKey('vt_id');
		$wo_orders->addForeignKey('facility_id');
		$wo_orders->addForeignKey('user_id');
		$wo_orders->addForeignKey('type_id');
		$wo_orders->addForeignKey('reason_id');
		$wo_orders->addNumberField('number');
		$wo_orders->addNumberField('sch_number');
		$wo_orders->addStringField('origin');
		$wo_orders->addForeignKey('origin_id');
		$wo_orders->addStringField('state');
		$wo_orders->addForeignKey('dispatcher_id');
		$wo_orders->addForeignKey('dispatcher_viewable_id');
		$wo_orders->addStringField('requested_by');
		$wo_orders->addStringField('availability');
		$wo_orders->addForeignKey('facility_department_id');
		$wo_orders->addForeignKey('facility_area_id');
		$wo_orders->addForeignKey('facility_room_id');
		$wo_orders->addStringField('facility_room_type');
		$wo_orders->addStringField('plant_room_no');
		$wo_orders->addStringField('location_description');
		$wo_orders->addDateTimeField('submitted_on');
		$wo_orders->addDateTimeField('issued');
		$wo_orders->addDateTimeField('completed_on');
		$wo_orders->addDateTimeField('closed_on');
		$wo_orders->addDateTimeField('cancelled_on');
		$wo_orders->addDateTimeField('last_activity_on');
		$wo_orders->addForeignKey('completed_response_id');
		$wo_orders->addStringField('emergency');
		$wo_orders->addStringField('equipment_no');
		$wo_orders->addStringField('schedule');
		$wo_orders->addStringField('schedule_label');
		$wo_orders->addBooleanField('schedule_enabled');
		$wo_orders->addDateTimeField('schedule_prev_run');
		$wo_orders->addDateTimeField('schedule_next_run');
		$wo_orders->addDateField('schedule_last_day');
		$wo_orders->addNumberField('schedule_self_destruct');
		$wo_orders->addStringField('reminder_state');
		$wo_orders->addDateTimeField('reminder_set_on');
		$wo_orders->addDateTimeField('reminder_last_notification');
		$wo_orders->addNumberField('DELETE_reminder_interval');
		$wo_orders->addDateTimeField('reminder_date');
		$wo_orders->addNumberField('reminder_restore_state');
		$wo_orders->addForeignKey('reminder_user_id');
		$wo_orders->addForeignKey('inspection_id');
		$wo_orders->addStringField('estimate');
		$wo_orders->addStringField('account_number');
		$wo_orders->addStringField('it_work_order_number');
		$wo_orders->addBooleanField('approve_immediately');
		$wo_orders->addDateTimeField('is_estimate_required');
		$wo_orders->addForeignKey('year_id');
		$wo_orders->addBooleanField('job_complete');
		$wo_orders->addNumberField('job_score_number');
		$wo_orders->addStringField('job_score_name');
		$wo_orders->addBooleanField('contractor_verifies_job_completed');
		$wo_orders->addDateField('job_completed_on');
		$wo_orders->addStringField('job_completed_by');
		$wo_orders->addStringField('job_completed_hours_worked');
		$wo_orders->addStringField('job_verification_message');
		$wo_orders->addBooleanField('is_archived');
		$wo_orders->addForeignKey('client_id');
		$wo_orders->addStringField('misc');
		$wo_orders->addStringField('requirements_progress');
		$wo_orders->addStringField('schedule_locations');
		
		return $wo_orders;
	}
	
	protected function get_events($alias = 'events')
	{
		$events = new Table('events', $alias);
		
		$events->setPrimaryKey('id');
		$events->addStringField('ref_type');
		$events->addForeignKey('ref_id');
		$events->addForeignKey('parent_id');
		$events->addStringField('event');
		$events->addStringField('event_source');
		$events->addStringField('event_target');
		$events->addForeignKey('facility_id');
		$events->addForeignKey('user_id');
		$events->addStringField('message');
		$events->addBooleanField('is_user_comment');
		$events->addDateTimeField('created_on');
		$events->addDateTimeField('deleted_at');
		
		return $events;
	}
	
	protected function get_wo_types($alias = 'types')
	{
		$wo_types = new Table('wo_types', $alias);
		
		$wo_types->setPrimaryKey('id');
		$wo_types->addForeignKey('vt_id');
		$wo_types->addStringField('name');
		$wo_types->addNumberField('budget_type');
		$wo_types->addStringField('gl_code');
		$wo_types->addForeignKey('default_reason_id');
		$wo_types->addForeignKey('DELETE_logs_submission_template_id');
		$wo_types->addStringField('DELETE_logs_submission_required_states');
		$wo_types->addNumberField('sort_order');
		$wo_types->addDateTimeField('deleted_at');
		$wo_types->addStringField('logs_submission_required_state');
		$wo_types->addForeignKey('logs_submission_template_id');
		
		return $wo_types;
	}
	
	protected function get_facilities($alias = 'facilities')
	{
		$facilities = new Table('facilities', $alias);
		
		$facilities->setPrimaryKey('id');
		$facilities->addStringField('name');
		$facilities->addStringField('facility_number');
		$facilities->addStringField('address');
		$facilities->addStringField('city');
		$facilities->addStringField('postal');
		$facilities->addStringField('province');
		$facilities->addStringField('country');
		$facilities->addStringField('ldap_code');
		$facilities->addStringField('latitude');
		$facilities->addStringField('longitude');
		$facilities->addStringField('phone');
		$facilities->addStringField('fax');
		$facilities->addStringField('website');
		$facilities->addStringField('email');
		$facilities->addStringField('notes');
		$facilities->addStringField('principal');
		$facilities->addStringField('vice');
		$facilities->addStringField('custodial_contact');
		$facilities->addStringField('supplies_account_no');
		$facilities->addDateTimeField('retired_at');
		$facilities->addDateTimeField('deleted_at');
		$facilities->addStringField('mident_no');
		$facilities->addStringField('sfis_no');
		$facilities->addStringField('supplies_flag');
		
		return $facilities;
	}
	
	protected function get_wo_jobs($alias = 'jobs')
	{
		$wo_jobs = new Table('wo_jobs', $alias);
		
		$wo_jobs->setPrimaryKey('id');
		$wo_jobs->addForeignKey('work_type_id');
		$wo_jobs->addForeignKey('service_group_id');
		$wo_jobs->addForeignKey('linked_wo_id');
		$wo_jobs->addStringField('subject');
		$wo_jobs->addStringField('description');
		$wo_jobs->addForeignKey('priority_id');
		$wo_jobs->addStringField('medium');
		$wo_jobs->addNumberField('assigned_to');
		$wo_jobs->addStringField('provider_type');
		$wo_jobs->addStringField('client_contact');
		$wo_jobs->addDateTimeField('planned_start');
		$wo_jobs->addDateTimeField('received_on');
		$wo_jobs->addForeignKey('work_order_id');
		$wo_jobs->addStringField('estimated_time');
		$wo_jobs->addDateTimeField('created_on');
		$wo_jobs->addDateTimeField('assigned_on');
		$wo_jobs->addDateTimeField('escalated_on');
		$wo_jobs->addDateTimeField('group_escalated_on');
		$wo_jobs->addDateField('planned_completion');
		$wo_jobs->addDateTimeField('completed_on');
		$wo_jobs->addDateTimeField('cancelled_on');
		$wo_jobs->addStringField('state');
		$wo_jobs->addBooleanField('is_asb_present');
		$wo_jobs->addBooleanField('has_asb_been_reviewed');
		$wo_jobs->addStringField('asb_reviewer_initials');
		$wo_jobs->addDateTimeField('asb_reviewed_on');
		$wo_jobs->addForeignKey('asb_reviewer_id');
		$wo_jobs->addNumberField('require_estimate');
		$wo_jobs->addStringField('po_number');
		$wo_jobs->addForeignKey('asset_id');
		$wo_jobs->addForeignKey('asset_grouping_id');
		$wo_jobs->addDateTimeField('employee_read_at');
		$wo_jobs->addBooleanField('packing_slip');
		$wo_jobs->addBooleanField('is_modified');
		$wo_jobs->addStringField('account_number');
		$wo_jobs->addDateTimeField('updated_on');
		$wo_jobs->addForeignKey('vfa_fs_type_id');
		$wo_jobs->addForeignKey('vfa_fs_year_id');
		$wo_jobs->addForeignKey('vfa_category_id');
		
		return $wo_jobs;
	}
	
	protected function get_wo_accounting_notes($alias = 'accounting_notes')
	{
		$wo_accounting_notes = new Table('wo_accounting_notes', $alias);
		
		$wo_accounting_notes->setPrimaryKey('id');
		$wo_accounting_notes->addForeignKey('accounting_note_type_id');
		$wo_accounting_notes->addStringField('note_type_name');
		$wo_accounting_notes->addForeignKey('vendor_id');
		$wo_accounting_notes->addDateTimeField('date');
		$wo_accounting_notes->addStringField('id_number');
		$wo_accounting_notes->addStringField('description');
		$wo_accounting_notes->addStringField('price');
		$wo_accounting_notes->addStringField('total');
		$wo_accounting_notes->addForeignKey('tax_code_id');
		$wo_accounting_notes->addStringField('tax_code');
		$wo_accounting_notes->addForeignKey('user_id');
		$wo_accounting_notes->addStringField('hours');
		$wo_accounting_notes->addDateTimeField('created_on');
		$wo_accounting_notes->addForeignKey('job_id');
		$wo_accounting_notes->addStringField('po_number');
		$wo_accounting_notes->addStringField('meta_data');
		$wo_accounting_notes->addDateTimeField('exported_on');
		$wo_accounting_notes->addForeignKey('purchase_order_id');
		$wo_accounting_notes->addForeignKey('approval_group_id');
		$wo_accounting_notes->addNumberField('approval_tier_index');
		$wo_accounting_notes->addDateTimeField('approved_on');
		$wo_accounting_notes->addDateTimeField('approval_denied_on');
		$wo_accounting_notes->addForeignKey('approver_id');
		
		return $wo_accounting_notes;
	}
	
	protected function get_wo_accounting_note_type($alias = 'accounting_note_type')
	{
		$wo_accounting_note_type = new Table('wo_accounting_note_type', $alias);
		
		$wo_accounting_note_type->setPrimaryKey('id');
		$wo_accounting_note_type->addForeignKey('vt_id');
		$wo_accounting_note_type->addStringField('name');
		$wo_accounting_note_type->addNumberField('sort_order');
		$wo_accounting_note_type->addStringField('note_type');
		$wo_accounting_note_type->addForeignKey('user_rate_id');
		$wo_accounting_note_type->addBooleanField('is_travel_time');
		$wo_accounting_note_type->addBooleanField('is_remote_support');
		$wo_accounting_note_type->addDateTimeField('deleted_at');
		
		return $wo_accounting_note_type;
	}
	
	protected function get_users($alias = 'users')
	{
		$users = new Table('users', $alias);
		
		$users->setPrimaryKey('id');
		$users->addStringField('username');
		$users->addStringField('password');
		$users->addForeignKey('organization_id');
		$users->addBooleanField('change_pass');
		$users->addStringField('user_role');
		$users->addStringField('first_name');
		$users->addStringField('last_name');
		$users->addStringField('email');
		$users->addForeignKey('user_title_id');
		$users->addStringField('employee_id');
		$users->addStringField('language');
		$users->addStringField('website');
		$users->addForeignKey('company_id');
		$users->addStringField('remember_key');
		$users->addStringField('reset_key');
		$users->addBooleanField('login_attempts');
		$users->addStringField('comments');
		$users->addDateTimeField('created_on');
		$users->addDateTimeField('last_login_on');
		$users->addBooleanField('disabled');
		$users->addBooleanField('ghost');
		$users->addBooleanField('employee');
		$users->addStringField('rate');
		$users->addBooleanField('vendor');
		$users->addBooleanField('service_provider');
		$users->addBooleanField('dispatcher');
		$users->addStringField('fax');
		$users->addStringField('user_prefs');
		$users->addStringField('phone');
		$users->addStringField('cellphone');
		$users->addStringField('special_rate');
		$users->addStringField('address');
		$users->addStringField('city');
		$users->addStringField('province');
		$users->addStringField('postal_code');
		$users->addNumberField('version');
		$users->addStringField('default_send_option');
		$users->addStringField('bas_vendor_number');
		$users->addDateTimeField('deleted_at');
		$users->addStringField('ldap_guid');
		$users->addStringField('ldap_dn');
		$users->addBooleanField('ldap_skip_profiles');
		$users->addBooleanField('ldap_skip_facilities');
		$users->addStringField('blackboard_id');
		
		return $users;
	}
	
	protected function get_wo_work_types($alias = 'work_types')
	{
		$wo_work_types = new Table('wo_work_types', $alias);
		
		$wo_work_types->setPrimaryKey('id');
		$wo_work_types->addForeignKey('vt_id');
		$wo_work_types->addForeignKey('parent_id');
		$wo_work_types->addForeignKey('type_id');
		$wo_work_types->addStringField('name');
		$wo_work_types->addNumberField('sort_order');
		$wo_work_types->addStringField('create_description');
		$wo_work_types->addStringField('code');
		$wo_work_types->addForeignKey('wo_priority_id');
		$wo_work_types->addNumberField('start_in');
		$wo_work_types->addStringField('gl_code');
		$wo_work_types->addBooleanField('is_asset_required');
		$wo_work_types->addStringField('service_group');
		$wo_work_types->addStringField('est_completion_time');
		$wo_work_types->addStringField('default_estimated_time');
		$wo_work_types->addForeignKey('DELETE_logs_submission_template_id');
		$wo_work_types->addStringField('DELETE_logs_submission_required_states');
		$wo_work_types->addDateTimeField('deleted_at');
		$wo_work_types->addStringField('logs_submission_required_state');
		$wo_work_types->addForeignKey('logs_submission_template_id');
		
		return $wo_work_types;
	}
	
	protected function get_wo_reasons($alias = 'reasons')
	{
		$wo_reasons = new Table('wo_reasons', $alias);
		
		$wo_reasons->setPrimaryKey('id');
		$wo_reasons->addForeignKey('vt_id');
		$wo_reasons->addStringField('name');
		$wo_reasons->addStringField('message_on_create');
		$wo_reasons->addStringField('message_on_create_importance');
		$wo_reasons->addNumberField('sort_order');
		$wo_reasons->addDateTimeField('deleted_at');
		
		return $wo_reasons;
	}
	
	protected function get_facility_areas($alias = 'facility_areas')
	{
		$facility_areas = new Table('facility_areas', $alias);
		
		$facility_areas->setPrimaryKey('id');
		$facility_areas->addForeignKey('facility_id');
		$facility_areas->addStringField('name');
		$facility_areas->addStringField('remote_url');
		$facility_areas->addStringField('details');
		
		return $facility_areas;
	}
	
	protected function get_wo_priorities($alias = 'priorities')
	{
		$wo_priorities = new Table('wo_priorities', $alias);
		
		$wo_priorities->setPrimaryKey('id');
		$wo_priorities->addForeignKey('vt_id');
		$wo_priorities->addStringField('name');
		$wo_priorities->addNumberField('sort_order');
		$wo_priorities->addNumberField('priority');
		$wo_priorities->addNumberField('days_to_escalate');
		$wo_priorities->addForeignKey('escalate_to_id');
		$wo_priorities->addStringField('start_in');
		$wo_priorities->addStringField('colour');
		$wo_priorities->addDateTimeField('deleted_at');
		
		return $wo_priorities;
	}
	
	protected function get_wo_response($alias = 'response')
	{
		$wo_response = new Table('wo_response', $alias);
		
		$wo_response->setPrimaryKey('id');
		$wo_response->addForeignKey('vt_id');
		$wo_response->addForeignKey('parent_id');
		$wo_response->addStringField('message');
		$wo_response->addDateTimeField('deleted_at');
		
		return $wo_response;
	}
	
	protected function get_facility_rooms($alias = 'facility_rooms')
	{
		$facility_rooms = new Table('facility_rooms', $alias);
		
		$facility_rooms->setPrimaryKey('id');
		$facility_rooms->addForeignKey('room_type_id');
		$facility_rooms->addForeignKey('facility_id');
		$facility_rooms->addForeignKey('area_id');
		$facility_rooms->addStringField('room_no');
		$facility_rooms->addStringField('room_name');
		$facility_rooms->addStringField('details');
		$facility_rooms->addForeignKey('addition_id');
		$facility_rooms->addDateTimeField('retired_at');
		
		return $facility_rooms;
	}
	
	protected function get_wo_service_group($alias = 'service_group')
	{
		$wo_service_group = new Table('wo_service_group', $alias);
		
		$wo_service_group->setPrimaryKey('id');
		$wo_service_group->addForeignKey('vt_id');
		$wo_service_group->addStringField('name');
		$wo_service_group->addStringField('description');
		$wo_service_group->addStringField('next_group');
		$wo_service_group->addNumberField('escalation_wait_time');
		$wo_service_group->addBooleanField('is_disabled');
		$wo_service_group->addBooleanField('is_contractor_only');
		$wo_service_group->addNumberField('DEPRECATED_can_create_maintenance_wo');
		$wo_service_group->addNumberField('can_self_escalate');
		$wo_service_group->addNumberField('allow_open_to_group');
		$wo_service_group->addNumberField('allow_take');
		$wo_service_group->addNumberField('show_assigned_in_my_status_group');
		$wo_service_group->addForeignKey('next_group_id');
		
		return $wo_service_group;
	}
	
	protected function get_wo_part_note($alias = 'part_note')
	{
		$wo_part_note = new Table('wo_part_note', $alias);
		
		$wo_part_note->setPrimaryKey('id');
		$wo_part_note->addForeignKey('job_id');
		$wo_part_note->addForeignKey('user_id');
		$wo_part_note->addForeignKey('vendor_id');
		$wo_part_note->addForeignKey('part_note_item_id');
		$wo_part_note->addStringField('sku');
		$wo_part_note->addStringField('description');
		$wo_part_note->addDateField('date');
		$wo_part_note->addStringField('stock');
		$wo_part_note->addBooleanField('shipped');
		$wo_part_note->addBooleanField('backordered');
		$wo_part_note->addNumberField('qty');
		$wo_part_note->addStringField('list_price');
		$wo_part_note->addStringField('unit_price');
		$wo_part_note->addStringField('subtotal');
		$wo_part_note->addStringField('total');
		$wo_part_note->addForeignKey('tax_code_id');
		$wo_part_note->addStringField('tax_code');
		$wo_part_note->addDateTimeField('created_on');
		
		return $wo_part_note;
	}
	
}
