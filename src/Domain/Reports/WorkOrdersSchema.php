<?php

namespace App\Domain\Reports;

use App\Reporting\Resources\Schema;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Resources\TableName;

trait WorkOrdersSchema
{
	protected function getSchema()
	{
		$wo_orders = $this->get_wo_orders('work_orders');
		$events = $this->get_events('ev');
		$wo_types = $this->get_wo_types('class');
		$facilities = $this->get_facilities('facility');
		$wo_jobs = $this->get_wo_jobs('jobs');
		$wo_accounting_notes = $this->get_wo_accounting_notes('accounting_notes');
		$wo_accounting_note_type = $this->get_wo_accounting_note_type('accounting_note_types');
		$service_providers = $this->get_users('service_provider');
		$dispatchers = $this->get_users('dispatcher');
		$wo_work_types = $this->get_wo_work_types('work_types');
		$wo_reasons = $this->get_wo_reasons('reason');
		$facility_areas = $this->get_facility_areas('area');
		$wo_priorities = $this->get_wo_priorities('priority');
		$child_response = $this->get_wo_response('child_response');
		$parent_response = $this->get_wo_response('parent_response');
		$facility_rooms = $this->get_facility_rooms('room');
		$wo_service_group = $this->get_wo_service_group();
		$wo_part_note = $this->get_wo_part_note();

//		new Relationship($wo_types, $wo_orders, [new Condition($wo_orders->dbField('type_id'), new Equals(), [$wo_types->primary_key()])]);
//		new Relationship($wo_reasons, $wo_orders, [new Condition($wo_orders->dbField('reason_id'), new Equals(), [$wo_reasons->primary_key()])]);
//		new Relationship($facilities, $wo_orders, [new Condition($wo_orders->dbField('facility_id'), new Equals(), [$facilities->primary_key()])]);
//		new Relationship($facility_areas, $wo_orders, [new Condition($wo_orders->dbField('facility_area_id'), new Equals(), [$facility_areas->primary_key()])]);
//		new Relationship($facility_rooms, $wo_orders, [new Condition($wo_orders->dbField('facility_room_id'), new Equals(), [$facility_rooms->primary_key()])]);
//		new Relationship($dispatchers, $wo_orders, [new Condition($wo_orders->dbField('dispatcher_id'), new Equals(), [$dispatchers->primary_key()])]);
//		new Relationship($child_response, $wo_orders, [new Condition($wo_orders->dbField('completed_response_id'), new Equals(), [$child_response->primary_key()])]);
//		new Relationship($parent_response, $child_response, [new Condition($child_response->dbField('parent_id'), new Equals(), [$parent_response->primary_key()])]);
//
//
//		new Relationship($wo_orders, $wo_jobs, [new Condition($wo_jobs->dbField('work_order_id'), new Equals(), [$wo_orders->primary_key()])]);
//		new Relationship($wo_work_types, $wo_jobs, [new Condition($wo_jobs->dbField('work_type_id'), new Equals(), [$wo_work_types->primary_key()])]);
//		new Relationship($service_providers, $wo_jobs, [new Condition($wo_jobs->dbField('assigned_to'), new Equals(), [$service_providers->primary_key()])]);
//		new Relationship($wo_priorities, $wo_jobs, [new Condition($wo_jobs->dbField('priority_id'), new Equals(), [$wo_priorities->primary_key()])]);
//		new Relationship($wo_service_group, $wo_jobs, [new Condition($wo_jobs->dbField('service_group_id'), new Equals(), [$wo_service_group->primary_key()])]);
//
//
//		new Relationship($wo_jobs, $wo_accounting_notes, [new Condition($wo_accounting_notes->dbField('job_id'), new Equals(), [$wo_jobs->primary_key()])]);
//		new Relationship($wo_accounting_note_type, $wo_accounting_notes, [new Condition($wo_accounting_notes->dbField('accounting_note_type_id'), new Equals(), [$wo_accounting_note_type->primary_key()])]);
//
//		new Relationship($wo_jobs, $wo_part_note, [new Condition($wo_part_note->dbField('job_id'), new Equals(), [$wo_jobs->primary_key()])]);
//
//		new Relationship($wo_orders, $events, [
//			new Condition($events->dbField('ref_id'), new Equals(), [$wo_orders->primary_key()]),
//			new Condition($events->dbField('ref_type'), new Equals(), ['Wo::WorkOrder']),
//			new Condition($events->dbField('event'), new Equals(), ['comment']),
//		]);

		return new Schema(TableCollection::fromArray([
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
		]), []);
	}

