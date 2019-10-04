<?php

namespace App\Domain\Reports;

use App\Reporting\AbstractReport;
use App\Reporting\Resources\ModuleConfiguration;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\Relationship;
use App\Reporting\Resources\Condition;
use App\Reporting\Filters\Constraints\Equals;

abstract class WorkOrdersBaseReport extends AbstractReport
{
	protected function getModuleConfiguration()
	{
		$wo_orders = $this->get_wo_orders('work_orders');
		$events = $this->get_events('ev');
		$wo_types = $this->get_wo_types();
		$facilities = $this->get_facilities('facility');
		$wo_jobs = $this->get_wo_jobs();
		$wo_accounting_notes = $this->get_wo_accounting_notes();
		$wo_accounting_note_type = $this->get_wo_accounting_note_type();
		$service_providers = $this->get_users('service_providers');
		$dispatchers = $this->get_users('dispatchers');
		$wo_work_types = $this->get_wo_work_types();
		$wo_reasons = $this->get_wo_reasons();
		$facility_areas = $this->get_facility_areas('area');
		$wo_priorities = $this->get_wo_priorities();
		$child_response = $this->get_wo_response('child_response');
		$parent_response = $this->get_wo_response('parent_response');
		$facility_rooms = $this->get_facility_rooms('room');
		$wo_service_group = $this->get_wo_service_group();
		$wo_part_note = $this->get_wo_part_note();

		new Relationship($wo_types, $wo_orders, [Condition::equals($wo_orders->dbField('type_id'), [$wo_types->primary_key()])]);
		new Relationship($wo_reasons, $wo_orders, [Condition::equals($wo_orders->dbField('reason_id'), [$wo_reasons->primary_key()])]);
		new Relationship($facilities, $wo_orders, [Condition::equals($wo_orders->dbField('facility_id'), [$facilities->primary_key()])]);
		new Relationship($facility_areas, $wo_orders, [new Condition($wo_orders->dbField('facility_area_id'), new Equals(), [$facility_areas->primary_key()])]);
		new Relationship($facility_rooms, $wo_orders, [new Condition($wo_orders->dbField('facility_room_id'), new Equals(), [$facility_rooms->primary_key()])]);
		new Relationship($dispatchers, $wo_orders, [new Condition($wo_orders->dbField('dispatcher_id'), new Equals(), [$dispatchers->primary_key()])]);
		new Relationship($child_response, $wo_orders, [new Condition($wo_orders->dbField('completed_response_id'), new Equals(), [$child_response->primary_key()])]);
		new Relationship($parent_response, $child_response, [new Condition($child_response->dbField('parent_id'), new Equals(), [$parent_response->primary_key()])]);


		new Relationship($wo_orders, $wo_jobs, [new Condition($wo_jobs->dbField('work_order_id'), new Equals(), [$wo_orders->primary_key()])]);
		new Relationship($wo_work_types, $wo_jobs, [new Condition($wo_jobs->dbField('work_type_id'), new Equals(), [$wo_work_types->primary_key()])]);
		new Relationship($service_providers, $wo_jobs, [new Condition($wo_jobs->dbField('assigned_to'), new Equals(), [$service_providers->primary_key()])]);
		new Relationship($wo_priorities, $wo_jobs, [new Condition($wo_jobs->dbField('priority_id'), new Equals(), [$wo_priorities->primary_key()])]);
		new Relationship($wo_service_group, $wo_jobs, [new Condition($wo_jobs->dbField('service_group_id'), new Equals(), [$wo_service_group->primary_key()])]);


		new Relationship($wo_jobs, $wo_accounting_notes, [new Condition($wo_accounting_notes->dbField('job_id'), new Equals(), [$wo_jobs->primary_key()])]);
		new Relationship($wo_accounting_note_type, $wo_accounting_notes, [new Condition($wo_accounting_notes->dbField('accounting_note_type_id'), new Equals(), [$wo_accounting_note_type->primary_key()])]);

		new Relationship($wo_jobs, $wo_part_note, [new Condition($wo_part_note->dbField('job_id'), new Equals(), [$wo_jobs->primary_key()])]);

		new Relationship($wo_orders, $events, [
			new Condition($events->dbField('ref_id'), new Equals(), [$wo_orders->primary_key()]),
			new Condition($events->dbField('ref_type'), new Equals(), ['Wo::WorkOrder']),
			new Condition($events->dbField('event'), new Equals(), ['comment']),
		]);



		return new ModuleConfiguration([
			$wo_orders,
			$events,
			$wo_types,
			$facilities,
			$wo_jobs,
			$wo_accounting_notes,
			$wo_accounting_note_type,
			$service_providers,
			$dispatchers,
			$wo_work_types,
			$wo_reasons,
			$facility_areas,
			$wo_priorities,
			$child_response,
			$parent_response,
			$facility_rooms,
			$wo_service_group,
			$wo_part_note,
		]);
	}

