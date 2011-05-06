<?php
	if(isset($_POST["tags"]))
	{
		if($_POST["tags"]!="starttags")
		{
			$tags=explode("_xmltag_", $_POST["tags"]);
			searchActivities($tags);
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
	if(isset($_POST["actChainId"]))
	{
		$chainId=$_POST["actChainId"];
		getActivitiesList($chainId);
	};

	function searchActivities($tags)
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
			"chains.id=activities.chain_id",
		);
		$tagsClause="activities.data like \"%";
		foreach($tags as $tag)
		{
			$tagsClause.="<".$tag." value=\\\"true\\\"/>%";
		};
		$tagsClause.="\"";
		$result=$sql->getSelectQ(true, "title as course, id", "courses", "deleted=\"0000-00-00 00:00:00\"", array("title", "ASC"));
		foreach($result as &$course)
		{
			$tempClauses=$clauses;
			$tempClauses[9]="courses.id=".$course["id"];
			$num=$sql->getSelectQ(false, "count(activities.id) as num", $tables, $tempClauses);
			$course["num"]=$num["num"];
			$course["units"]=$sql->getSelectQ(true, "title as unit, id", "units", array("deleted=\"0000-00-00 00:00:00\"", "course_id=".$course["id"]), array("title", "ASC"));
			foreach($course["units"] as &$unit)
			{
				$tempClauses[9]="units.id=".$unit["id"];
				$num=$sql->getSelectQ(false, "count(activities.id) as num", $tables, $tempClauses);
				$unit["num"]=$num["num"];
				$unit["lessons"]=$sql->getSelectQ(true, "title as lesson, id", "lessons", array("deleted=\"0000-00-00 00:00:00\"", "unit_id=".$unit["id"]), array("title", "ASC"));
				foreach($unit["lessons"] as &$lesson)
				{
					$tempClauses[9]="lessons.id=".$lesson["id"];
					$num=$sql->getSelectQ(false, "count(activities.id) as num", $tables, $tempClauses);
					$lesson["num"]=$num["num"];
					$lesson["chains"]=$sql->getSelectQ(true, "title as chain, id", "chains", array("deleted=\"0000-00-00 00:00:00\"", "lesson_id=".$lesson["id"]), array("title", "ASC"));
					foreach($lesson["chains"] as &$chain)
					{
						$tempClauses[9]="chains.id=".$chain["id"];
						$num=$sql->getSelectQ(false, "count(activities.id) as num", $tables, $tempClauses);
						$chain["num"]=$num["num"];
						$chain["activities"]=$sql->getSelectQ(true, "title as activity, id", "activities", array("deleted=\"0000-00-00 00:00:00\"", "chain_id=".$chain["id"], $tagsClause), array("title", "ASC"));
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

	function getActivitiesList($cId)
	{
		require_once("sql.class.php");
		$sql=new Sql("zeus");
		$result=array();
		$activities=$sql->getSelectQ(true, array("title", "data"), "activities", array("deleted=\"0000-00-00 00:00:00\"", "chain_id=".$cId), array("title", "ASC"));
		$tags=array("oneone", "onetwo", "onethree", "twoone", "twotwo", "threeone", "threetwo", "fourone", "fourtwo", "fiveone", "fivetwo");
		foreach($activities as $act)
		{
			$actR=array($act["title"]);
			$data=simplexml_load_string($act["data"]);
			foreach($tags as $tag)
			{
				$actflBool="No";
				if(isset($data->tags))
				{
					if($data->tags->actfl->$tag->attributes()->value=="true")
					{
						$actflBool="Yes";
					};
				};
				array_push($actR, $actflBool);
			};
			array_push($result, $actR);
		};
//print_r($result);
		echo json_encode($result);
	}
?>
