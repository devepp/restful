<?php

namespace App\Reporting\Excel;

use App\Reporting\TabularData;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\WriterPart;

class PhpOfficeSpreadsheet
{

	public function stream(TabularData $tabularData, $filename)
	{
		$spreadsheet = $this->makeSpreadsheet($tabularData);

		$spreadsheet->getProperties()->setTitle($filename);

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}

	public function writeToFile(TabularData $tabularData, $filename, $path)
	{
		$spreadsheet = $this->makeSpreadsheet($tabularData);

		$spreadsheet->getProperties()->setTitle($filename);

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

		$writer->save($path.DIRECTORY_SEPARATOR.$filename);
	}

	protected function makeSpreadsheet(TabularData $tabularData)
	{
		$spreadsheet = new Spreadsheet();

		$dataSheet = new Worksheet($spreadsheet, 'data');

		$sheet = $this->addDataToSheet($dataSheet, $tabularData);

		$spreadsheet->addSheet($sheet);

		$spreadsheet->setActiveSheetIndexByName('data');

		return $spreadsheet;
	}

	protected function addDataToSheet(Worksheet $sheet, TabularData $data)
	{
		$headerText = $data->headerTextAll();
		$sheet->fromArray($headerText, null, 'A1');

		for ($i = 1; $i <= count($headerText); $i++) {
			$sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
		}

		$rowNumber = 2;

		foreach ($data->rowValues() as $row) {
			$sheet->fromArray($row, null, 'A'.$rowNumber);
			$rowNumber++;
		}
		return $sheet;
	}
}