jQuery(document).ready(function(){
	$("#month-selector").change( sendCalData );
	$("#venue-selector").change( sendCalData );
	$("#next-week").click( function(){
		console.log("a click!");
		var whatever = {
			action:"add_calendar",
			data:"something"
		};
		$.post( ticket_ajax.ajaxurl, whatever ).done( function(res){
			console.log(res)
		} );
	} );
});

function sendCalData() {
	var selectedData = {
		"monthVal" : $("#month-selector").val(),
		"venueVal" : $("#venue-selector").val()
	};
	var toPass = {
		action: "add_calendar",
		data: selectedData
	};
	$.post( ticket_ajax.ajaxurl, toPass ).done( function(res){
		console.log(res)
	} );
};