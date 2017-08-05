<?php
// 일반적인 함수묶음클라스
class general extends CI_Model {
	var $tbl_sitesetting;
	public function __construct() 
	{
		global $MYSQL;

		//$this->tbl_sitesetting = $MYSQL['tbl_sitesetting'];

		parent::__construct();
	}

	function SaveExcelFile($header, $table, $filename, $start = 0, $size = 1000 )
	{
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');

		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		require_once 'www/include/classes/PHPExcel.php';

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Zhongge Han")
								 ->setLastModifiedBy("Zhongge Han")
								 ->setTitle("PHPExcel Test Document")
								 ->setSubject("PHPExcel Test Document")
								 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
								 ->setKeywords("office PHPExcel php")
								 ->setCategory("Test result file");

		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle('result');
		
		$cellname_prefix = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		for($i=26; $i<256; $i++)
		{
			$quoter = ($i - ($i % 26)) / 26;
			$remind = $i % 26;
			$cellname_prefix[$i] = $cellname_prefix[$quoter-1].$cellname_prefix[$remind];
		}
		$j = 0;
		foreach($header as $value)
		{
			$j++;
			$cellname = $cellname_prefix[$j-1]."1";
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($cellname, $value);
		}
		$i = 1;
		foreach($table as $row_ary)
		{
			$i++;
			$j=0;
			foreach($row_ary as $col_ary)
			{
				$j++;
				if($j <= $start) continue;
				if($j > $start + $size) continue;

				$cellname = $cellname_prefix[$j-1-$start].$i;
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($cellname, $col_ary);
			}
		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save("./www/excel/".$filename);
//		echo "<script>alert('$filename 파일로 보관되었습니다.');</script>";
	}

	function downloadExcelEmailByType($header, $table, $filename, $start = 0, $size = 1000 )
	{
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');

		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		require_once 'www/include/classes/PHPExcel.php';

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("David Wang")
								 ->setLastModifiedBy("David Wang")
								 ->setTitle("Email List")
								 ->setSubject("Email List Document")
								 ->setDescription("Email List By Type, generated using PHP classes.")
								 ->setKeywords("office PHPExcel php")
								 ->setCategory("Email List result file");

		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle('result');
		
		$cellname_prefix = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		for($i=26; $i<256; $i++)
		{
			$quoter = ($i - ($i % 26)) / 26;
			$remind = $i % 26;
			$cellname_prefix[$i] = $cellname_prefix[$quoter-1].$cellname_prefix[$remind];
		}
		$j = 0;
		foreach($header as $value)
		{
			$j++;
			$cellname = $cellname_prefix[$j-1]."1";
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($cellname, $value);
		}
		$i = 1;
		foreach($table as $row_ary)
		{
			$i++;
			$j=0;
			foreach($row_ary as $col_ary)
			{
				$j++;
				if($j <= $start) continue;
				if($j > $start + $size) continue;

				$cellname = $cellname_prefix[$j-1-$start].$i;
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($cellname, $col_ary);
			}
		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//$objWriter->save("./www/excel/".$filename);
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition:attachment; filename="'.$filename.'"');
		$objWriter->save("php://output");
//		echo "<script>alert('$filename 파일로 보관되었습니다.');</script>";
	}
}
?>
