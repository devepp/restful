<?php

namespace App\Reporting\DB;

use CI;
use PDO;

class CIConnectionInfo implements ConnectionInfo
{
	private $ci;

	public function __construct()
	{
		$this->ci = CI::$APP;
		$this->ci->load->database();
	}

	public function dsn()
	{
		return Dsn::mysql($this->ci->db->hostname, $this->ci->db->database, $this->ci->db->char_set);
	}

	public function credentials()
	{
		return new Credentials($this->ci->db->username, $this->ci->db->password);
	}

	public function connectionOptions()
	{
		return new ConnectionOptions([
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		]);
	}


}