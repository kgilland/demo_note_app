jQuery(document).ready(function() {
	get_note_list();
	
	//attach events for static objects
	$('input[value=Save]').on('click',function() {
		var note_id = $('.note_container .note_id').val();
		save_note(note_id)	
    });
	$('input[value=Cancel]').on('click',function() {
		close_note()	
    });
	$(".btn_add_note").on('click',function() {
		load_note(0,""); //new, blank note
	});	
	$(".btn_logout").on('click',function() {
		logout(); 
	});
});



//ASYNC call to server/database to get user's list of notes
function get_note_list () {
	var json_param = {};
	json_param['task']= "getNoteList";
	
	$(".loading").removeClass('hidden'); // show loading gif
	
	$.post("json/note_dbops.php", 
			json_param,
			function(data) {
				$(".loading").addClass('hidden'); //hide loading gif 
				
				if (data['error']) {
					// error
					$err_html = "<div class='err_msg'>" + data['error'] + "</div>";
					$('.note_list_container').html($err_html);
				}
				else if (data['no_auth']) {
					// user authorization failed (probably due to session time out)
					$err_html = "<div class='err_msg'>Session timed out. Refresh page to re-login.</div>";
					$('.note_list_container').html($err_html);
				}
				else
				{
					update_note_list_view(data['note_list_html'],data['user_name']);
				}
	});
}

//Attach events to dynamically created note list objects
function attach_note_list_events() {
	$('.edit_note').on('click',function() {
		var note_id = $(this).attr("id");
    	load_note(note_id);	
    });
	$('.delete_note').on('click',function() {
		var note_id = $(this).attr('id');
		if (confirm('Delete note?')) {
			delete_note(note_id);
		}
	});
	$('.show_note').on('click',function() {
		var id = $(this).attr('id');
		if ($('.note_list_info[id='+id+']').hasClass('hidden')) {
			$('.note_list_info[id='+id+']').removeClass('hidden');
		}
		else {
			$('.note_list_info[id='+id+']').addClass('hidden');
		}
		
		
	});
}

// Load single note view, if note_id not >0, load new, blank note
function load_note(note_id) {
	$('.note_container .note_id').val(note_id);
	if (note_id > 0) {
		$(".page_title").html("Note");
		
		get_saved_note(note_id);

	}
	else {
		//load new, blank note
		$(".page_title").html("New Note");
		
		clear_note_controls();
		
	}
	$('.note_list_container').addClass('hidden');
	$('.note_container').removeClass('hidden');
}

//Async call to Get selected note info
function get_saved_note(note_id) {
	var json_param = {};
	json_param['task']= "getNote";
	json_param['note_id']= note_id;
	$.post("json/note_dbops.php", 
			json_param,
			function(data) {
				$(".loading").addClass('hidden');
				if (data['error']) {
					// error
					$('.note_container .err_msg').html("Error retrieving note");
					return "";
				}
				else
				{
					$('.note_container textarea').val(data['note_text']);
					var value = data['note_type_id'];
					$(".sel_note_type").val(value);
					var title = data['note_title'];
					$('.note_container .title_text').val(title);
				}
	});
}

//Async call to Save current note
function save_note(note_id) {
	var note = $('.note_container textarea').val();
	var title = $('.note_container .title_text').val();
	var note_type_id = $('.sel_note_type').val();
	
	var json_param = {};
	json_param['task']= "saveNote";
	json_param['note_id']= note_id;
	json_param['note']= note;
	json_param['title']= title;
	json_param['note_type_id']= note_type_id;
	
	$.post("json/note_dbops.php", 
			json_param,
			function(data) {
				$(".loading").addClass('hidden');
				if (data['error']) {
					// error
					$('.note_container .err_msg').html(data['error']);
				}
				else
				{
					//update the note list
					update_note_list_view(data['note_list_html'],data['user_name']);
					close_note();
				}
	});
}
function delete_note(note_id) {
	var json_param = {};
	json_param['task']= "deleteNote";
	json_param['note_id']= note_id;
	$.post("json/note_dbops.php", 
			json_param,
			function(data) {
				$(".loading").addClass('hidden');
				if (data['error']) {
					// error
					$('.note_list_container .err_msg').html(data['error']);
				}
				else
				{
					//update the note list
					update_note_list_view(data['note_list_html'],data['user_name']);
				}
	});
}

//update the note list view with latest list and attach events
function update_note_list_view(note_list_html,user_name) {
	$('.tbl_note_list').html(note_list_html);
	$('.user_info').html("Notes for "+ user_name);
	$('.note_list_container .err_msg').html("");
	attach_note_list_events();
}

//Close single note view, open note list view
function close_note() {
	$(".page_title").html("Note List");
	
	clear_note_controls();
	
	//close
	$('.note_container').addClass('hidden');
	$('.note_list_container').removeClass('hidden');
}

//clear single note controls
function clear_note_controls() {
	$('.note_container .title_text').val("");
	$('.note_container textarea').val("");
	$(".sel_note_type").val(1);
	$('.note_container .err_msg').html("");
}

//User logs out
function logout() {
	var json_param = {};
	json_param['task']= "logout";
	$.post("json/note_signup_dbops.php", 
			json_param,
			function(data) {
				$(".loading").addClass('hidden'); //hide loading gif 
				
				if (data['error']) {
					// error
					$err_html = "<div class='err_msg'>" + data['error'] + "</div>";
					$('.note_list_container').html($err_html);
				}
				else {
					window.location.href = "login.php";
				}
	});
	
}