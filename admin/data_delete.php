<?php
session_start();
if(isset($_SESSION['logged']) && !empty($_SESSION['logged'])){
include "../database.php";

	if(isset($_REQUEST['submit'])){
		echo $filename=$_FILES["file"]["tmp_name"];
		
		if($_FILES["file"]["size"] > 0){
			$file = fopen($filename, "r");
			
			mysqli_query($db_conn,"TRUNCATE TABLE un_siswa");
			
			while (($unData = fgetcsv($file, 10000, ";")) !== FALSE){
				//query insert
				$sql = "DELETE INTO un_siswa VALUES('$unData[0]','$unData[1]','$unData[2]','$unData[3]','$unData[4]','$unData[5]','$unData[6]','$unData[7]','$unData[8]','$unData[9]','$unData[10]','$unData[11]','$unData[12]','$unData[13]','$unData[14]','$unData[15]','$unData[16]','$unData[17]')";
				$res = mysqli_query($db_conn,$sql);
				
				if(! $res){
					echo "<script type=\"text/javascript\">alert(\"Invalid File!Please Upload CSV File.\");window.location = \"data.php\"</script>";
				}
			}
			
			fclose($file);
			
			echo "<script type=\"text/javascript\">alert(\"CSV File has been successfully Imported.\");window.location = \"data.php\"</script>";
		}
	} else {
		header('Location: data.php');
	}


} else {
	header('Location: ./login.php');
}
?>