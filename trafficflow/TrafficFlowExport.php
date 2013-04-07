<?php
require_once 'common/excel/PHPExcel.php';  
require_once 'common/excel/phpExcel/Writer/Excel2007.php';  
require_once 'common/excel/phpExcel/Writer/Excel5.php';  
include_once 'common/excel/phpExcel/IOFactory.php';
 
// 创建一个处理对象实例   
$objExcel = new PHPExcel();   
// 创建文件格式写入对象实例, uncomment   
////$objWriter = new PHPExcel_Writer_Excel5($objExcel);     // 用于其他版本格式   
// or   
////$objWriter = new PHPExcel_Writer_Excel2007($objExcel); // 用于 2007 格式   
//$objWriter->setOffice2003Compatibility(true);   
$objExcel->setActiveSheetIndex(0);  
$objExcel->getActiveSheet()->setTitle('数据'); 

$objExcel->getActiveSheet()->setCellValue('a1', "序号");  
$objExcel->getActiveSheet()->setCellValue('b1', "调查者编号");  
$objExcel->getActiveSheet()->setCellValue('c1', "调查者姓名");  
$objExcel->getActiveSheet()->setCellValue('d1', "地点编号");  
$objExcel->getActiveSheet()->setCellValue('e1', "地点名称");  
$objExcel->getActiveSheet()->setCellValue('f1', "方向编号");  
$objExcel->getActiveSheet()->setCellValue('g1', "方向名称");  
$objExcel->getActiveSheet()->setCellValue('h1', "类型编号");  
$objExcel->getActiveSheet()->setCellValue('i1', "类型名称");  
$objExcel->getActiveSheet()->setCellValue('j1', "通过时间");  

//读取数据库   
$TableName = "view4export";
$con = mysql_connect("localhost","root","qweasd") or die("Error 2#". mysql_error());   
mysql_select_db("trafficflowcounter",$con) or die("Error 3#". mysql_error());   
$result = mysql_query("select * from ".$TableName) or die("Error 4#".mysql_error()); 

$i=2;
while($v = mysql_fetch_assoc($result)) {
	//print_r ($v);
    $objExcel->getActiveSheet()->setCellValue('a'.$i, $v["ID"]);  
    $objExcel->getActiveSheet()->setCellValue('b'.$i, $v["UserID"]);  
    $objExcel->getActiveSheet()->setCellValue('c'.$i, $v["UserName"]);  
    $objExcel->getActiveSheet()->setCellValue('d'.$i, $v["LocationID"]);  
    $objExcel->getActiveSheet()->setCellValue('e'.$i, $v["LocationName"]); 
    $objExcel->getActiveSheet()->setCellValue('f'.$i, $v["DirectionID"]);  
    $objExcel->getActiveSheet()->setCellValue('g'.$i, $v["DirectionName"]);  
    $objExcel->getActiveSheet()->setCellValue('h'.$i, $v["TypeID"]);  
    $objExcel->getActiveSheet()->setCellValue('i'.$i, $v["TypeName"]);  
    $objExcel->getActiveSheet()->setCellValue('j'.$i, $v["RecordTime"]);   
    $i++;  
}

mysql_free_result($result);
mysql_close($con);   

$objExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);   
$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
$objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
$objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);

//输出内容   
//   
$outputFileName = "output.xls";   

//到文件   
////$objWriter->save($outputFileName);   
//or   
//到浏览器   
header('Content-Type: application/vnd.ms-excel');  
header('Content-Disposition: attachment;filename="'.$outputFileName.'"');  
header('Cache-Control: max-age=0');  
$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');  
$objWriter->save('php://output');  


echo ("正确");
//Error code table
//Error 0: Success
//Error 1: Table Name error
//Error 2: Mysql database error
//Error 3: 
//Error 4: 
?> 