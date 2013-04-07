<?php
header("Content-Type:text/html; charset=UTF-8");

//获取参数信息
$TableName= $_POST['TableName'];
//echo $TableName;

switch($TableName){
	case "user": 
		$FieldName = "UserName";
		break;
	case "location":
		$FieldName = "LocationName";
		break;
	case "direction":
		$FieldName = "DirectionName";
		break;
	case "type":
		$FieldName = "TypeName";
		break;
	defult:
		die ("Error 1#$TableName");
}
//echo "fieldname is ".$FieldName;
//exit();
//读取数据库   
$con = mysql_connect("localhost","root","qweasd") or die("Error 2#". mysql_error());   
mysql_select_db("trafficflowcounter",$con) or die("Error 2#". mysql_error());   
$result = mysql_query("select * from ".$TableName) or die("Error 2#".mysql_error()); 
$rows = array();
while($r = mysql_fetch_assoc($result)) {
    $rows[] = $r;
}

$ReturnData = array('data' => $rows);
echo urldecode(json_encode($ReturnData));
mysql_free_result($result);
mysql_close($con);   

echo ("正确");
//Error code table
//Error 0: Success
//Error 1: Table Name error
//Error 2: Mysql database error
//Error 3: 
//Error 4: 
?> 