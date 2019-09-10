<?php

namespace App\Domain\Reports;

use App\Reporting\AbstractProviderRepository;
use App\Domain\Reports\WorkOrders;
use App\Domain\Reports\Events;
use App\Domain\Reports\Types;
use App\Domain\Reports\Facilities;
use App\Domain\Reports\Jobs;
use App\Domain\Reports\AccountingNotes;
use App\Domain\Reports\AccountingNoteType;
use App\Domain\Reports\Users;
use App\Domain\Reports\WorkTypes;
use App\Domain\Reports\Reasons;
use App\Domain\Reports\FacilityAreas;
use App\Domain\Reports\Priorities;
use App\Domain\Reports\Response;
use App\Domain\Reports\FacilityRooms;
use App\Domain\Reports\ServiceGroup;
use App\Domain\Reports\PartNote;

class WOProviderRepository extends AbstractProviderRepository
{
	CONST PROVIDERS = [
		WorkOrders::SLUG => WorkOrders::class,
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
