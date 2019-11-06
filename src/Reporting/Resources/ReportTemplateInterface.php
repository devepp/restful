<?php


namespace App\Reporting\Resources;

use App\Reporting\DB\Query;
use App\Reporting\DB\QueryBuilderFactoryInterface;
use App\Reporting\ReportFieldInterface;
use App\Reporting\ReportFilterInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ReportTemplateInterface
{

	public function availableRelatedResources();

	/**
	 * @return array
	 */
	public function nestedFields();

	/**
	 * @return ReportFieldInterface[]
	 */
	public function availableFields();

	/**
	 * @return array
	 */
	public function nestedFilters();

	/**
	 * @return ReportFilterInterface[]
	 */
	public function availableFilters();


	/**
	 * @param QueryBuilderFactoryInterface $queryBuilderFactory
	 * @param ServerRequestInterface $request
	 * @return Query
	 */
	public function getQuery(QueryBuilderFactoryInterface $queryBuilderFactory, ServerRequestInterface $request);
}