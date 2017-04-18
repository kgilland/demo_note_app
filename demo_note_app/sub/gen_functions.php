<?php 
/*
 * General PHP functions
 * 
 * Any functions required on multiple pages should go here
 */
function get_hash($s1) {
	//creates hash for passwords and password reset key
	$hash = sha1(md5($s1 . 'Cute=Puffin?4747'));
	return $hash;
}

/*
 * Read post/get parameters
 */
function getPassed($name, $def = 0) {
	if (isset($_GET[$name])) {
		return $_GET[$name];
	}
	elseif (isset($_POST[$name]))	{
		return $_POST[$name];
	}
	else {
		return $def;
	}
}
function getInteger($name, $def = 0) {
	$filtered = filter_var(getPassed($name, $def), FILTER_SANITIZE_NUMBER_INT);
	if ($filtered == '') {
		return $def;
	}
	return $filtered;
}
function getString($name, $def = "") {
	return trim(getPassed($name, $def));
}
/*
 * JSON return functions
 */
// error message JSON return
function jsonRetError($error) {
	$array = array();
	$array['error'] = $error;
	echo(json_encode($array));
	exit;
}
//general JSON return
function jsonReturn($array) {
	echo(json_encode($array));
	exit;
}

/*
 * Conversions
 */
function format_for_html($s) {
	//format string to display as html
	$s = str_replace("\"","&#34;",$s);
	$s = str_replace("'","&#39;",$s);
	$s = str_replace(">","&gt;",$s);
	$s = str_replace("<","&lt;",$s);
	return $s;
}
/*
 * MISC HTML Helpers
 */
function get_note_type_options_html() {
	//returns options for note type <select>
	$html = "";
	$rows = get_rows("SELECT id,label,color FROM note_type ORDER BY label");
	foreach ($rows as $row) {
		$selected = $id == 1 ? "selected" : "";
		$html .= "<option value=".$row['id']." style='background-color:".$row['color']."' $select>".$row['label']."</option>";
	}
	return $html;
}


?>