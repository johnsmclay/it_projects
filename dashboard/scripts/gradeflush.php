<?php
	if(isset($_POST["sGValue"]))
	{
		$sGValue=$_POST["sGValue"];
		$sGType=$_POST["sGType"];
		$sGServer=$_POST["sGServer"];
		$sGServ=explode("DB", $sGServer);
		searchGrade($sGValue, $sGType, $sGServ);
	};
	if(isset($_POST["fGId"]))
	{
		$fGId=$_POST["fGId"];
		$fGType=$_POST["fGType"];
		$fGServer=$_POST["fGServer"];
		$fGServ=explode("DB", $fGServer);
		flushGrades($fGId, $fGType, $fGServ);
	};

	function searchGrade($sGValue, $sGType, $sGServ)
	{
		require_once("sql.class.php");
		$result=array();
		$sql=new Sql($sGServ[0]);
		$sql->selectDb($sGServ[1]);
		$types=array();
		$types["user_id"]="user";
		$types["activity_id"]="test";
		$result["type"]=$types[$sGType];
		$result["dbtype"]=$sGType;
		$result["server"]=implode("DB", $sGServ);
		if($sGValue!="")
		{
			if($sGType=="user_id")
			{
				if($userId=$sql->getSelectQ(false, "id", "users", array("identifier=\"".$sGValue."\"", "deleted=\"0000-00-00 00:00:00\""), null, 1))
				{
					$sGValue=$userId["id"];
				}
				else
				{
					$userId="noid";
				};
			};
			if(is_array($selGrades=$sql->getSelectQ(false, "count(*)", "grades", $sGType."=\"".$sGValue."\"")))
			{
				if($selGrades["count(*)"]!=0)
				{
					$result["status"]="success";
					$result["id"]=$sGValue;
					$result["num"]=$selGrades["count(*)"];
				}
				else
				{
					$result["status"]="nomatch";
				};
			}
			else
			{
				$result["status"]="failed";
				$result["output"]=$sql->getError();
			};
		};
		echo json_encode($result);
	}

	function flushGrades($fGId, $fGType, $fGServ)
	{
		require_once("sql.class.php");
		$result=array();
		$sql=new Sql($fGServ[0]);
		$sql->selectDb($fGServ[1]);
		if($delGrades=$sql->setDeleteQ("grades", $fGType."=\"".$fGId."\""))
		{
			$result["status"]="success";
		}
		else
		{
			$result["status"]="failed";
			$result["output"]=$sql->getError();;
		};
		echo json_encode($result);
	}
?>
