<?php 
/*
 * Database operations
 * 
 * Handles database connection and all database queries
 * 
 * Exceptions are logged to error log
 */

$dsn = 'mysql:host=localhost;port=3306;dbname=test';
$dbpdo = new PDO($dsn, "username", "password");
$dbpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbpdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

function do_query($sql,$params,$which) {
	global $dbpdo;
	try {
		$query = $dbpdo->prepare($sql);
		$query->execute($params);
		
		if ($which == 'scalar') {
			return $query->fetchColumn();
		}
		else if ($query->rowCount() > 0) {
			if ($which == 'row') {
				return $query->fetch(PDO::FETCH_ASSOC);
			}
			else if ($which == 'rows') {
				return $query->fetchAll(PDO::FETCH_ASSOC);
			}
			else if ($which == 'insert') {
				return $dbpdo->lastInsertId();
			}
			else {
				return $query->rowCount();
			}
		}
		return null; 
	}
	catch (Exception $e) {
		//LogError("($sql)".$e->getMessage());
		return null;
	}
}
/*
 * The following functions are not actually necessary but they make the code nicer to read
 */
function get_scalar($sql, $params) {
	//get 1st column of 1st row
	//returns single value
	return do_query($sql,$params,'scalar');
}
function get_row($sql, $params) {
	//get 1st row
	//returns array
	return do_query($sql,$params,'row');
}
function get_rows($sql, $params) {
	//get all rows & columns
	//returns array
	return do_query($sql,$params,'rows');
}
function get_row_count($sql, $params) {
	//Called with a SELECT query
	//returns rowCount
	return do_query($sql,$params,'');
}
function insert_record($sql, $params) {
	//execute insert
	//returns id of new record
	return do_query($sql,$params,'insert');
}
function update_db($sql, $params) {
	//All purpose function, used mainly for update and delete
	//returns rowCount
	return do_query($sql,$params,'');
}


?>