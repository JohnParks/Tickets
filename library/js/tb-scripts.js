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
    $('#menu-main-nav li:first-of-type').hover(revealShowMenu);
    $('#drop-down-shows').mouseleave(function(e){
        //$('#drop-down-shows').css('display', "none");
        $('#drop-down-shows').slideToggle();
    });
    $('.ul-genre-list ul li').each(function(n,el){
        console.log(el);
        $(el).hover(function(e){
            $('.ul-genre-list ul li a').each(function(n,el){
                $(el).removeClass('active');
            });
            $(el).find('a').addClass('active');                              
            $('.genre-show-list').each(function(n,el){
                $(el).css('display', 'none');
            })
            var genre = e.target.dataset.genre;
            var su_el = $('#genre-show-list-'+genre);
            su_el.toggle();
            su_el.mouseleave(function(){
                //su_el.css('display', 'none');
            })
        },function(e){

        });
    })
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
	console.log(toPass);
	$.post( ticket_ajax.ajaxurl, toPass ).done( function(res){
		$("#events-table").html( res );
		console.log(res)
	} );
};
var showMenuTimeout;
var revealTimeout;
function revealGenreShows(e){
    
}

function revealShowMenu(){
    var el = $('#drop-down-shows');
    if(el.css('display') == "none"){
        el.slideToggle();
        $('body').click(function(e){
            if(el.css('display') !== "none"){
                el.click(function(e){
                    console.log("clicked inside div");
                    e.stopPropagation();
                })
                console.log("clicked outside div");
                el.slideToggle();
            }
        });
        
        
    }
}