	protected function get_wo_orders($alias = 'wo_orders')
	{
		$wo_orders = Table::builder('wo_orders')
			->setAlias($alias)
			->setPrimaryKey('id')
//			->addManyToOneRelationship('vt_id')
			->addManyToOneRelationship(new TableName('facilities', 'facility'), 'work_orders.facility_id = facility.id', 'facility_id')
//			->addManyToOneRelationship(new TableName('users', 'author'), '', 'user_id')
			->addManyToOneRelationship(new TableName('wo_types', 'class'), 'work_orders.type_id = class.id', 'type_id')
			->addManyToOneRelationship(new TableName('wo_reasons', 'reason'), 'work_orders.reason_id = reason.id', 'reason_id')
			->addNumberField('number')
			->addNumberField('sch_number')
			->addStringField('origin')
//			->addManyToOneRelationship('origin_id')
			->addStringField('state')
//			->addManyToOneRelationship('dispatcher_id')
			->addManyToOneRelationship(new TableName('users', 'dispatcher'), 'work_orders.dispatcher_id = dispatcher.id', 'dispatcher_id')
//			->addManyToOneRelationship('dispatcher_viewable_id')
			->addStringField('requested_by')
			->addStringField('availability')
//			->addManyToOneRelationship('facility_department_id')
//			->addManyToOneRelationship('facility_area_id')
//			->addManyToOneRelationship('facility_room_id')
			->addStringField('facility_room_type')
			->addStringField('plant_room_no')
			->addStringField('location_description')
			->addDateTimeField('submitted_on')
			->addDateTimeField('issued')
			->addDateTimeField('completed_on')
			->addDateTimeField('closed_on')
			->addDateTimeField('cancelled_on')
			->addDateTimeField('last_activity_on')
//			->addManyToOneRelationship('completed_response_id')
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
			//->addManyToOneRelationship('reminder_user_id')
			//->addManyToOneRelationship('inspection_id')
			->addStringField('estimate')
			->addStringField('account_number')
			->addStringField('it_work_order_number')
			->addBooleanField('approve_immediately')
			->addDateTimeField('is_estimate_required')
			//->addManyToOneRelationship('year_id')
			->addBooleanField('job_complete')
			->addNumberField('job_score_number')
			->addStringField('job_score_name')
			->addBooleanField('contractor_verifies_job_completed')
			->addDateField('job_completed_on')
			->addStringField('job_completed_by')
			->addStringField('job_completed_hours_worked')
			->addStringField('job_verification_message')
			->addBooleanField('is_archived')
			//->addManyToOneRelationship('client_id')
			->addStringField('misc')
			->addStringField('requirements_progress')
			->addStringField('schedule_locations')
			->build();

		return $wo_orders;
	}

	protected function get_events($alias = 'events')
	{
		$events = Table::builder('events')
			->setAlias($alias)
			->setPrimaryKey('id')
			->addStringField('ref_type')
			//->addManyToOneRelationship('ref_id')
			//->addManyToOneRelationship('parent_id')
			->addStringField('event')
			->addStringField('event_source')
			->addStringField('event_target')
			//->addManyToOneRelationship('facility_id')
			//->addManyToOneRelationship('user_id')
			->addStringField('message')
			->addBooleanField('is_user_comment')
			->addDateTimeField('created_on')
//			->addDateTimeField('deleted_at')
			->build();

		return $events;
	}

	protected function get_wo_types($alias = 'class')
	{
		$wo_types = Table::builder('wo_types')->setAlias($alias)
			->setPrimaryKey('id')
			//->addManyToOneRelationship('vt_id')
			->addStringField('name')
			->addNumberField('budget_type')
			->addStringField('gl_code')
			//->addManyToOneRelationship('default_reason_id')
			->addNumberField('sort_order')
//			->addDateTimeField('deleted_at')
			->addStringField('logs_submission_required_state')
			//->addManyToOneRelationship('logs_submission_template_id');
			->build();

		return $wo_types;
	}

