<?php

namespace App\Reporting\Excel;

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\XLSX\Writer;
use App\Reporting\TabularData;

class SpoutExcel
{
	/** @var Writer */
	private $writer;

	/**
	 * SpoutExcel constructor.
	 * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
	 */
	public function __construct()
	{
		$this->writer = WriterFactory::create(Type::XLSX);
	}


	public function stream(TabularData $tabularData, $filename)
	{
//		die(var_dump($filename));
		$this->writer->openToBrowser($filename);

		$this->addData($tabularData);

		$this->writer->close();
	}

	public function writeToFile(TabularData $tabularData, $filename, $path)
	{
		$this->writer->openToFile($path.DIRECTORY_SEPARATOR.$filename);

		$this->addData($tabularData);

		$this->writer->close();
	}

	protected function addData(TabularData $data)
	{
		$sheet = $this->writer->getCurrentSheet();
		$sheet->setName('data');

		$this->writer->addRow($data->headerTextAll());

		foreach ($data->rowValues() as $row) {
			$this->writer->addRow($row);
		}
	}

}