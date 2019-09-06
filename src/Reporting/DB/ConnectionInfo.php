<?php

namespace App\Reporting\DB;

interface ConnectionInfo
{
	/**
	 * @return Dsn
	 */
	public function dsn();

	/**
	 * @return Credentials
	 */
	public function credentials();

	/**
	 * @return ConnectionOptions
	 */
	public function connectionOptions();
}