	protected function get_facilities($alias = 'facilities')
	{
		$facilities = Table::builder('facilities')->setAlias($alias)
//			->addOneToManyRelationship(new TableName('wo_orders', 'work_orders'), 'work_orders.facility_id = facility.id')
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
//			->addDateTimeField('deleted_at')
			->addStringField('mident_no')
			->addStringField('sfis_no')
			->addStringField('supplies_flag')
			->build();

		return $facilities;
	}

	protected function get_wo_jobs($alias = 'jobs')
	{
		$wo_jobs = Table::builder('wo_jobs')->setAlias($alias)
			->setPrimaryKey('id')
			->addManyToOneRelationship(new TableName('wo_orders', 'work_orders'), 'jobs.work_order_id = work_orders.id', 'work_order_id')
			->addManyToOneRelationship(new TableName('wo_work_types', 'work_types'), 'jobs.work_type_id = work_types.id', 'work_type_id')
			//->addManyToOneRelationship('service_group_id')
			//->addManyToOneRelationship('linked_wo_id')
			->addStringField('subject')
			->addStringField('description')
			//->addManyToOneRelationship('priority_id')
			->addStringField('medium')
			->addNumberField('assigned_to')
			->addStringField('provider_type')
			->addStringField('client_contact')
			->addDateTimeField('planned_start')
			->addDateTimeField('received_on')
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
			//->addManyToOneRelationship('asb_reviewer_id')
			->addNumberField('require_estimate')
			->addStringField('po_number')
			//->addManyToOneRelationship('asset_id')
			//->addManyToOneRelationship('asset_grouping_id')
			->addDateTimeField('employee_read_at')
			->addBooleanField('packing_slip')
			->addBooleanField('is_modified')
			->addStringField('account_number')
			->addDateTimeField('updated_on')
			//->addManyToOneRelationship('vfa_fs_type_id')
			//->addManyToOneRelationship('vfa_fs_year_id')
			//->addManyToOneRelationship('vfa_category_id')
			->build();

		return $wo_jobs;
	}

	protected function get_wo_accounting_notes($alias = 'wo_accounting_notes')
	{
		$wo_accounting_notes = Table::builder('wo_accounting_notes')->setAlias($alias)
			->setPrimaryKey('id')
			->addManyToOneRelationship(new TableName('wo_jobs', 'jobs'), $alias.'.job_id = jobs.id', 'job_id')
			->addManyToOneRelationship(new TableName('wo_accounting_note_type', 'accounting_note_types'), $alias.'.accounting_note_type_id = accounting_note_types.id', 'accounting_note_type_id')
			//->addManyToOneRelationship('accounting_note_type_id')
			->addStringField('note_type_name')
			//->addManyToOneRelationship('vendor_id')
			->addDateTimeField('date')
			->addStringField('id_number')
			->addStringField('description')
			->addNumberField('price')
			->addNumberField('total')
			//->addManyToOneRelationship('tax_code_id')
			->addStringField('tax_code')
			//->addManyToOneRelationship('user_id')
			->addNumberField('hours')
			->addDateTimeField('created_on')
			->addStringField('po_number')
			->addStringField('meta_data')
			->addDateTimeField('exported_on')
			//->addManyToOneRelationship('purchase_order_id')
			//->addManyToOneRelationship('approval_group_id')
			->addNumberField('approval_tier_index')
			->addDateTimeField('approved_on')
			->addDateTimeField('approval_denied_on')
			//->addManyToOneRelationship('approver_id')
			->build();

		return $wo_accounting_notes;
	}

	protected function get_wo_accounting_note_type($alias = 'wo_accounting_note_type')
	{
		$wo_accounting_note_type = Table::builder('wo_accounting_note_type')->setAlias($alias)
			->setPrimaryKey('id')
			//->addManyToOneRelationship('vt_id')
			->addStringField('name')
			->addNumberField('sort_order')
			->addStringField('note_type')
			//->addManyToOneRelationship('user_rate_id')
			->addBooleanField('is_travel_time')
			->addBooleanField('is_remote_support')
//			->addDateTimeField('deleted_at')
			->build();

		return $wo_accounting_note_type;
	}

	protected function get_users($alias = 'users')
	{
		$users = Table::builder('users')->setAlias($alias)
			->setPrimaryKey('id')
			->addStringField('username')
			//->addManyToOneRelationship('organization_id')
			->addStringField('first_name')
			->addStringField('last_name')
			->addStringField('email')
			->addStringField('employee_id')
			->build();

		return $users;
	}