	protected function get_wo_orders($alias = 'wo_orders')
	{
		return Table::builder('wo_orders')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('vt_id')
			->addForeignKey('facility_id')
			->addForeignKey('user_id')
			->addForeignKey('type_id')
			->addForeignKey('reason_id')
			->addNumberField('number')
			->addNumberField('sch_number')
			->addStringField('origin')
			->addForeignKey('origin_id')
			->addStringField('state')
			->addForeignKey('dispatcher_id')
			->addForeignKey('dispatcher_viewable_id')
			->addStringField('requested_by')
			->addStringField('availability')
			->addForeignKey('facility_department_id')
			->addForeignKey('facility_area_id')
			->addForeignKey('facility_room_id')
			->addStringField('facility_room_type')
			->addStringField('plant_room_no')
			->addStringField('location_description')
			->addDateTimeField('submitted_on')
			->addDateTimeField('issued')
			->addDateTimeField('completed_on')
			->addDateTimeField('closed_on')
			->addDateTimeField('cancelled_on')
			->addDateTimeField('last_activity_on')
			->addForeignKey('completed_response_id')
			->addStringField('emergency')
			->addStringField('equipment_no')
			->addStringField('schedule')
			->addStringField('schedule_label')
			->addBooleanField('schedule_enabled')
			->addDateTimeField('schedule_prev_run')
			->addDateTimeField('schedule_next_run')
			->addDateField('schedule_last_day')
			->addNumberField('schedule_self_destruct')
			->addStringField('reminder_state')
			->addDateTimeField('reminder_set_on')
			->addDateTimeField('reminder_last_notification')
			->addDateTimeField('reminder_date')
			->addNumberField('reminder_restore_state')
			->addForeignKey('reminder_user_id')
			->addForeignKey('inspection_id')
			->addStringField('estimate')
			->addStringField('account_number')
			->addStringField('it_work_order_number')
			->addBooleanField('approve_immediately')
			->addDateTimeField('is_estimate_required')
			->addForeignKey('year_id')
			->addBooleanField('job_complete')
			->addNumberField('job_score_number')
			->addStringField('job_score_name')
			->addBooleanField('contractor_verifies_job_completed')
			->addDateField('job_completed_on')
			->addStringField('job_completed_by')
			->addStringField('job_completed_hours_worked')
			->addStringField('job_verification_message')
			->addBooleanField('is_archived')
			->addForeignKey('client_id')
			->addStringField('misc')
			->addStringField('requirements_progress')
			->addStringField('schedule_locations')
			->build();
	}

	protected function get_events($alias = 'events')
	{
		return Table::builder('events')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addStringField('ref_type')
			->addForeignKey('ref_id')
			->addForeignKey('parent_id')
			->addStringField('event')
			->addStringField('event_source')
			->addStringField('event_target')
			->addForeignKey('facility_id')
			->addForeignKey('user_id')
			->addStringField('message')
			->addBooleanField('is_user_comment')
			->addDateTimeField('created_on')
			->addDateTimeField('deleted_at')
			->build();
	}

	protected function get_wo_types($alias = 'class')
	{
		return Table::builder('wo_types')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('vt_id')
			->addStringField('name')
			->addNumberField('budget_type')
			->addStringField('gl_code')
			->addForeignKey('default_reason_id')
			->addNumberField('sort_order')
			->addDateTimeField('deleted_at')
			->addStringField('logs_submission_required_state')
			->addForeignKey('logs_submission_template_id')
			->build();
	}

	protected function get_facilities($alias = 'facilities')
	{
		return Table::builder('facilities')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addStringField('name')
			->addStringField('facility_number')
			->addStringField('address')
			->addStringField('city')
			->addStringField('postal')
			->addStringField('province')
			->addStringField('country')
			->addStringField('ldap_code')
			->addStringField('latitude')
			->addStringField('longitude')
			->addStringField('phone')
			->addStringField('fax')
			->addStringField('website')
			->addStringField('email')
			->addStringField('notes')
			->addStringField('principal')
			->addStringField('vice')
			->addStringField('custodial_contact')
			->addStringField('supplies_account_no')
			->addDateTimeField('retired_at')
			->addDateTimeField('deleted_at')
			->addStringField('mident_no')
			->addStringField('sfis_no')
			->addStringField('supplies_flag')
			->build();
	}

