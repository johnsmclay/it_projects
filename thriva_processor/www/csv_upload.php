<?php
  
if(isset($_FILES['uploadedfile']))
{
	//where uploaded CSVs will be stored
	$target_path = "uploads/";
	$target_file = $target_path . basename( $_FILES['uploadedfile']['name']);
	$uploaded_path_info = pathinfo($_FILES['uploadedfile']['name']);
	
	unlink($target_path.'ThrivaReport.csv');
	
	//echo 'Extension:'.$uploaded_path_info['extension'].'<br /><br />';//debug

	if($uploaded_path_info['extension'] == 'csv')
	{
		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_file))
		{
			echo "The file ".  $uploaded_path_info['basename']." has been uploaded<br /><br />";
		} else{
			echo "There was an error uploading the file, please try again!<br /><br />";
		}
		
		$today = date("Ymd_hms");
		copy($target_file,$target_path.'/archive/'.basename( $_FILES['uploadedfile']['name']).$today);
		rename($target_file,$target_path.'ThrivaReport.csv');

		$result=shell_exec("cd /home/www-data/data-integration; ./pan.sh -file '/home/www-data/data-integration/transformations/COO_report.ktr' | egrep 'error|Finished'");
	
		$pos = strpos($result,'error');
	
		if($pos === false) {
			echo 'No errors detected in kettle task results<br /><br />';
		}else{
			echo 'Errors were detected in the kettle task results:<br /><br />';
			echo($result);
		}
	
		if(isset($_REQUEST['debug']))
		{
			echo($result);
		}

	}else{
		echo "Only CSV files are accepted, please try again!<br /><br />";
	}
}
?>

