jQuery(document).ready(function(){
	$("#month-selector").change( sendCalData );
	$("#venue-selector").change( sendCalData );
	$("#events-table").on( "click", "#prev-week-btn", sendPrevWeek );
	$("#events-table").on( "click", "#next-week-btn", sendNextWeek );
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

function sendCalData( weekData ) {
	var selectedData = {
		"monthVal"	: $("#month-selector").val(),
		"venueVal"	: $("#venue-selector").val(),
		"showID"	: $("#showID").val(),
		"week"		: ""
	};
	var toPass = {
		action: "add_calendar",
		data: selectedData
	};
	$.post( ticket_ajax.ajaxurl, toPass ).done( function(res){
		$("#events-table").html( res );
		console.log(res)
	} );
};

function sendPrevWeek() {
	var selectedData = {
		"monthVal"	: $("#month-selector").val(),
		"venueVal"	: $("#venue-selector").val(),
		"showID"	: $("#showID").val(),
		"week"		: $("#prev-week").val()
	};
	var toPass = {
		action: "add_calendar",
		data: selectedData
	};
	$.post( ticket_ajax.ajaxurl, toPass ).done( function(res){
		$("#events-table").html( res );
		console.log(res)
	} );
};

function sendNextWeek() {
	var selectedData = {
		"monthVal"	: $("#month-selector").val(),
		"venueVal"	: $("#venue-selector").val(),
		"showID"	: $("#showID").val(),
		"week"		: $("#next-week").val()
	};
	var toPass = {
		action: "add_calendar",
		data: selectedData
	};
	$.post( ticket_ajax.ajaxurl, toPass ).done( function(res){
		$("#events-table").html( res );
		console.log(res)
	} );
};