	protected function get_wo_jobs($alias = 'wo_jobs')
	{
		return Table::builder('wo_jobs')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('work_type_id')
			->addForeignKey('service_group_id')
			->addForeignKey('linked_wo_id')
			->addStringField('subject')
			->addStringField('description')
			->addForeignKey('priority_id')
			->addStringField('medium')
			->addNumberField('assigned_to')
			->addStringField('provider_type')
			->addStringField('client_contact')
			->addDateTimeField('planned_start')
			->addDateTimeField('received_on')
			->addForeignKey('work_order_id')
			->addStringField('estimated_time')
			->addDateTimeField('created_on')
			->addDateTimeField('assigned_on')
			->addDateTimeField('escalated_on')
			->addDateTimeField('group_escalated_on')
			->addDateField('planned_completion')
			->addDateTimeField('completed_on')
			->addDateTimeField('cancelled_on')
			->addStringField('state')
			->addBooleanField('is_asb_present')
			->addBooleanField('has_asb_been_reviewed')
			->addStringField('asb_reviewer_initials')
			->addDateTimeField('asb_reviewed_on')
			->addForeignKey('asb_reviewer_id')
			->addNumberField('require_estimate')
			->addStringField('po_number')
			->addForeignKey('asset_id')
			->addForeignKey('asset_grouping_id')
			->addDateTimeField('employee_read_at')
			->addBooleanField('packing_slip')
			->addBooleanField('is_modified')
			->addStringField('account_number')
			->addDateTimeField('updated_on')
			->addForeignKey('vfa_fs_type_id')
			->addForeignKey('vfa_fs_year_id')
			->addForeignKey('vfa_category_id')
			->build();
	}

	protected function get_wo_accounting_notes($alias = 'wo_accounting_notes')
	{
		return Table::builder('wo_accounting_notes')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('accounting_note_type_id')
			->addStringField('note_type_name')
			->addForeignKey('vendor_id')
			->addDateTimeField('date')
			->addStringField('id_number')
			->addStringField('description')
			->addNumberField('price')
			->addNumberField('total')
			->addForeignKey('tax_code_id')
			->addStringField('tax_code')
			->addForeignKey('user_id')
			->addNumberField('hours')
			->addDateTimeField('created_on')
			->addForeignKey('job_id')
			->addStringField('po_number')
			->addStringField('meta_data')
			->addDateTimeField('exported_on')
			->addForeignKey('purchase_order_id')
			->addForeignKey('approval_group_id')
			->addNumberField('approval_tier_index')
			->addDateTimeField('approved_on')
			->addDateTimeField('approval_denied_on')
			->addForeignKey('approver_id')
			->build();
	}

	protected function get_wo_accounting_note_type($alias = 'wo_accounting_note_type')
	{
		return Table::builder('wo_accounting_note_type')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('vt_id')
			->addStringField('name')
			->addNumberField('sort_order')
			->addStringField('note_type')
			->addForeignKey('user_rate_id')
			->addBooleanField('is_travel_time')
			->addBooleanField('is_remote_support')
			->addDateTimeField('deleted_at')
			->build();
	}

	protected function get_users($alias = 'users')
	{
		return Table::builder('users')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addStringField('username')
			->addStringField('password')
			->addForeignKey('organization_id')
			->addBooleanField('change_pass')
			->addStringField('user_role')
			->addStringField('first_name')
			->addStringField('last_name')
			->addStringField('email')
			->addForeignKey('user_title_id')
			->addStringField('employee_id')
			->addStringField('language')
			->addStringField('website')
			->addForeignKey('company_id')
			->addStringField('remember_key')
			->addStringField('reset_key')
			->addBooleanField('login_attempts')
			->addStringField('comments')
			->addDateTimeField('created_on')
			->addDateTimeField('last_login_on')
			->addBooleanField('disabled')
			->addBooleanField('ghost')
			->addBooleanField('employee')
			->addStringField('rate')
			->addBooleanField('vendor')
			->addBooleanField('service_provider')
			->addBooleanField('dispatcher')
			->addStringField('fax')
			->addStringField('user_prefs')
			->addStringField('phone')
			->addStringField('cellphone')
			->addStringField('special_rate')
			->addStringField('address')
			->addStringField('city')
			->addStringField('province')
			->addStringField('postal_code')
			->addNumberField('version')
			->addStringField('default_send_option')
			->addStringField('bas_vendor_number')
			->addDateTimeField('deleted_at')
			->addStringField('ldap_guid')
			->addStringField('ldap_dn')
			->addBooleanField('ldap_skip_profiles')
			->addBooleanField('ldap_skip_facilities')
			->addStringField('blackboard_id')
			->build();
	}

