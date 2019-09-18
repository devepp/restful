<?php


namespace App\Domain;


class Asset
{
	public function getId()
	{
		return new AssetId(1);
	}

}