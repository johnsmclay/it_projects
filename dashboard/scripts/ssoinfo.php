<?php
	if(isset($_POST["getSchools"]))
	{
		getSchoolsList();
	};
	if(isset($_POST["schoolId"]))
	{
		$sId=$_POST["schoolId"];
		getSchoolDetails($sId);
	};

	function getSchoolsList()
	{
		require_once("sql.class.php");
		$sql=new Sql("livepglms");
		$result=$sql->getSelectQ(true, array("schools.id as id", "school_name as sname", "description as name", "parent_id as parentid", "contact"), "schools, sections", array("secret is not null", "schools.id=sections.school_id", "sections.deleted=\"0000-00-00 00:00:00\"", "(\n\tsections.expires is null\n\tOR\n\tsections.expires=\"0000-00-00 00:00:00\"\n\tOR\n\tsections.expires>\"".date("Y-m-d H:i:s")."\"\n)", "schools.id!=1", "schools.id!=2", "schools.id!=177"), null, null, array("schools.id", "ASC"));
		foreach($result as &$sc)
		{
			if($sc["contact"]!=""&&$sc["contact"]!=null)
			{
				$contactInfo=$sql->getSelectQ(false, array("id", "user_type_id", "username", "first_name", "last_name", "email", "last_login"), "users", "id=".$sc["contact"]);
				$complete=true;
				foreach($contactInfo as $ci)
				{
					if($ci==""||$ci==null)
					{
						$complete=false;
					};
				};
				if($contactInfo["user_type_id"]!=2)
				{
					$sc["color"]="#FF8800";
					$sc["sso"]="Incorrect user type";
				};
				if($complete==true)
				{
					$sc["color"]="green";
					$sc["sso"]="Complete information: ".$contactInfo["id"]." - ".$contactInfo["first_name"]." ".$contactInfo["last_name"]." - ".$contactInfo["email"];
				}
				else
				{
					$sc["color"]="#FF8800";
					$sc["sso"]="Incomplete information";
				};
			}
			else
			{
				$contactInfo=$sql->getSelectQ(true, array("id", "username", "first_name", "last_name", "email", "last_login"), "users", array("user_type_id=2", "school_id=".$sc["id"]));
				if(isset($contactInfo[0]))
				{
					$numA=0;
					$numNA=0;
					foreach($contactInfo as &$ci)
					{
						$complete=true;
						foreach($ci as $ciField)
						{
							if($ciField==""||$ciField==null)
							{
								$complete=false;
							};
						};
						if($complete==true)
						{
							$numA++;
						}
						else
						{
							$numNA++;
						};
					};
					if($numA!=0)
					{
						$sc["color"]="blue";
						$sc["sso"]=$numA." available complete contact(s)";
					}
					else
					{
						$sc["color"]="red";
						$sc["sso"]=$numNA." available incomplete contact(s)";
					};
				}
				else
				{
					$sc["color"]="#552200";
					$sc["sso"]="No information available";
				};
			};
		};
		echo json_encode($result);
	}

	function getSchoolDetails($id)
	{
		require_once("sql.class.php");
		$sql=new Sql("livepglms");
		$result=$sql->getSelectQ(false, "description as school", "schools", "id=".$id);
		$result["users"]=$sql->getSelectQ(true, array("id", "username", "first_name as fname", "last_name as lname", "email"), "users", array("user_type_id=2", "school_id=".$id, "last_login is not null"));
		echo json_encode($result);
	}
?>
