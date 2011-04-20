<?php
	$selFlds["users"]["pglms"]=array("id", "username", "email");
	$selFlds["users"]["speakez"]=array("id", "identifier as username", "identifier as email");
	$insFlds["users"]["pglms"]=array("email", "username", "school_id", "user_type_id", "user_type", "password");
	$insFlds["users"]["speakez"]=array("identifier", "provider_id", "editor", "migrator", "password");
	$selClssFlds["users"]["pglms"]=array("username"=>"username", "email"=>"email");
	$selClssFlds["users"]["speakez"]=array("username"=>"identifier", "email"=>"identifier");
	$insDefVals["users"]["pglms"]=array(1, 3, "admin", "M!dd138urY");
	$insDefVals["users"]["speakez"]=array(16, 1, 1, sha1("M!dd138urY"."@"."g4@tM:gPM,l?N271$8yL"));
	$updFlds["users"]["pglms"]=array
	(
		"username"=>"username",
		"email"=>"email"
	);
	$updFlds["users"]["speakez"]=array
	(
		"username"=>"identifier",
		"email"=>"identifier"
	);
	$selSch["course"]["pglms"]=array("assignments"=>array("courses_assgmts", "tasks"=>array("task_media"), "medias_assgmts"));
	$selSch["course"]["speakez"]=array("units"=>array("lessons"=>array("chains"=>array("activities"))));
	$selTbls["course"]["pglms"]=array
	(
		"assignments"=>array("assignments", "courses_assgmts"),
		"courses_assgmts"=>"courses_assgmts",
		"tasks"=>"tasks",
		"task_media"=>"task_media",
		"medias_assgmts"=>"medias_assgmts"
	);
	$selTbls["course"]["speakez"]=array
	(
		"units"=>"units",
		"lessons"=>"lessons",
		"chains"=>"chains",
		"activities"=>"activities"
	);
	$selFlds["course"]["pglms"]=array
	(
		"assignments"=>array("id", "assignment_type", "assignment_name", "assignment_description", "created_by", "created_on", "last_updated", "last_updated_by", "points", "locked"),
		"courses_assgmts"=>array("assignment_id as assignment_idPARENT", "course_id as course_idADDVAL", "day_number", "display_sequence"),
		"tasks"=>array("id", "assignment_id as assignment_idPARENT", "task_text", "task_type", "task_link", "task_order", "calculated_score", "assigned_score"),
		"task_media"=>array("id", "task_id as task_id#PARENT#", "media_id", "display_sequence", "task_text"),
		"medias_assgmts"=>array("assignment_id as assignment_idPARENT", "media_id", "display_sequence")
	);
	$selFlds["course"]["speakez"]=array
	(
		"units"=>array("id", "course_id as course_idPARENT", "origin_id", "editor", "sequence", "locked", "tasks", "title", "created", "modified", "deleted"),
		"lessons"=>array("id", "unit_id as unit_idPARENT", "origin_id", "editor", "practice", "deeper", "sequence", "locked", "tasks", "title", "created", "modified", "deleted"),
		"chains"=>array("id", "lesson_id as lesson_idPARENT", "origin_id", "editor", "sequence", "locked", "tasks", "title", "created", "modified", "deleted"),
		"activities"=>array("id", "lesson_id as lesson_idADDVAL", "chain_id as chain_idPARENT", "origin_id", "editor", "sequence", "locked", "scored", "tasks", "module", "title", "migrator", "created", "modified", "deleted", "data")
	);
	$selClss["course"]["pglms"]=array
	(
		"assignments"=>array("course_id=#DYNVAL#", "assignment_id=id"),
		"courses_assgmts"=>array("course_id=#ADDVAL#", "assignment_id=#DYNVAL#"),
		"tasks"=>"assignment_id=#DYNVAL#",
		"task_media"=>"task_id=#DYNVAL#",
		"medias_assgmts"=>"assignment_id=#DYNVAL#"
	);
	$selClss["course"]["speakez"]=array
	(
		"units"=>array("course_id=#DYNVAL#", "deleted=\"0000-00-00 00:00:00\""),
		"lessons"=>array("unit_id=#DYNVAL#", "deleted=\"0000-00-00 00:00:00\""),
		"chains"=>array("lesson_id=#DYNVAL#", "deleted=\"0000-00-00 00:00:00\""),
		"activities"=>array("chain_id=#DYNVAL#", "lesson_id=#ADDVAL#", "deleted=\"0000-00-00 00:00:00\"")
	);
	$services["pglms"]=array
	(
		array("pglms1 @ localhost", "loDBpglms1DBpglms"),
		array("pglms1 @ QA", "qaDBpglms1DBpglms"),
		array("pglms2 @ QA", "qaDBpglms2DBpglms")
	);
	$services["speakez"]=array
	(
		array("speakez @ localhost", "loDBspeakezDBspeakez"),
		array("speakez1 @ QA", "qaDBspeakez1DBspeakez"),
		array("speakez2 @ QA", "qaDBspeakez2DBspeakez")
	);
?>
