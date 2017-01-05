jQuery(document).ready(function(){
	$("#month-selector").change( sendCalData );
	$("#venue-selector").change( sendCalData );
	$("#events-table").on( "click", "#prev-week-btn", sendCalData );
	$("#events-table").on( "click", "#next-week-btn", sendCalData );
	$("#shows-listing-container").on( "click" , "#next-shows-btn", loadNextShows );
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

function loadNextShows() {
	console.log( "load them next shows!" );
	var showData = {
		"offset"	: $("#next-shows-offset").val(),
		"postID"	: $("#post-id").val()
	};
	var toPass = {
		action: "display_shows",
		data: showData 
	};
	$.post( ticket_ajax.ajaxurl, toPass ).done( function(res){
		$("#shows-listing-container").html( res );
		console.log(res)
	} );
}

function sendCalData() {
	var week = "";
	if ( $(this).is("a") ) {
		week = $(this).find("input").val();
	}
	var selectedData = {
		"monthVal"	: $("#month-selector").val(),
		"venueVal"	: $("#venue-selector").val(),
		"showID"	: $("#showID").val(),
		"week"		: week
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