$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover();   
	renderPartial();
});

function renderPartial() {
	var array_input = [];
	var button = {};
	var array_input = {};

	$(document).on('click', '.ajax_r', function(e) {
		var url = getAbsolutePath();
		var current = $(this).parent('.current_data');
		var array_div = $(current).find(':input');
		$.each(array_div, function(k, v) {
			if($(this).get(0).tagName === "INPUT") {
				array_input[$(this).attr("name")] = $(this).val();
			}
			else {
				button[$(this).attr('name')] = $(this).val();
			}
		});
		$.ajax({
			url: url + 'replace_partial',
			type: 'POST',
			data: {input : JSON.stringify(array_input),
				id : JSON.stringify(button)},
			})
		.done(function(data) {
			if(data === "") {
				return;
			}
			var img = '<img id="img_ok" src="../static/img/success.png" alt="ok">';
			$('#data').html(data);
			var modif = $("#" + Object.values(button)[0] + "modif").html();
			$("#" + Object.values(button)[0] + "modif").html(img);
			$('#img_ok').delay(1000).fadeOut('slow');
			setTimeout(function(){
				$("#" + Object.values(button)[0] + "modif").html(modif); 
			}, 1500);
		})

		function getAbsolutePath() {
			var loc = window.location;
			var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
			return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + 
				loc.hash).length - pathName.length));
		}
	});


		$(document).on('click', '.ajax_d', function(e) {
		var url = getAbsolutePath();
		var current = $(this).parent('.current_data');
		var div = $(current).find(':input')[0];
		 button[$(div).attr('name')] = $(div).val();

		$.ajax({
			url: url + 'remove_partial',
			type: 'POST',
			data: {id : JSON.stringify(button)},
			})
		.done(function(data) {
			if(data === "") {
				return;
			}
			$('#data').html(data);
		})

		function getAbsolutePath() {
			var loc = window.location;
			var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
			return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + 
				loc.hash).length - pathName.length));
		}
	});
}