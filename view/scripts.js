$(document).ready(function() {

	var active_list = -1;

	/********************************
	 * Inputs
	 ********************************/

	// Buttons
	$('#add').click(function() {
		add_list();
	});

	// List selection
	$('#lists ul li').live('click', function() {
		load_list($(this).attr('id'));
	});

	// Name input
	$('#name').blur(rename_list);
	$('#name').keyup(function(e) {
		if (e.keyCode == 13)
			clear_input();
	});

	// Word input
	$('#type').keyup(function(e) {
		if (e.keyCode==13) {
			var word = $.trim(this.value);
			if (word != '') {
				display_word(word);
				save_word(word);
				clear_input();
			}
		} else if (e.keyCode == 27) {
			clear_input();
		}
	});

	/********************************
	 * Functions
	 ********************************/

	function get_lists() {
		// Clear
		$('#lists ul li').remove();
		// Get list ids
		$.getJSON('request.php', { call: 'getLists' }, function(lists) {
			// Fill dropdown with lists
			$.each(lists, function() {
				var list = this;
				$.getJSON('request.php', {
					call: 'getListName',
					list: list
				}, function(data) {
					$('#lists ul').append('<li id="' + list + '">' + data + '</li>');
				});
			});
			// Maybe load first
			if (active_list<0) load_list(lists[0]);
		});
	} get_lists();

	function load_list(list) {
		active_list = list;
		// Name
		$.getJSON('request.php', {
			call: 'getListName',
			list: active_list
		}, function(name) {
			$('#name').val(name);
		});
		// Words
		$.getJSON('request.php', {
			call: 'getWords',
			list: active_list
		}, function(words) {
			// Clear
			$('#list ul li').remove();
			// Fill list with words
			if (words) {
				$.each(words, function() {
					var word = this;
					$.getJSON('request.php', { call: 'getWordName', word: word }, function(word) {
						display_word(word);
					});
				});
			}
		});
		clear_input();
	}

	function add_list() {
		// Clear
		$('#list ul li').remove();
		// Create new list
		$.getJSON('request.php', { call: 'addList' }, function(data) {
			load_list(data);
		});
		get_lists();

		clear_input();
	}

	function rename_list() {
		var name = $('#name').val();

		// Server
		$.getJSON('request.php', {
			call: 'changeListName',
			list: active_list,
			name: name
		}, function(data) {
			$('#lists ul li#'+active_list).html(name);
		});
		// Client
		get_lists();

		clear_input();
	}

	// Add a word to list (server)
	function save_word(word) {
		word = $.trim(word);
		$.get('request.php', {
			call: 'addWord',
			list: active_list,
			name: word
		});
		clear_input();
	}

	// Remove words (client and server), disable right click on ui
	$('*').live('contextmenu', function() {
		if ($(this).is('#list ul li')) {
			var name = $(this).html();
			// Client
			$(this).css({
				width: $(this).css('width'),
				background: 'red'
			}).html('&nbsp;').stop().animate({
				width: 0,
				opacity: 0
			}, 500, function() {
				$(this).remove();
			}).css('overflow', 'visible');
			// Server
			$.getJSON('request.php', {
				call: 'getWordId',
				name: name
			}, function(word) {
				$.get('request.php', {
					call: 'deleteWord',
					list: active_list,
					word: word
				});
			});
		}
		clear_input();
		return false;
	});

	/********************************
	 * Style
	 ********************************/

	// Add a word to list (client)
	function display_word(word) {
		word = $.trim(word);

		// Check if words is already in list
		exist = $('#list ul li').filter(function() {
			return $(this).html() == word;
		});
		if (exist.length > 0) {
			exist.css('background-color', '#000');
			setTimeout(function() {
				exist.css('background-color', '#ccc');
			}, 800);
			return;
		}

		var container = $('#list span:contains(' + word.charAt(0).toString().toUpperCase() + ')');
		if (container.length <= 0)
			container = $('#list span:contains(#)');
		container.parent().children('ul').append('<li>' + word + '</li>');

		correct_styles();
	}

	// Saving spinner
	$('#saving').ajaxStart(function() {
		$(this).addClass('saving');
		$('#saving_text').html('operating');
	}).ajaxStop(function() {
		$(this).removeClass('saving');
		$('#saving_text').html('all saved');
	});

	// Style adaption
	var resizeevent = false;
	$(window).resize(function() {
		if (resizeevent !== false)
			clearTimeout(resizeevent);
		resizeevent = setTimeout(style_adaption, 100);
	});

});

/********************************
 * Helpers
 ********************************/

function clear_input() {
	$('#type').val('');
	$('#type').focus();
}

function style_adaption() {
	style_scaling();
	correct_styles();
}

function correct_styles() {
	$('#list ul li').css('line-height', $('#list ul li').innerHeight() + 'px');
}

function style_scaling() {
	var height = $(window).height();
	var size;
		 if (height > 650) size = 10;
	else if (height > 600) size =  9;
	else if (height > 550) size =  8;
	else if (height > 500) size =  7;
	else 				   size =  7;
	$('body').css('font-size',size);
}
function fullscreen_list() {
	if (is_fullscreen) {
		is_fullscreen = false;
		if (document.exitFullscreen) document.exitFullscreen();
		else if (document.mozCancelFullScreen) document.mozCancelFullScreen();
		else if (document.webkitCancelFullscreen) document.webkitCancelFullscreen();
		else if (document.webkitCancelFullScreen) document.webkitCancelFullScreen();
	} else {
		is_fullscreen = true;
		var doc = document.documentElement;
		if (doc.requestFullscreen) doc.requestFullscreen();
		else if (doc.mozRequestFullScreen) doc.mozRequestFullScreen();
		else if (doc.webkitRequestFullscreen) doc.webkitRequestFullscreen();
		else if (doc.webkitRequestFullScreen) doc.webkitRequestFullScreen();
	}
} var is_fullscreen = false;
