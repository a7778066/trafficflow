<?php

//清空数据库   
$con = mysql_connect("localhost","root","qweasd") or die("Error 2#". mysql_error());   
mysql_select_db("trafficflowcounter",$con) or die("Error 3#". mysql_error());   
mysql_query("truncate table trafficflow) or die("Error 4#".mysql_error()); 
mysql_query("truncate table user) or die("Error 4#".mysql_error()); 
mysql_query("truncate table location) or die("Error 4#".mysql_error()); 
mysql_query("truncate table direction) or die("Error 4#".mysql_error()); 
mysql_query("truncate table type) or die("Error 4#".mysql_error()); 

echo ("正确");
?>