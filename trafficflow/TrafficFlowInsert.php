<?php
session_start();
header("Content-type:text/html;charset:utf-8");

//ȫ�ֱ���
$succ_result=0;
$error_result=0;
$file=$_FILES['filename'];
$max_size="2000000"; //����ļ����ƣ���λ��byte��
$fname=$file['name'];
//print_r ($file);


$ftype=strtolower(substr(strrchr($fname,'.'),1));
 
 //�ļ���ʽ
 $uploadfile=$file['tmp_name'];
 if($_SERVER['REQUEST_METHOD']=='POST'){
     if(is_uploaded_file($uploadfile)){
          if($file['size']>$max_size){
         echo "�����ļ�̫��"; 
         exit;
         }
          if($ftype!='xls'){
         echo "������ļ�����xls�ļ�";
          exit;   
         }
     }else{
     echo "�����ļ�Ϊ��!";
      exit; 
     } 
 }

//����phpexcel���
require_once 'common/excel/PHPExcel.php';  
require_once 'common/excel/phpExcel/Writer/Excel2007.php';  
require_once 'common/excel/phpExcel/Writer/Excel5.php';  
include_once 'common/excel/phpExcel/IOFactory.php';

//�������ݿ�   
$con = mysql_connect("localhost","root","qweasd") or die("Error 2#". mysql_error());   
mysql_select_db("trafficflowcounter",$con) or die("Error 2#". mysql_error());   
switch ($fname){
 	case "user.xls":
 		mysql_query ("truncate table user");
 		break;
 	case "location.xls":
 		mysql_query ("truncate table location");
 		break;
 	case "direction.xls":
 	  mysql_query ("truncate table direction");
 	  break;
 	case "type.xls":
 		mysql_query ("truncate table type");
 	  break;
 	default:
 		die ("�ļ��������ݱ�������!");
 }


$objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format 
$objPHPExcel = $objReader->load($uploadfile); 
$sheet = $objPHPExcel->getSheet(0); 
$highestRow = $sheet->getHighestRow(); // ȡ�������� 
$highestColumn = $sheet->getHighestColumn(); // ȡ��������
  $arr_result=array();
  $strs=array();

for($j=2;$j<=$highestRow;$j++)
 { 
    unset($arr_result);
    unset($strs);
 for($k='A';$k<= $highestColumn;$k++){ 
     //��ȡ��Ԫ��
  	$arr_result  .= $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().',';
 }
 
 $strs=explode(",",$arr_result);
 switch ($fname){
 	case "user.xls":
 		if (trim($strs[2])=="")
 			$sql="insert into user (ID,UserName,Password) values ('$strs[0]','$strs[1]','$strs[0]')";
 		else
 			$sql="insert into user (ID,UserName,Password) values ('$strs[0]','$strs[1]','$strs[2]')";	
 		break;
 	case "location.xls":
 		$sql="insert into location (ID,LocationName) values ('$strs[0]','$strs[1]')";
 		break;
 	case "direction.xls":
 	  $sql="insert into direction (ID,DirectionName) values ('$strs[0]','$strs[1]')";
 	  break;
 	case "type.xls":
 		if (trim($strs[2]) =="")
 			$sql="insert into type (ID,TypeName,Parent) values ('$strs[0]','$strs[1]',NULL)";
 		else
 			$sql="insert into type (ID,TypeName,Parent) values ('$strs[0]','$strs[1]','$strs[2]')";
 	  break;
 	default:
 		die ("�ļ��������ݱ�������!");
 }
 echo $sql."<br/>"; 

 mysql_query("set names utf8");
 $result=mysql_query($sql) or die("ִ�д���");

 $insert_num=mysql_affected_rows();
  if($insert_num>0){
        $succ_result+=1;
    }else{
        $error_result+=1;
   }
}

echo "����ɹ�".$succ_result."�����ݣ�����<br>";
echo "����ʧ��".$error_result."�����ݣ�����";
?>