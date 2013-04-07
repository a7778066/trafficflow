<?php
include ("inspectcarmobile\include\dbcommon.php");

if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br />";
  }
else
  {
 	$Year = date('Y');
	$Month = date('m');
	$Day = date('d');
	$Parameter = explode("^",$_POST["name"]);
	$PCID = $Parameter[0];
	$CarInfoID = $Parameter[1];
	$InspectStateID = $Parameter[2];
		
	$sqlStr = "select Content from dbo.Parameter where CS='Path'";
	$rsPath = CustomQuery($sqlStr);
	$dataPath = db_fetch_array($rsPath);	//rootpath
	$PhotoPath = $dataPath["Content"];
	if ($PhotoPath =="")
		$PhotoPath = "c:\\";
	
	if (is_dir($PhotoPath)==false)
		mkdir($PhotoPath,0700,false);
	
	$PhotoPath = $PhotoPath."\\" . $Year;		//rootpath\YYYY
	if (is_dir($PhotoPath)==false)
		mkdir($PhotoPath,0700,false);

	$PhotoPath = $PhotoPath."\\".$Month;		//rootpath\YYYY\MM
	if (is_dir($PhotoPath)==false)
		mkdir($PhotoPath,0700,false);

	$PhotoPath = $PhotoPath."\\".$Day;			//rootpath\YYYY\MM\DD
	if (is_dir($PhotoPath)==false)
		mkdir($PhotoPath,0700,false);

	$sqlStr="SELECT CarInfo.Hphm AS cphm,CarType.Name AS hplx FROM dbo.CarInfo INNER JOIN dbo.CarType ON dbo.CarInfo.CarTypeID=dbo.CarType.ID WHERE dbo.CarInfo.ID=" .$CarInfoID;
	$rsCarInfo=CustomQuery($sqlStr);
	$dataCarInfo = db_fetch_array($rsCarInfo);
	
	$PhotoPath = $PhotoPath ."\\".$dataCarInfo["cphm"]."_".$dataCarInfo["hplx"];		//rootpath\YYYY\MM\DD\≥µ≈∆∫≈¬Î_∫≈≈∆¿‡–Õ
	if (is_dir($PhotoPath)==false)
		mkdir($PhotoPath,0700,false);

  //echo $PhotoPath;
  
	$PhotoFileName = $PhotoPath. "\\" . $dataCarInfo["cphm"] . "_" . $dataCarInfo["hplx"]. "_P". $PCID . ".jpg";
 	if (move_uploaded_file($_FILES["file"]["tmp_name"],  $PhotoFileName))
 	{
 		$sqlStr = "select count(*) as count from dbo.PDAPhoto where InspectStateID=".$InspectStateID;
		$rsPDA = CustomQuery($sqlStr);
		$dataPDA = db_fetch_array($rsPDA);	
		$HaveID = $dataPDA["count"];
		
		if ($HaveID =="0") 
		{
			$sqlStr = "INSERT INTO dbo.PDAPhoto (InspectStateID) VALUES (".$InspectStateID .")";
			CustomQuery($sqlStr);
		}
		
		$sqlStr = "select Name from dbo.PDACheck where ID='".$PCID."'";
		$rsPDA = CustomQuery($sqlStr);
		$dataPDA = db_fetch_array($rsPDA);	
		$PCItem = $dataPDA["Name"];
		
		$PCItemFlag = $PCItem . "Flag";
		
		$sqlStr = "update dbo.PDAPhoto set " .$PCItemFlag . "=1 where InspectStateID = ".$InspectStateID;
		//$sqlStr = "update dbo.PDAPhoto set " .$PCItemFlag . "=1, " .$PCItem . "='" . $PhotoFileName . "' where InspectStateID = ".$InspectStateID;
 		//echo $sqlStr;
 		CustomQuery($sqlStr);
 		$sqlStr = "update dbo.PDAPhoto set " .$PCItem . "='" . $PhotoFileName . "' where InspectStateID = ".$InspectStateID;
 		CustomQuery($sqlStr);
 		echo "Upload Success";
 	}
 	else
 		echo "Upload Failure";


	//echo "Upload: " . $_FILES["file"]["name"] . "<br />";
  //echo "Type: " . $_FILES["file"]["type"] . "<br />";
  //echo "Size: " . ($_FILES["file"]["size"] ) . " Kb<br />";
  //echo "Stored in: " . $_FILES["file"]["tmp_name"];
}
?> 