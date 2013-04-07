<?php
$PostData = file_get_contents('php://input');  
//$PostData = '{"data":[{"_id":1,"user_id":"0700250122","location_id":1,"direction_id":2,"type_id":2,"RecordTime":"2013-03-18 11:11:00"},{"_id":2,"user_id":"0700250122","location_id":1,"direction_id":2,"type_id":1,"RecordTime":"2013-03-18 11:12:00"}],"count":2}';
//print_r($PostData);

$jsonObjects = json_decode($PostData,1);   
//print_r($jsonObjects);

$PostCount = $jsonObjects['count'];
//print_r($PostCount);

$jsonArray = $jsonObjects['data'];
$ArrayCount = count($jsonArray);
//print_r($ArrayCount);

if ($PostCount != $ArrayCount)
{
	die ('Error 3');
}

//保存数据库   
$con = mysql_connect("localhost","root","qweasd") or die("Error 1#". mysql_error());   
mysql_select_db("TrafficFlowCounter",$con) or die("Error 2#". mysql_error());   
$DataCount = 0;

mysql_query("BEGIN");							//开始一个事务 
mysql_query("SET AUTOCOMMIT=0"); 	//设置事务不自动commit 

foreach($jsonArray as $obj)
{ 
	$DataCount++;
	$sql = "INSERT INTO TrafficFlow (user_id,location_id,direction_id,type_id,RecordTime) VALUES ('".$obj["user_id"]."','".$obj["location_id"]."','".$obj["direction_id"]."', '".$obj["type_id"]."', '".$obj["RecordTime"]."')"; 
	$sqlresult=mysql_query($sql,$con);
	//echo "sql:".$sqlresult;
	if(!$sqlresult)
	{
		mysql_query("ROLLBACK");			//非autocommit模式，执行ROLLBACK使事务操作无效
		die ("error 4#". mysql_error()."#".$obj["_id"]."#");
	}
	//echo ("#".$obj["_id"]."# inserted!<br>");
}
mysql_query("COMMIT");						//非autocommit模式，必须手动执行COMMIT使操作生效

mysql_close($con);   
echo ("ok");
//Error code table
//Error 0: Success
//Error 1: Cannot connect to MySQL
//Error 2: Cannot connect to DataBase "TrafficFlowCounter"
//Error 3: Count from PostData is no equal to the count in array
//Error 4: Insert to database error, and it't _id
?> 