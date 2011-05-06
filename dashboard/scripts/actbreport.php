<?php
	if(isset($_POST["tags"]))
	{
		if($_POST["tags"]!="starttags")
		{
			$tags=explode("_xmltag_", $_POST["tags"]);
			$isChecked=$_POST["isChecked"];
			searchActivities($tags, $isChecked);
		};
	};
	if(isset($_POST["actid"]))
	{
		$actId=$_POST["actid"];
		activityDetails($actId);
	};

	function searchActivities($tags, $chkd)
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
			$tagsClause.="<".$tag." value=\\\"".$chkd."\\\"/>%";
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
//print_r($result);
		echo json_encode($result);
	}

	function activityDetails($actId)
	{
		require_once("sql.class.php");
		$sql=new Sql("zeus");
		$result=array();
		$queryResult=$sql->getSelectQ(false, array("title", "data"), "activities", "id=".$actId);
		$result["title"]=$queryResult["title"];
		$tagName=array
		(
			"initialbuild"=>"Initial build",
			"mediaplaced"=>"Media placed",
			"functional"=>"functionnal",
			"targetedit"=>"Target edit",
			"englishedit"=>"English edit",
			"buildlayout"=>"Layout",
			"writerreview"=>"Writer review"
		);
		$data=simplexml_load_string($queryResult["data"]);
		$result["tags"]=array();
		foreach($data->tags->builder->children() as $tag)
		{
			if($tag->attributes()->value=="true")
			{
				array_push($result["tags"], $tagName[$tag->getName()]);
			};
		};
		echo json_encode($result);
	}
?>
