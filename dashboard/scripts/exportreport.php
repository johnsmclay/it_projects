<?php
	if(isset($_GET["courseId"]))
	{
		exportReport($_GET["courseId"]);
	};

	function exportReport($cId)
	{
		require_once("sql.class.php");
		require_once("PHPExcel.php");
		$phpExcelReader=new PHPExcel_Reader_Excel5();
		$phpxl=new PHPExcel();
		$tab=$phpxl->getActiveSheet();
		$tab->setTitle("ACTFL report");
		$sql=new SQL("zeus");
		$tagsXML=array
		(
			"actfl"=>array("oneone", "onetwo", "onethree", "twoone", "twotwo", "threeone", "threetwo", "fourone", "fourtwo", "fiveone", "fivetwo"),
			"skills"=>array("listening", "speaking", "reading", "writing"),
			"finalgrade"=>array("coursework", "outofbox", "teachergradeda", "unittest", "midterm", "finalg"),
			"other"=>array("culture", "teachergradedw", "teachergradeds", "selfgradeds", "selfgradedw")
		);
		$tagsNames=array
		(
			"actfl"=>array("1.1", "1.2", "1.3", "2.1", "2.2", "3.1", "3.2", "4.1", "4.2", "5.1", "5.2"),
			"skills"=>array("Listening", "Speaking", "Reading", "Writing"),
			"finalgrade"=>array("Course work", "Out of the box project", "Teacher-graded activities", "Unit test", "Midterm", "Final"),
			"other"=>array("Culture", "Teacher-graded writing", "Teacher-graded speaking", "Self-graded speaking", "Self-graded writing")
		);
		$course=$sql->getSelectQ(false, array("id", "title"), "courses", "id=".$cId);
		$tab->setCellValue("A1", $course["title"]." ACTFL list");
		$tab->setCellValue("A2", "reported on ".date("l, F jS, Y")." at ".date("g:i:sa T"));
		$tab->setCellValue("A4", "ACTFL standards");
		$tab->setCellValue("A5", "1.1: Students engage in conversations, provide and obtain information, express feelings and emotions, and exchange opinions.");
		$tab->setCellValue("A6", "1.2: Students understand and interpret written and spoken language on a variety of topics.");
		$tab->setCellValue("A7", "1.3: Students present information, concepts, and ideas to an audience of listeners or readers on a variety of topics.");
		$tab->setCellValue("A8", "2.1: Students demonstrate an understanding of the relationship between the practices and perspectives of the culture studied.");
		$tab->setCellValue("A9", "2.2: Students demonstrate an understanding of the relationship between the products and perspectives of the culture studied.");
		$tab->setCellValue("A10", "3.1: Students reinforce and further their knowledge of other disciplines through the foreign language.");
		$tab->setCellValue("A11", "3.2: Students acquire information and recognize the distinctive viewpoints that are only available through the language and its cultures.");
		$tab->setCellValue("A12", "4.1: Students demonstrate understanding of the nature of language through comparisons of the language studied and their own.");
		$tab->setCellValue("A13", "4.2: Students demonstrate understanding of the concept of culture through comparisons of the cultures studied and their own.");
		$tab->setCellValue("A14", "5.1: Students use the language both within and beyond the school setting.");
		$tab->setCellValue("A15", "5.2: Students show evidence of becoming life-long learners by using the language for personal enjoyment and enrichment.");
		$headStyles=array
		(
			"font"=>array
			(
				"bold"=>true,
				"size"=>16
			),
			"alignment"=>array
			(
				"horizontal"=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			)
		);
		$tab->getStyle("A1:A2")->applyFromArray($headStyles);
		$tab->getStyle("A4")->getFont()->setBold(true);
		$i=1;
		for($i=1; $i<17; $i++)
		{
			$tab->mergeCells("A".$i.":G".$i);
		};
		$tab->getStyle("A1:G15")->getAlignment()->setWrapText(true);
		$tab->getColumnDimension("A")->setWidth(8);
		$tab->getColumnDimension("B")->setWidth(8);
		$tab->getColumnDimension("C")->setWidth(80);
		$tab->getColumnDimension("D")->setWidth(8);
		$tab->getColumnDimension("E")->setWidth(10);
		$tab->getColumnDimension("F")->setWidth(20);
		$tab->getColumnDimension("G")->setWidth(20);
		$unitRow=17;
		$course["units"]=$sql->getSelectQ(true, array("id", "title"), "units", array("deleted=\"0000-00-00 00:00:00\"", "course_id=".$cId), array("title", "ASC"));
		foreach($course["units"] as &$unit)
		{
			$unit["lessons"]=$sql->getSelectQ(true, array("id", "title"), "lessons", array("deleted=\"0000-00-00 00:00:00\"", "unit_id=".$unit["id"]), array("title", "ASC"));
			$tab->setCellValue("A".$unitRow, $unit["title"]);
			$unitStyles=array
			(
				"font"=>array
				(
					"bold"=>true,
					"size"=>14
				),
				"fill"=>array
				(
					"type"=>PHPExcel_Style_Fill::FILL_SOLID,
					"color"=>array
					(
						"rgb"=>"00CC00"
					)
				),
				"alignment"=>array
				(
					"horizontal"=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER
				)
			);
			$tab->getStyle("A".$unitRow.":G".$unitRow)->applyFromArray($unitStyles);
			$tab->mergeCells("A".$unitRow.":G".$unitRow);
			$lessonRow=$unitRow+1;
			foreach($unit["lessons"] as &$lesson)
			{
				$lesson["activities"]=$sql->getSelectQ(true, array("activities.title as title", "data"), array("activities", "chains"), array("chains.deleted=\"0000-00-00 00:00:00\"", "activities.deleted=\"0000-00-00 00:00:00\"", "lesson_id=".$lesson["id"], "chain_id=chains.id"), array("activities.title", "ASC"));
				$tab->setCellValue("B".$lessonRow, $lesson["title"]);
				$lessonStyles=array
				(
					"font"=>array
					(
						"bold"=>true,
						"size"=>12
					),
					"fill"=>array
					(
						"type"=>PHPExcel_Style_Fill::FILL_SOLID,
						"color"=>array
						(
							"rgb"=>"00AAFF"
						)
					),
					"alignment"=>array
					(
						"horizontal"=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER
					)
				);
				$tab->getStyle("B".$lessonRow.":G".$lessonRow)->applyFromArray($lessonStyles);
				$tab->mergeCells("B".$lessonRow.":G".$lessonRow);
				$actFldsRow=$lessonRow+1;
				$tab->setCellValue("C".$actFldsRow, "Activity title");
				$tab->setCellValue("D".$actFldsRow, "ACTFL");
				$tab->setCellValue("E".$actFldsRow, "Skills");
				$tab->setCellValue("F".$actFldsRow, "Final grade");
				$tab->setCellValue("G".$actFldsRow, "Other");
				$tab->getStyle("C".$actFldsRow.":G".$actFldsRow)->getFont()->setBold(true);
				foreach($lesson["activities"] as &$activity)
				{
					$xml=simplexml_load_string($activity["data"]);
					$activity=array("title"=>$activity["title"]);
					foreach($tagsXML as $key=>$tagArr)
					{
						$i=0;
						$actTags=array();
						for($i=0; $i<count($tagsXML[$key]); $i++)
						{
							if(isset($xml->tags->$key))
							{
								if($xml->tags->$key->$tagsXML[$key][$i]->attributes()->value=="true")
								{
									array_push($actTags, $tagsNames[$key][$i]);
								};
							};
						};
						$activity[$key]=implode("\n", $actTags);
					};
					$actRow=$tab->getHighestRow()+1;
					$tab->setCellValue("C".$actRow, $activity["title"]);
					$tab->setCellValue("D".$actRow, $activity["actfl"]);
					$tab->setCellValue("E".$actRow, $activity["skills"]);
					$tab->setCellValue("F".$actRow, $activity["finalgrade"]);
					$tab->setCellValue("G".$actRow, $activity["other"]);
					$tab->getStyle("D".$actRow.":G".$actRow)->getAlignment()->setWrapText(true);
				};
				$actStyles=array
				(
					"borders"=>array
					(
						"horizontal"=>array
						(
							"style"=>PHPExcel_Style_Border::BORDER_THIN,
							"color"=>array
							(
								"rgb"=>"000000"
							)
						),
						"vertical"=>array
						(
							"style"=>PHPExcel_Style_Border::BORDER_THIN,
							"color"=>array
							(
								"rgb"=>"000000"
							)
						),
						"allborders"=>array
						(
							"style"=>PHPExcel_Style_Border::BORDER_THIN,
							"color"=>array
							(
								"rgb"=>"000000"
							)
						)
					)
				);
				$tab->getStyle("C".($lessonRow+1).":G".$tab->getHighestRow())->applyFromArray($actStyles);
				$tab->getStyle("B".($lessonRow+1).":B".$tab->getHighestRow())->applyFromArray($lessonStyles);
				$tab->mergeCells("B".($lessonRow+1).":B".$tab->getHighestRow());
				$lessonStyles=array
				(
					"borders"=>array
					(
						"allborders"=>array
						(
							"style"=>PHPExcel_Style_Border::BORDER_THIN,
							"color"=>array
							(
								"rgb"=>"000000"
							)
						)
					)
				);
				$tab->getStyle("B".$lessonRow.":G".$tab->getHighestRow())->applyFromArray($lessonStyles);
				$lessonRow=$tab->getHighestRow()+1;
			};
			$tab->getStyle("A".($unitRow+1).":A".$tab->getHighestRow())->applyFromArray($unitStyles);
			$tab->mergeCells("A".($unitRow+1).":A".$tab->getHighestRow());
			$unitStyles=array
			(
				"borders"=>array
				(
					"allborders"=>array
					(
						"style"=>PHPExcel_Style_Border::BORDER_THIN,
						"color"=>array
						(
							"rgb"=>"000000"
						)
					)
				)
			);
			$tab->getStyle("A".$unitRow.":G".$tab->getHighestRow())->applyFromArray($unitStyles);
			$unitRow=$tab->getHighestRow()+1;
		};
		$rowStyles=array
		(
			"alignment"=>array
			(
				"horizontal"=>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
			)
		);
		$tab->getStyle("D1:G".$tab->getHighestRow())->applyFromArray($rowStyles);
		$phpExcelWriter=new PHPExcel_Writer_Excel5($phpxl);
		$phpExcelWriter->setPreCalculateFormulas(false);
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment;filename=\"course_".$course["id"]."_ACTFL_report.xls\"");
		header("Cache-Control: max-age=0");
		$phpExcelWriter->save("php://output");
	}
?>
