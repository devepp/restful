<?php

namespace eBase\Modules;

use App\Reporting\AbstractProviderRepository;
use eBase\Modules\Orders;
use eBase\Modules\Events;
use eBase\Modules\Types;
use eBase\Modules\Facilities;
use eBase\Modules\Jobs;
use eBase\Modules\AccountingNotes;
use eBase\Modules\AccountingNoteType;
use eBase\Modules\Users;
use eBase\Modules\WorkTypes;
use eBase\Modules\Reasons;
use eBase\Modules\FacilityAreas;
use eBase\Modules\Priorities;
use eBase\Modules\Response;
use eBase\Modules\FacilityRooms;
use eBase\Modules\ServiceGroup;
use eBase\Modules\PartNote;

class SlamProviderRepository extends AbstractProviderRepository
{
	CONST PROVIDERS = [
		Orders::SLUG => Orders::class,
		Events::SLUG => Events::class,
		Types::SLUG => Types::class,
		Facilities::SLUG => Facilities::class,
		Jobs::SLUG => Jobs::class,
		AccountingNotes::SLUG => AccountingNotes::class,
		AccountingNoteType::SLUG => AccountingNoteType::class,
		Users::SLUG => Users::class,
		WorkTypes::SLUG => WorkTypes::class,
		Reasons::SLUG => Reasons::class,
		FacilityAreas::SLUG => FacilityAreas::class,
		Priorities::SLUG => Priorities::class,
		Response::SLUG => Response::class,
		FacilityRooms::SLUG => FacilityRooms::class,
		ServiceGroup::SLUG => ServiceGroup::class,
		PartNote::SLUG => PartNote::class,
	];
	
}
