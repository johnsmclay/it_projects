<?php
	if(isset($_POST["days"]))
	{
		$days=$_POST["days"];
		getNumRecords($days);
	};

	function getNumRecords($days)
	{
		$conn=ftp_connect("173.192.12.71");
		$log=ftp_login($conn, "sk3fmc5ply6w", "kkja08wut5j");
		ftp_pasv($conn, true);
		$num=ftp_recursive_rawlist_count($conn, "/FMS_Content/lms/streams", date("ymd", time()-($days*24*60*60)));
		ftp_close($conn);
		echo $num;
	}

	function ftp_recursive_rawlist_count($ftpconn, $folder, $date)
	{
		$numRec=0;
		ftp_chdir($ftpconn, $folder);
//		echo "<h2>Starting directory: ".ftp_pwd($ftpconn)."</h2>\n";
		$rawlist=ftp_rawlist($ftpconn, ftp_pwd($ftpconn));
		$i=0;
		$list=ftp_nlist($ftpconn, ftp_pwd($ftpconn));
		foreach($list as $val)
		{
//			echo "<h3>Current directory: ".ftp_pwd($ftpconn)."</h3>\n";
//			echo "<p>Try to change directory to: ".$val."</p>\n";
//			echo "<ul>\n";
			if(@ftp_chdir($ftpconn, $val))
			{
//				echo "<li>".ftp_pwd($ftpconn)."</li>\n";
				ftp_cdup($ftpconn);
				$numRec+=ftp_recursive_rawlist_count($ftpconn, $val, $date);
			}
			else
			{
				if(preg_match("/^.*\.flv$/", $rawlist[$i]))
				{
					$fileDateArr=explode(" ", $rawlist[$i]);
					$fileDate=substr(preg_replace("/-/", "", $fileDateArr[0]), -2).substr(preg_replace("/-/", "", $fileDateArr[0]), 0, 4);
					if($date>=$fileDate)
					{
						$numRec++;
//					}
//					else
//					{
//						echo "<li>".$rawlist[$i]."</li>\n";
//						echo "<li>Date:".$date."</li>\n";
//						echo "<li>File date:".$fileDate."</li>\n";
					};
				};
			};
//			echo "<li>".ftp_pwd($ftpconn)."</li>\n";
//			echo "</ul>\n";
			$i++;
		};
		ftp_cdup($ftpconn);
		return $numRec;
	}
?>