	protected function get_wo_work_types($alias = 'wo_work_types')
	{
		$wo_work_types = Table::builder('wo_work_types')->setAlias($alias)
			->setPrimaryKey('id')
			//->addManyToOneRelationship('vt_id')
			//->addManyToOneRelationship('parent_id')
			//->addManyToOneRelationship('type_id')
			->addStringField('name')
			->addNumberField('sort_order')
			->addStringField('create_description')
			->addStringField('code')
			//->addManyToOneRelationship('wo_priority_id')
			->addNumberField('start_in')
			->addStringField('gl_code')
			->addBooleanField('is_asset_required')
			->addStringField('service_group')
			->addStringField('est_completion_time')
			->addStringField('default_estimated_time')
//			->addDateTimeField('deleted_at')
			->addStringField('logs_submission_required_state')
			//->addManyToOneRelationship('logs_submission_template_id')
			->build();

		return $wo_work_types;
	}

	protected function get_wo_reasons($alias = 'reason')
	{
		$wo_reasons = Table::builder('wo_reasons')->setAlias($alias)
			->setPrimaryKey('id')
			//->addManyToOneRelationship('vt_id')
			->addStringField('name')
			->addStringField('message_on_create')
			->addStringField('message_on_create_importance')
			->addNumberField('sort_order')
//			->addDateTimeField('deleted_at')
			->build();

		return $wo_reasons;
	}

	protected function get_facility_areas($alias = 'facility_areas')
	{
		$facility_areas = Table::builder('facility_areas')->setAlias($alias)
			->setPrimaryKey('id')
			//->addManyToOneRelationship('facility_id')
			->addStringField('name')
			->addStringField('remote_url')
			->addStringField('details')
			->build();

		return $facility_areas;
	}

	protected function get_wo_priorities($alias = 'wo_priorities')
	{
		$wo_priorities = Table::builder('wo_priorities')->setAlias($alias)
			->setPrimaryKey('id')
			//->addManyToOneRelationship('vt_id')
			->addStringField('name')
			->addNumberField('sort_order')
			->addNumberField('priority')
			->addNumberField('days_to_escalate')
			//->addManyToOneRelationship('escalate_to_id')
			->addStringField('start_in')
			->addStringField('colour')
//			->addDateTimeField('deleted_at')
			->build();

		return $wo_priorities;
	}

	protected function get_wo_response($alias = 'wo_response')
	{
		$wo_response = Table::builder('wo_response')->setAlias($alias)
			->setPrimaryKey('id')
			//->addManyToOneRelationship('vt_id')
			//->addManyToOneRelationship('parent_id')
			->addStringField('message')
//			->addDateTimeField('deleted_at')
			->build();

		return $wo_response;
	}

	protected function get_facility_rooms($alias = 'facility_rooms')
	{
		$facility_rooms = Table::builder('facility_rooms')->setAlias($alias)
			->setPrimaryKey('id')
			//->addManyToOneRelationship('room_type_id')
			//->addManyToOneRelationship('facility_id')
			//->addManyToOneRelationship('area_id')
			->addStringField('room_no')
			->addStringField('room_name')
			->addStringField('details')
			//->addManyToOneRelationship('addition_id')
			->addDateTimeField('retired_at')
			->build();

		return $facility_rooms;
	}

	protected function get_wo_service_group($alias = 'wo_service_group')
	{
		$wo_service_group = Table::builder('wo_service_group')->setAlias($alias)
			->setPrimaryKey('id')
			//->addManyToOneRelationship('vt_id')
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
			//->addManyToOneRelationship('next_group_id')
			->build();

		return $wo_service_group;
	}

	protected function get_wo_part_note($alias = 'wo_part_note')
	{
		$wo_part_note = Table::builder('wo_part_note')->setAlias($alias)
			->setPrimaryKey('id')
			//->addManyToOneRelationship('job_id')
			//->addManyToOneRelationship('user_id')
			//->addManyToOneRelationship('vendor_id')
			//->addManyToOneRelationship('part_note_item_id')
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
			//->addManyToOneRelationship('tax_code_id')
			->addStringField('tax_code')
			->addDateTimeField('created_on')
			->build();

		return $wo_part_note;
	}
}