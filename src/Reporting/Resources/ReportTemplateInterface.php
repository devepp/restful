<?php


namespace App\Reporting\Resources;

use App\Reporting\DB\ConnectionInterface;
use App\Reporting\DB\DbInterface;
use App\Reporting\DB\Query;
use App\Reporting\DB\QueryBuilderFactoryInterface;
use App\Reporting\ReportFieldCollection;
use App\Reporting\ReportFieldInterface;
use App\Reporting\ReportFilterCollection;
use App\Reporting\ReportFilterInterface;
use App\Reporting\ReportRequest;
use App\Reporting\SelectionsInterface;
use App\Reporting\TabularData;

interface ReportTemplateInterface
{

	/**
	 * @return ReportFieldCollection
	 */
	public function fields();

	/**
	 * @return ReportFilterCollection
	 */
	public function filters();

	/**
	 * @param DbInterface $db
	 * @param ReportRequest $request
	 * @return TabularData
	 */
	public function getData(DbInterface $db, ReportRequest $request);
}