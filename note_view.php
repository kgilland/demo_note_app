<?php
/*
 * Note View Page
 * 
 * Note list view and single note view
 */

$current_page = "note_view.php";

require_once('sub/head.php'); //page header
?>
<div class='page_title'>Note List</div>
<div class='page_contents'>
	<div class='loading hidden'><img src='img/ajax-loader.gif'> Loading ...</div>
	<div class='note_list_container section'>
		<table class='tbl_note_list_heading' style='width:100%'>
			<tr>
				<td class='user_info'></td>
				<td class='btn_add_note' style='text-align:right'>
					<span class='icon_button'>
						<img src='img/add.png'>
						<span class="tooltiptext">open new note</span>
					</span>
				</td>
				<td class='btn_logout' style='text-align:right'>
					<span class='icon_button'>
						<img src='img/logout.png'>
						<span class="tooltiptext">log out</span>
					</span>
				</td>
			</tr>
		</table>
		<table class='tbl_note_list' style='width:100%'>
			<tr>
				<td>No Notes saved</td>
			</tr>
		</table>
		<div class='form_element'>
			<span class='err_msg'></span>
		</div>
	</div>
	<div class='note_container section hidden'>
		<input class='note_id' type='hidden'>
		<div class='form_element'>
			Title:<br>
			<input class='title_text' type=text>
		</div>
		<div class='form_element'>
			Note:<br>
			<textarea></textarea>
		</div>
		<div class='form_element'>
			Category:<br>
			<select class='sel_note_type'><?php echo get_note_type_options_html();?></select>
		</div>
		<div class='form_element'>
			<input type='submit' value='Save' />
			<input type='submit' value='Cancel' />
		</div>
		<div class='form_element'>
			<span class='err_msg'></span>
		</div>
	</div>
</div> <!-- page contents -->
<?php 
require_once('sub/footer.php');


?>