	protected function get_wo_work_types($alias = 'wo_work_types')
	{
		return Table::builder('wo_work_types')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('vt_id')
			->addForeignKey('parent_id')
			->addForeignKey('type_id')
			->addStringField('name')
			->addNumberField('sort_order')
			->addStringField('create_description')
			->addStringField('code')
			->addForeignKey('wo_priority_id')
			->addNumberField('start_in')
			->addStringField('gl_code')
			->addBooleanField('is_asset_required')
			->addStringField('service_group')
			->addStringField('est_completion_time')
			->addStringField('default_estimated_time')
			->addDateTimeField('deleted_at')
			->addStringField('logs_submission_required_state')
			->addForeignKey('logs_submission_template_id')
			->build();
	}

	protected function get_wo_reasons($alias = 'reason')
	{
		return Table::builder('wo_reasons')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('vt_id')
			->addStringField('name')
			->addStringField('message_on_create')
			->addStringField('message_on_create_importance')
			->addNumberField('sort_order')
			->addDateTimeField('deleted_at')
			->build();
	}

	protected function get_facility_areas($alias = 'facility_areas')
	{
		return Table::builder('facility_areas')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('facility_id')
			->addStringField('name')
			->addStringField('remote_url')
			->addStringField('details')
			->build();
	}

	protected function get_wo_priorities($alias = 'wo_priorities')
	{
		return Table::builder('wo_priorities')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('vt_id')
			->addStringField('name')
			->addNumberField('sort_order')
			->addNumberField('priority')
			->addNumberField('days_to_escalate')
			->addForeignKey('escalate_to_id')
			->addStringField('start_in')
			->addStringField('colour')
			->addDateTimeField('deleted_at')
			->build();
	}

	protected function get_wo_response($alias = 'wo_response')
	{
		return Table::builder('wo_response')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('vt_id')
			->addForeignKey('parent_id')
			->addStringField('message')
			->addDateTimeField('deleted_at')
			->build();
	}

	protected function get_facility_rooms($alias = 'facility_rooms')
	{
		return Table::builder('facility_rooms')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('room_type_id')
			->addForeignKey('facility_id')
			->addForeignKey('area_id')
			->addStringField('room_no')
			->addStringField('room_name')
			->addStringField('details')
			->addForeignKey('addition_id')
			->addDateTimeField('retired_at')
			->build();
	}

	protected function get_wo_service_group($alias = 'wo_service_group')
	{
		return Table::builder('wo_service_group')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('vt_id')
			->addStringField('name')
			->addStringField('description')
			->addStringField('next_group')
			->addNumberField('escalation_wait_time')
			->addBooleanField('is_disabled')
			->addBooleanField('is_contractor_only')
			->addNumberField('DEPRECATED_can_create_maintenance_wo')
			->addNumberField('can_self_escalate')
			->addNumberField('allow_open_to_group')
			->addNumberField('allow_take')
			->addNumberField('show_assigned_in_my_status_group')
			->addForeignKey('next_group_id')
			->build();
	}

	protected function get_wo_part_note($alias = 'wo_part_note')
	{
		return Table::builder('wo_part_note')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addForeignKey('job_id')
			->addForeignKey('user_id')
			->addForeignKey('vendor_id')
			->addForeignKey('part_note_item_id')
			->addStringField('sku')
			->addStringField('description')
			->addDateField('date')
			->addStringField('stock')
			->addBooleanField('shipped')
			->addBooleanField('backordered')
			->addNumberField('qty')
			->addStringField('list_price')
			->addStringField('unit_price')
			->addStringField('subtotal')
			->addStringField('total')
			->addForeignKey('tax_code_id')
			->addStringField('tax_code')
			->addDateTimeField('created_on')
			->build();
	}
}
