<?php
	if(isset($_POST["tags"]))
	{
		if($_POST["tags"]!="starttags")
		{
			$tags=explode("_xmltag_", $_POST["tags"]);
			$pId=null;
			$pType=null;
			if(isset($_POST["pId"]))
			{
				$pId=$_POST["pId"];
				$pType=$_POST["startType"];
			};
			searchActivities($tags, $pId, $pType);
		};
	};
	if(isset($_POST["getCourses"]))
	{
		getCoursesList();
	};
	if(isset($_POST["getListType"]))
	{
		$getListType=$_POST["getListType"];
		$parentId=$_POST["parentId"];
		getList($getListType, $parentId);
	};
	if(isset($_POST["actPId"]))
	{
		$pId=$_POST["actPId"];
		$pType=null;
		$tGroup=$_POST["tGroup"];
		if(isset($_POST["startType"]))
		{
			$pType=$_POST["startType"];
		};
		getActivitiesList($pId, $pType, $tGroup);
	};

	function searchActivities($tags, $pId=null, $pType=null)
	{
		require_once("sql.class.php");
		$sql=new Sql("zeus");
		$tables=array("courses", "units", "lessons", "chains", "activities");
		$clauses=array
		(
			"courses.deleted=\"0000-00-00 00:00:00\"",
			"units.deleted=\"0000-00-00 00:00:00\"",
			"lessons.deleted=\"0000-00-00 00:00:00\"",
			"chains.deleted=\"0000-00-00 00:00:00\"",
			"activities.deleted=\"0000-00-00 00:00:00\"",
			"courses.id=units.course_id",
			"units.id=lessons.unit_id",
			"lessons.id=chains.lesson_id",
			"chains.id=activities.chain_id"
		);
		$tagsClause="activities.data like \"%";
		foreach($tags as $tag)
		{
			$tagsClause.="<".$tag." value=\\\"true\\\"/>%";
		};
		$tagsClause.="\"";
		$parents=array
		(
			"unit"=>array("unit"),
			"lesson"=>array("unit", "lesson"),
			"chain"=>array("unit", "lesson", "chain")
		);
		if($pId!=null)
		{
			$parClauses=$clauses;
			$parClauses[9]=$pType."s.id=".$pId;
			$parFields=array();
			$result=$sql->getSelectQ(true, array("distinct courses.title as course", "courses.id as id"), $tables, $parClauses);
			if(isset($parents[$pType]))
			foreach($parents[$pType] as $par)
			{
				switch($par)
				{
					case "unit":
						$result[0]["units"]=$sql->getSelectQ(true, array("distinct ".$par."s.title as ".$par, $par."s.id as id"), $tables, $parClauses);
						break;
					case "lesson":
						$result[0]["units"][0]["lessons"]=$sql->getSelectQ(true, array("distinct ".$par."s.title as ".$par, $par."s.id as id"), $tables, $parClauses);
						break;
					case "chain":
						$result[0]["units"][0]["lessons"][0]["chains"]=$sql->getSelectQ(true, array("distinct ".$par."s.title as ".$par, $par."s.id as id"), $tables, $parClauses);
						break;
					default:
						break;
				};
			};
		}
		else
		{
			$result=$sql->getSelectQ(true, array("title as course", "id"), "courses", "deleted=\"0000-00-00 00:00:00\"", array("title", "ASC"));
		};
		foreach($result as &$course)
		{
			$tempClauses=$clauses;
			$tempClauses[9]="courses.id=".$course["id"];
			$num=$sql->getSelectQ(false, "count(activities.id) as num", $tables, $tempClauses);
			$course["num"]=$num["num"];
			if(!isset($course["units"]))
			{
				$course["units"]=$sql->getSelectQ(true, array("title as unit", "id"), "units", array("deleted=\"0000-00-00 00:00:00\"", "course_id=".$course["id"]), array("title", "ASC"));
			};
			foreach($course["units"] as &$unit)
			{
				$tempClauses[9]="units.id=".$unit["id"];
				$num=$sql->getSelectQ(false, "count(activities.id) as num", $tables, $tempClauses);
				$unit["num"]=$num["num"];
				if(!isset($unit["lessons"]))
				{
					$unit["lessons"]=$sql->getSelectQ(true, array("title as lesson", "id"), "lessons", array("deleted=\"0000-00-00 00:00:00\"", "unit_id=".$unit["id"]), array("title", "ASC"));
				};
				foreach($unit["lessons"] as &$lesson)
				{
					$tempClauses[9]="lessons.id=".$lesson["id"];
					$num=$sql->getSelectQ(false, "count(activities.id) as num", $tables, $tempClauses);
					$lesson["num"]=$num["num"];
					if(!isset($lesson["chains"]))
					{
						$lesson["chains"]=$sql->getSelectQ(true, array("title as chain", "id"), "chains", array("deleted=\"0000-00-00 00:00:00\"", "lesson_id=".$lesson["id"]), array("title", "ASC"));
					};
					foreach($lesson["chains"] as &$chain)
					{
						$tempClauses[9]="chains.id=".$chain["id"];
						$num=$sql->getSelectQ(false, "count(activities.id) as num", $tables, $tempClauses);
						$chain["num"]=$num["num"];
						$chain["activities"]=$sql->getSelectQ(true, array("title as activity", "id"), "activities", array("deleted=\"0000-00-00 00:00:00\"", "chain_id=".$chain["id"], $tagsClause), array("title", "ASC"));
					};
				};
			};
		};
		echo json_encode($result);
	}

	function getCoursesList()
	{
		require_once("sql.class.php");
		$sql=new Sql("zeus");
		$result=$sql->getSelectQ(true, array("title", "id"), "courses", "deleted=\"0000-00-00 00:00:00\"", array("title", "ASC"));
		echo json_encode($result);
	}

	function getList($type, $pId)
	{
		$parents=array("unit"=>"course", "lesson"=>"unit", "chain"=>"lesson");
		require_once("sql.class.php");
		$sql=new Sql("zeus");
		$result=array
		(
			"type"=>$type,
			"vals"=>$sql->getSelectQ(true, array("title", "id"), $type."s", array("deleted=\"0000-00-00 00:00:00\"", $parents[$type]."_id=".$pId), array("title", "ASC"))
		);
		echo json_encode($result);
	}

	function getActivitiesList($pId, $pType=null, $tGroup)
	{
		require_once("sql.class.php");
		$sql=new Sql("zeus");
		$result=array();
		$tags=array
		(
			"actfl"=>array("oneone", "onetwo", "onethree", "twoone", "twotwo", "threeone", "threetwo", "fourone", "fourtwo", "fiveone", "fivetwo"),
			"skills"=>array("listening", "speaking", "reading", "writing"),
			"finalgrade"=>array("coursework", "outofbox", "teachergradeda", "unittest", "midterm", "finalg"),
			"other"=>array("culture", "teachergradedw", "teachergradeds", "selfgradeds", "selfgradedw")
		);
		if($pType==null)
		{
			$activities=$sql->getSelectQ(true, array("title", "data"), "activities", array("deleted=\"0000-00-00 00:00:00\"", "chain_id=".$pId), array("title", "ASC"));
			foreach($activities as $act)
			{
				$actR=array($act["title"]);
				$data=simplexml_load_string($act["data"]);
				foreach($tags[$tGroup] as $tag)
				{
					$actflBool="No";
					if(isset($data->tags))
					{
						if($data->tags->$tGroup->$tag->attributes()->value=="true")
						{
							$actflBool="Yes";
						};
					};
					array_push($actR, $actflBool);
				};
				array_push($result, $actR);
			};
		}
		else
		{
			$actR=$sql->getSelectQ(false, "title", $pType."s", "id=".$pId);
			$actR=array($actR["title"]);
			$tables=array("courses", "units", "lessons", "chains", "activities");
			$clauses=array
			(
				"courses.deleted=\"0000-00-00 00:00:00\"",
				"units.deleted=\"0000-00-00 00:00:00\"",
				"lessons.deleted=\"0000-00-00 00:00:00\"",
				"chains.deleted=\"0000-00-00 00:00:00\"",
				"activities.deleted=\"0000-00-00 00:00:00\"",
				"courses.id=units.course_id",
				"units.id=lessons.unit_id",
				"lessons.id=chains.lesson_id",
				"chains.id=activities.chain_id",
				$pType."s.id=".$pId
			);
			foreach($tags[$tGroup] as $tag)
			{
				$countClauses=$clauses;
				$countClauses[10]="activities.data like \"%<".$tag." value=\\\"true\\\"/>%\"";
				$count=$sql->getSelectQ(false, "count(activities.id) as count", $tables, $countClauses);
				array_push($actR, $count["count"]);
			};
			array_push($result, $actR);
		};
		echo json_encode($result);
	}
?>
