<?php
// User accounts management dedicated PhP
//
// Checks which data has been sent, then calls the right function.

	// Search function data
	if(isset($_POST["sValue"]))
	{
		$sValue=$_POST["sValue"];
		$sType=$_POST["sType"];
		$sProduct=$_POST["sProduct"];
		$sProduct=explode("DB", $sProduct);
		searchUser($sValue, $sType, $sProduct);
	};
	// Add function data
	if(isset($_POST["aUsername"]))
	{
		$aUsername=$_POST["aUsername"];
		$aEmail=$_POST["aEmail"];
		$aProduct=$_POST["aProduct"];
		$aProduct=explode("DB", $aProduct);
		addUser($aUsername, $aEmail, $aProduct);
	};
	// Edit function data
	if(isset($_POST["eId"]))
	{
		$eId=$_POST["eId"];
		$eUsername=$_POST["eUsername"];
		$eEmail=$_POST["eEmail"];
		$eProduct=$_POST["eProduct"];
		$eProduct=explode("DB", $eProduct);
		editUser($eId, $eUsername, $eEmail, $eProduct);
	};
	// Delete function data
	if(isset($_POST["dId"]))
	{
		$dId=$_POST["dId"];
		$dProduct=$_POST["dProduct"];
		$dProduct=explode("DB", $dProduct);
		deleteUser($dId, $dProduct);
	};

	function searchUser($sValue, $sType, $sProduct)
	{
		require_once("sql.class.php");
		$sql=new Sql($sProduct[0]);
		if($sProduct[1]=="all")
		{
			if($sProduct[0]=="lo")
			{
		        	$dbNames=array("pglms1"=>"pglms", "speakez"=>"speakez");
			};
			if($sProduct[0]=="qa")
			{
		        	$dbNames=array("pglms1"=>"pglms", "pglms2"=>"pglms", "speakez1"=>"speakez", "speakez2"=>"speakez");
			};
		}
		else
		{
			$dbNames=array($sProduct[1]=>$sProduct[2]);
		};
		include("dbstructure.php");
		$queryRun=true;
		if(($sType=="username"||$sType=="email")&&strlen($sValue)<3)
		{
			$queryRun=false;
		};
		$result=array();
		if($queryRun==true)
		{
	                foreach($dbNames as $dbName=>$dbType)
        	        {
				$sql->selectDb($dbName);
				if($sType=="id")
				{
					$clause="id=\"".addslashes($sValue)."\"";
				}
				else
				{
					$clause=$selClssFlds["users"][$dbType][$sType]." like \"%".addslashes($sValue)."%\"";
				};
				if(is_array($dbResult=$sql->getSelectQ(true, $selFlds["users"][$dbType], "users", $clause, array("id", "ASC"), 50)))
				{
					foreach($dbResult as &$r)
					{
						$r["product"]=$dbName;
						$r["server"]=$sProduct[0]."DB".$dbName."DB".$dbType;
						array_push($result, $r);
					};
				}
				else
				{
					$result["status"]="failed";
					$result["output"]=$sql->getError();
				};
			};
		};
		echo json_encode($result);
	}

	function addUser($aUsername, $aEmail, $aProduct)
	{
		require_once("sql.class.php");
		$sql=new Sql($aProduct[0]);
		include("dbstructure.php");
		$result=array();
		if(($aUsername!="")&&($aEmail!=""))
		{
			$sql->selectDb($aProduct[1]);
			$values=array($aEmail);
			if($aProduct[2]=="pglms")
			{
				array_push($values, $aUsername);
			};
			foreach($insDefVals["users"][$aProduct[2]] as $defVals)
			{
				array_push($values, $defVals);
			};
			if($insert=$sql->setInsertQ($insFlds["users"][$aProduct[2]], "users", $values))
			{
				$result["status"]="success";
				$result["output"]="User created. Change your password as soon as possible. Default password: M!d1138urY";
				$result["server"]="server".$aProduct[0];
				$result["id"]=$sql->getLastInsertId();
				if($aProduct[2]="speakez")
				{
					$sql->setInsertQ(array("editor_id", "course_id", "reader", "writer", "publisher", "manager"), "permissions", array($result["id"], 2616, 1, 1, 1, 1));
					$sql->setInsertQ(array("editor_id", "course_id", "reader", "writer", "publisher", "manager"), "permissions", array($result["id"], 2615, 1, 1, 1, 1));
				};
			}
			else
			{
				$result["status"]="failed";
				$result["output"]=$sql->getError();
			};
		};
		if(!isset($result["output"]))
		{
			$result["status"]="failed";
			$result["output"]="Please fill in all the fields.";
		};
		echo json_encode($result);
	}

	function editUser($eId, $eUsername, $eEmail, $eProduct)
	{
		require_once("sql.class.php");
		$sql=new Sql($eProduct[0]);
		$sql->selectDb($eProduct[1]);
		include("dbstructure.php");
		$result=array();
		if(($eUsername!=""&&$eUsername!="Not used")||($eEmail!=""))
		{
			$fields=array();
			$values=array();
			if($eEmail!="")
			{
				array_push($fields, $updFlds["users"][$eProduct[2]]["email"]);
				array_push($values, $eEmail);
			};
			if($updFlds["users"][$eProduct[2]]["email"]!=$updFlds["users"][$eProduct[2]]["username"]&&$eUsername!="")
			{
				array_push($fields, $updFlds["users"][$eProduct[2]]["username"]);
				array_push($values, $eUsername);
			};
			if($update=$sql->setUpdateQ($fields, "users", $values, "id=".$eId))
			{
				$result["status"]="success";
				$result["id"]=$eId;
			}
			else
			{
				$result["status"]="failed";
				$result["output"]=$sql->getError();
			};
		};
		if(!isset($result["status"]))
		{
			$result["status"]="failed";
			$result["output"]="Please fill in at least one field.";
		};
		echo json_encode($result);
	}

	function deleteUser($dId, $dProduct)
	{
		require_once("sql.class.php");
		$sql=new Sql($dProduct[0]);
		$sql->selectDb($dProduct[1]);
		include("dbstructure.php");
		$result=array();
		if($delete=$sql->setDeleteQ("users", "id=".$dId, 1))
		{
			$result["status"]="success";
		}
		else
		{
			$result["status"]="failed";
			$result["output"]=$sql->getError();
		};
		echo json_encode($result);
	}
?>
