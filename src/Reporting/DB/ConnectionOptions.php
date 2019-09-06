<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 7/19/2019
 * Time: 9:29 AM
 */

namespace App\Reporting\DB;


class ConnectionOptions
{
	CONST AVAILABLE_OPTIONS = [

	];

	private $options = [];

	/**
	 * ConnectionOptions constructor.
	 * @param array $options
	 */
	public function __construct($options)
	{
		//TODO add check to see if options are in available options
		$this->options = $options;
	}

	/**
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}
}