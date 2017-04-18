<?php
/*
 * Note page database operations, called from js scripts
 * 
 * Gets/updates database note info from database
 * 
 * Queries the database and return JSON format data
 * 
 */
$PHP_SELF = $_SERVER['PHP_SELF'];
$REQUEST_URI = $_SERVER['REQUEST_URI'];
//require_once('sub/load_config.php');
require_once('../sub/database_ops.php');
require_once('../sub/gen_functions.php');

header('Content-type: application/json');

//response array:
$resp = array();

//Authorization
//No data returned from this page unless user is logged in
$json_return = true; //return auth result as json, not page redirect
require('../sub/auth.php'); 

//passed parameters:
$task = getString('task','');
$note_id = getInteger('note_id',0);
$title = getString('title','');
$note = getString('note','');
$note_type_id = getInteger('note_type_id',1);

$resp['user_name'] = get_user_name($user_id);

if ($task == 'getNoteList') {	
	$resp['note_list_html'] = get_note_list($user_id);	
	jsonReturn($resp);
}
elseif ($task == 'saveNote') {
	$note_id = save_note($note_id,$note,$title,$user_id,$note_type_id);
	$resp['note_list_html'] = get_note_list($user_id);
	jsonReturn($resp);
}
elseif ($task == 'getNote') {
	$resp = get_note_info($note_id);
	jsonReturn($resp);
}
elseif ($task == 'deleteNote') {
	$nrows = delete_note($note_id);
	$resp['note_list_html'] = get_note_list($user_id);
	jsonReturn($resp);
}
else {
	//LogError("unknown task request: $task");
	jsonRetError("Error retrieving data");
}
exit;

/*
 * FUNCTIONS FOR TASKS
 */

/*
 * Get and format HTML table of note titles and ids, return string message if no notes
 * 
 * Return string (HTML table or simple string)
 * 
*/
function get_note_list($user_id) {
	
	$return = "No notes found.";
	$sql = "SELECT note.id,title,content,color,
        	CASE WHEN DATEDIFF(NOW(),note.created_at) > 7 
        	THEN DATE_FORMAT(note.created_at,'%m/%d/%Y %I:%i %p') 
        	ELSE CONCAT(LEFT(DATE_FORMAT(note.created_at,'%W'),3),' ',DATE_FORMAT(note.created_at,'%I:%i %p')) 
        	END as created,
        	user.name as username FROM note 
			LEFT OUTER JOIN note_type ON note.note_type_id = note_type.id 
			LEFT OUTER JOIN user ON user_id = user.id
        	WHERE user_id=:user_id";
	$params = array(':user_id' => $user_id);
	$notes = get_rows($sql,$params);
	
	$rows_html = "";
	foreach ($notes as $note) {
		$title = format_for_html($note['title']);
		$text = format_for_html($note['content']);
		$created = $note['created'];
		$username = $note['username'];
		
		$text .= "<br><br><i>$created</i>";

		
		$del_control = "<span class='icon_button'><img class='delete_note' id=".$note['id']." src='img/delete.png'><span class='tooltiptext'>delete note</span></span>";
		$edit_control = "<span class='icon_button'><img class='edit_note' id=".$note['id']." src='img/edit.png'><span class='tooltiptext'>edit note</span></span>";
		$info_control = "<span class='icon_button'><img class='show_note' id=".$note['id']." src='img/info2.png'><span class='tooltiptext'>show/hide note info</span></span>";
		
		$rows_html .= "<tr class='tr_title'>";
		$rows_html .= "<td class='note_list_item_type' style='background-color:".$note['color']."'>&nbsp;</td>";
		$rows_html .= "<td class='note_list_item_title' id=".$note['id'].">".$title."</td>";
		$rows_html .= "<td class='note_list_item_controls'>$info_control&nbsp;$edit_control&nbsp;$del_control</td>";
		$rows_html .= "</tr>";
		$rows_html .= "<tr class='note_list_info hidden' id=".$note['id'].">";
		$rows_html .= "<td class='note_list_item_type' style='background-color:".$note['color']."'>&nbsp;</td>";
		$rows_html .= "<td colspan=2>$text</td>";
		$rows_html .= "</tr>";
		
	}
	
	if ($rows_html != "") {
		$return = $rows_html;
	}
	
	return $return;
}
/*
 * Get the title , content and type_id of a single note
 * 
 * Return array
*/
function get_note_info($note_id) {
	$sql = "SELECT content,title,note_type_id FROM note WHERE id=:note_id";
	$params = array(':note_id' => $note_id);
	$items= get_row($sql,$params);

	$resp = array();
	$resp['note_title'] = $items['title'];
	$resp['note_text'] = $items['content'];
	$resp['note_type_id'] = $items['note_type_id'];

	return $resp;
}
/*
 * Gets user name from user_id
 * 
 * Return string
*/
function get_user_name($user_id) {
	$sql = "SELECT name FROM user WHERE id=:user_id";
	$params = array(':user_id' => $user_id);
	$name = get_scalar($sql,$params);
	return $name;
}
/*
 * Save note: insert new note or update existing note
 * 
 * Return value if successful or JSON formatted error if not successful
 * 
 */
function save_note($note_id,$note,$title,$user_id,$note_type_id) {
	
	if ($note_id > 0) {
		$sql = "UPDATE note SET title=:title,content=:note,note_type_id=:note_type_id WHERE id=:note_id";
		$params = array(':title' => $title,':note' => $note,':note_id' => $note_id,':note_type_id' => $note_type_id);
		$nrows = update_db($sql,$params);
		if ($nrows > 0) {
			return $nrows;
		}
		else {
			jsonRetError("Note not updated.");
		}
	}
	else {
		$sql = "INSERT INTO note (title,content,user_id,note_type_id,created_at) VALUES (:title,:note,:user_id,:note_type_id,NOW())";
		$params = array(':title' => $title,':note' => $note,':user_id' => $user_id,":note_type_id" => $note_type_id);
		$note_id = insert_record($sql,$params);
		if ($note_id > 0) {
			return $note_id;
		}
		else {
			jsonRetError("Error creating note.");
		}
	}
	
}
/*
 *  Delete note
 * 
 * Return value if successful or JSON formatted error if not successful
 */
function delete_note($note_id) {
	$sql = "DELETE FROM note WHERE id = :note_id";
	$params = array(":note_id" => $note_id);
	$nrows = update_db($sql,$params);
	if ($nrows > 0) {
		return $nrows;
	}
	else {
		jsonRetError("Error deleting note.");
	}
}
?>