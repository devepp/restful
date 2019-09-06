<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 7/19/2019
 * Time: 9:28 AM
 */

namespace App\Reporting\DB;


class Credentials
{
	private $user;

	private $password;

	/**
	 * Credentials constructor.
	 * @param $user
	 * @param $password
	 */
	public function __construct($user, $password)
	{
		$this->user = $user;
		$this->password = $password;
	}

	/**
	 * @return mixed
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @return mixed
	 */
	public function getPassword()
	{
		return $this->password;
	}
}