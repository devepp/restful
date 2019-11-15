<?php

namespace App\Reporting\DB\QueryBuilder\BuilderInterfaceParts;

interface OrdersInterface
{
	/**
	 * @param $field
	 * @param string $direction
	 * @return self - cloned builder
	 */
	public function orderBy($field, $direction = 'ASC');
}