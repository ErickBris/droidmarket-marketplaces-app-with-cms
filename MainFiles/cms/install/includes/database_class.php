<?php

class Database {

	// Function to the database and tables and fill them with the default data
	function create_database($data)
	{

		try {
			$mysqli = new mysqli($data['hostname'],$data['username'],$data['password'],'');
		} catch (Exception $e) {
			echo "notfound";
		}

		// Check for errors
		if(mysqli_connect_errno())
			return false;

		// Create the prepared statement
		$mysqli->query("CREATE DATABASE IF NOT EXISTS ".$data['database']);

		if(mysqli_error($mysqli)) {
			die(mysqli_error($db_link));
			return false;
		}

		// Close the connection
		$mysqli->close();

		return true;
	}

	// Function to create the tables and fill them with the default data
	function create_tables($data)
	{
		// Connect to the database
		$mysqli = new mysqli($data['hostname'],$data['username'],$data['password'],$data['database']);

		// Check for errors
		if(mysqli_connect_errno())
			return false;

		// Open the default SQL file
		$query = file_get_contents('assets/install.sql');

		ini_set('max_execution_time', 300);
		// Execute a multi query
		$mysqli->multi_query($query);

		do {
			if($result = mysqli_store_result($mysqli)){
				mysqli_free_result($result);
			}
		} while(mysqli_next_result($mysqli));

		if(mysqli_error($mysqli)) {
			die(mysqli_error($db_link));
			return false;
		}

		// Close the connection
		$mysqli->close();

		return true;
	}
}