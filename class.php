<?php

class database {
	function konek(){
		define('DB_HOST','localhost');
		define('DB_USER','root');
		define('DB_PASS','');
		define('DB_NAME','kelulusan');
		$db_conn = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		return $db_conn;
	}
}