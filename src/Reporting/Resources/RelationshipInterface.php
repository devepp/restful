<?php


namespace App\Reporting\Resources;


interface RelationshipInterface
{
	/**
	 * @param string $tableAlias
	 * @return bool
	 */
	public function hasTable($tableAlias);

	/**
	 * @param string $tableAlias
	 * @param string $otherTableAlias
	 * @return bool
	 */
	public function hasTables($tableAlias, $otherTableAlias);

	public function condition();

	/**
	 * @param string $tableAlias
	 * @param string $otherTableAlias
	 * @return bool
	 */
	public function tableHasOne($tableAlias, $otherTableAlias);
}