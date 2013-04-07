<?php
$s='{"total":359}';
echo $s;
$obj=json_decode($s,1);
print_r ($obj["total"]);
//print (json_decode($s)); 
?> 