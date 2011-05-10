<?php
	class Sql
	{
		var $params=array();
		var $dbConnector;
		var $dbSelector;
		
		function getParams()
		{
			return $this->params;
		}
		function getDbConnector()
		{
			return $this->dbConnector;
		}
		function getDbSelector()
		{
			return $this->dbSelector;
		}

		function setParams($p)
		{
			$this->params=$p;
		}
		function setDbConnector($con)
		{
			$this->dbConnector=$con;
		}
		function setDbSelector($sel)
		{
			$this->dbSelector=$sel;
		}

		function Sql($db=null)
		{
			include("sqlparams.ini");
			$this->setParams($paramsIni);
			$this->connectDb($db);
		}

		function connectDb($db=null)
		{
			$pms=$this->getParams();
			$this->setDbConnector(mysql_connect($pms[$db]["host"], $pms[$db]["user"], $pms[$db]["password"]));
			if(!$this->getDbConnector())
			{
				
			}
			else
			{
				mysql_set_charset("utf8");
				if(isset($pms[$db]["dbname"]))
				{
					$this->selectDb($pms[$db]["dbname"]);
				};
			};
		}
		function selectDb($dbn)
		{
			$this->setDbSelector(mysql_select_db($dbn, $this->getDbConnector()));
			if(!$this->getDbSelector())
			{
				
			};
		}
		function getError()
		{
			return "MySQL error #".mysql_errno().":&nbsp;".mysql_error();
		}
		function getStrToArr($str)
		{
			if(!is_array($str)&&!is_null($str))
			{
				$str=array($str);
			};
			return $str;
		}
		function getSelectQ($multi, $fields, $tables, $clauses=null, $order=null, $limit=null, $group=null)
		{
			$fields=$this->getStrToArr($fields);
			$tables=$this->getStrToArr($tables);
			$clauses=$this->getStrToArr($clauses);
			$query="SELECT\n";
			$query.=addslashes(implode(", ", $fields));
			$query.="\nFROM\n";
			$query.=addslashes(implode(", ", $tables));
			if($clauses)
			{
				$query.="\nWHERE\n";
				$query.=implode("\nAND\n", $clauses);
			};
			if($order&&is_array($order))
			{
				$query.="\nORDER BY\n";
				$query.=addslashes(implode("\n", $order));
			};
			if($group&&is_array($group))
			{
				$query.="\nGROUP BY\n";
				$query.=addslashes(implode("\n", $group));
			};
			if($limit&&is_int($limit))
			{
				$query.="\nLIMIT\n";
				$query.=addslashes($limit);
			};
			$query.=";\n";
//echo $query;
			$run=mysql_query($query);
			if($run)
			{
				$selectArr=array();
				if(mysql_num_rows($run)!=0)
				{
					if($multi==true)
					{
						while($result=mysql_fetch_assoc($run))
						{
							array_push($selectArr, $result);
						};
					}
					else
					{
						while($result=mysql_fetch_assoc($run))
						{
							$selectArr=array_merge_recursive($selectArr, $result);
						};
					};
				};
				return $selectArr;
			}
			else
			{
				return false;
			};
		}
		function setInsertQ($fields, $table, $values)
		{
			$fields=$this->getStrToArr($fields);
			$values=$this->getStrToArr($values);
			if(count($fields)!=count($values))
			{
				die("Invalid query");
			};
			foreach($values as &$v)
			{
				$v=addslashes($v);
			};
			$query="INSERT\n";
			$query.="INTO\n";
			$query.=addslashes($table)."\n";;
			$query.="(".addslashes(implode(", ", $fields)).")\n";
			$query.="VALUES\n";
			$query.="(\"".implode("\", \"", $values)."\");\n";
			$run=mysql_query($query);
			if(!$run)
			{
				return false;
			};
			return true;
		}
		function getLastInsertId()
		{
			return mysql_insert_id();
		}
		function setUpdateQ($fields, $table, $values, $clauses=null, $limit=null)
		{
			$fields=$this->getStrToArr($fields);
			$values=$this->getStrToArr($values);
			$clauses=$this->getStrToArr($clauses);
			if(count($fields)!=count($values))
			{
				die("Invalid query");
			};
			$i=0;
			for($i=0; $i<count($fields); $i++)
			{
				$updateSets[$i]=addslashes($fields[$i])." = \"".addslashes($values[$i])."\"";
			};
			$query="UPDATE\n";
			$query.=addslashes($table);
			$query.="\nSET\n";
			$query.=implode(",\n", $updateSets);
			if($clauses)
			{
				$query.="\nWHERE\n";
				$query.=implode("\nAND\n", $clauses);
			};
			if($limit)
			{
				$query.="\nLIMIT\n";
				$query.=addslashes($limit);
			};
			$query.=";\n";
			$run=mysql_query($query);
			if(!$run)
			{
				return false;
			};
			return true;
		}
		function setDeleteQ($table, $clauses=null, $limit=null)
		{
			$clauses=$this->getStrToArr($clauses);
			$query="DELETE\n";
			$query.="FROM\n";
			$query.=addslashes($table);
			if($clauses)
			{
				$query.="\nWHERE\n";
				$query.=implode("\nAND\n", $clauses);
			};
			if($limit)
			{
				$query.="\nLIMIT\n";
				$query.=addslashes($limit);
			};
			$query.=";\n";
			$run=mysql_query($query);
			if(!$run)
			{
				return false;
			};
			return true;
		}
	}
?>
