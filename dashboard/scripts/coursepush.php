<?php
	if(isset($_POST["sCValue"]))
	{
		$sCValue=$_POST["sCValue"];
		$sCFrom=$_POST["sCFrom"];
		$sCFromArr=explode("DB", $sCFrom);
		searchCourse($sCValue, $sCFromArr);
	};
	if(isset($_POST["pCId"]))
	{
		$pCId=$_POST["pCId"];
		$pCFrom=$_POST["pCFrom"];
		$pCTo=$_POST["pCTo"];
		$pFromArr=explode("DB", $pCFrom);
		$pToArr=explode("DB", $pCTo);
		pushCourse($pCId, $pFromArr, $pToArr);
	};

	function searchCourse($sCValue, $sCFromArr)
	{
		require_once("sql.class.php");
		include("dbstructure.php");
		$sql=new Sql($sCFromArr[0]);
		$sql->selectDb($sCFromArr[1]);
		switch($sCFromArr[2])
		{
			case "pglms":
				$uidName="course_uid";
				break;
			case "speakez":
				$uidName="identifier";
				break;
			default:
				die();
				break;
		};
		$fields=array("id", $uidName." as uid", "title as name");
		$result=$sql->getSelectQ(false, $fields, "courses", array($uidName."=\"".$sCValue."\"", "deleted=\"0000-00-00 00:00:00\""));
		if(isset($result["id"]))
		{
			$result["services"]=$services[$sCFromArr[2]];
		};
		echo json_encode($result);
	}

	function pushCourse($pCId, $pFromArr=null, $pToArr=null)
	{
		require_once("sql.class.php");
		include("dbstructure.php");
		$result=array();
		$sql=new Sql($pFromArr[0]);
		$sql->selectDb($pFromArr[1]);
		$selectedCourse=$sql->getSelectQ(false, "*", "courses", array("id=".$pCId, "deleted=\"0000-00-00 00:00:00\""));
		$selSchFor=$selSch["course"][$pFromArr[2]];
		$selectedCourse=recursiveSQ($pFromArr[0], $pFromArr[1], $pFromArr[2], $selectedCourse, $selSchFor, $selectedCourse["id"]);
		$sql=new Sql($pToArr[0]);
		$sql->selectDb($pToArr[1]);
		switch($pToArr[2])
		{
			case "pglms":
				$uidClause="course_uid=\"".$selectedCourse["course_uid"]."\"";
				break;
			case "speakez":
				$uidClause="identifier=\"".$selectedCourse["identifier"]."\"";
				break;
			default:
				die();
				break;
		};
		$existingCourse=$sql->getSelectQ(false, "*", "courses", array($uidClause, "deleted=\"0000-00-00 00:00:00\""));
		if(isset($existingCourse["id"]))
		{
			$sql->setUpdateQ("deleted", "courses", date("Y-m-d H:i:s"), "id=".$existingCourse["id"]);
		};
		recursiveIQ($pToArr[0], $pToArr[1], $pToArr[2], $selectedCourse, "courses");
		echo implode("DB", $pToArr);
	}

	function recursiveSQ($db, $dbn, $type, $current, $selSchFor, $addVal=null)
	{
		include("dbstructure.php");
		$sql=new SQL($db);
		$sql->selectDb($dbn);
		$result=array();
		foreach($selSchFor as $key=>$value)
		{
			if(is_int($key))
			{
				if(isset($addVal))
				{
					$selClss["course"][$type][$value]=preg_replace("/#ADDVAL#/", $addVal, $selClss["course"][$type][$value]);
				};
				$current[$value]=$sql->getSelectQ(true, $selFlds["course"][$type][$value], $selTbls["course"][$type][$value], preg_replace("/#DYNVAL#/", $current["id"], $selClss["course"][$type][$value]));
			}
			else
			{
				$current[$key]=$sql->getSelectQ(true, $selFlds["course"][$type][$key], $selTbls["course"][$type][$key], preg_replace("/#DYNVAL#/", $current["id"], $selClss["course"][$type][$key]));
				foreach($current[$key] as &$newCurrent)
				{
					if($key=="lessons"||isset($addVal))
					{
						if($key=="lessons")
						{
							$newCurrent=recursiveSQ($db, $dbn, $type, $newCurrent, $value, $newCurrent["id"]);
						};
						if(isset($addVal))
						{
							$newCurrent=recursiveSQ($db, $dbn, $type, $newCurrent, $value, $addVal);
						};
					}
					else
					{
						$newCurrent=recursiveSQ($db, $dbn, $type, $newCurrent, $value);
					};
				};
			};
		};
		return $current;
	}

	function recursiveIQ($db, $dbn, $type, $current, $table, $dynVal=null, $addVal=null)
	{
		include("dbstructure.php");
		$sql=new SQL($db);
		$sql->selectDb($dbn);
		$currentLastId=$dynVal;
		$lastId=$addVal;
		$fields=array();
		$values=array();
		foreach($current as $key=>$val)
		{
			if(!is_int($key))
			{
				if(!is_array($val))
				{
					if($key!="id")
					{
						if(preg_match("/PARENT/", $key))
						{
							array_push($fields, preg_replace("/PARENT/", "", $key));
							array_push($values, $currentLastId);
						}
						else
						{
							if(preg_match("/ADDVAL/", $key))
							{
								array_push($fields, preg_replace("/ADDVAL/", "", $key));
								array_push($values, $lastId);
							}
							else
							{
								array_push($fields, $key);
								array_push($values, $val);
							};
						};
					};
				};
			};
		};
		if(isset($fields[0]))
		{
			$sql->setInsertQ($fields, $table, $values);
			if($table=="courses"||$table=="lessons")
			{
				$lastId=$sql->getLastInsertId();
			};
			$currentLastId=$sql->getLastInsertId();
		};
		foreach($current as $key=>$val)
		{
			if(is_array($val))
			{
				if(!is_int($key))
				{
					$table=$key;
				};
				recursiveIQ($db, $dbn, $type, $current[$key], $table, $currentLastId, $lastId);
			};
		};
	}
?>
