<?php
/*
 Template Name: Event Search Results page
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
get_header();
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/date-fns/1.27.1/date_fns.min.js"></script>

			<div id="content" class="event-result">

				<div id="inner-content" class="wrap cf">

					<div class="sidebar d-1of7 t-1of3">
						<div id="filter-holder"></div>
					</div>

					<div class="body-content d-6of7 t-2of3">

						<h1>Event Search Result Test Page</h1>

						<?php

						$client = new SoapClient( WSDL );

						$params = array();
						$params[ 'websiteConfigID' ] = WEB_CONF_ID;
						$params[ 'searchTerms' ] = "ca";

						// account for no search term or a single result

						$result = $client->__soapCall( 'SearchEvents', array( 'parameters' => $params ) );

						if (is_soap_fault($result)) 
				      	{
				        	echo '<h2>Fault</h2><pre>';
				         	print_r($result);
				       		echo '</pre>';
				      	}

				      	// Grab an array of categories from the {prefix}_categories table, spit out a javascript array
				      	global $wpdb;
				      	$table = $wpdb->prefix . "categories";
				      	$catArray = $wpdb->get_results( "SELECT id, name FROM " . $table, OBJECT_K );

				      	echo "<script>var catArray = " . json_encode( $catArray ) . ";</script>";

				      	// find something like handlebars to manipulate with javascript
				      	// use Javascript "map" function to pop out arrays
				      	echo "<script>var result = " . json_encode( $result->SearchEventsResult->Event ) . ";</script>";

				      	//NOTE:
				      	// while building out list of events to be displayed, populate arrays that will be used to populate filters

						?>

						<script type="text/javascript">

							// initialize filters array
							var filters = {
								Days: [],
								Categories: [],
								Shows: [],
								Months: [],
								Venues: [],
								Times: []
							};

							// create array to hold original filters, to be used when resetting a single filter
							var defaultFilters = {
								Days: [],
								Categories: [],
								Shows: [],
								Months: [],
								Venues: [],
								Times: []
							};


							// function for populating the filter arrays
							// takes the initial "result" array at first, later instead takes a filtered array
							function populateFilters( theArray ) {
								//reset filters
								filters = {
									Days: [],
									Categories: [],
									Shows: [],
									Months: [],
									Venues: [],
									Times: []
								};

								theArray.forEach( function(item) {

									//day array first
									var day = dateFns.format(item.Date, "dddd");
									if( $.inArray(day, filters.Days) === -1 ) filters.Days.push(day);

									// grab any categories in result set
									var index = item.ChildCategoryID;
									cat = { id: index, name: catArray[index].name };
									var testCat = filters.Categories.filter(function ( obj ) {
									   return obj.id === item.ChildCategoryID;
									})[0];
									if( testCat === undefined ) filters.Categories.push(cat);

									// grab any shows/performers
									if( $.inArray(item.Name, filters.Shows) === -1 ) filters.Shows.push(item.Name);

									// grab any venues
									if ( $.inArray(item.Venue, filters.Venues) === -1 ) filters.Venues.push(item.Venue);

									// grab months events are happening in
									var month = dateFns.format(item.Date, "MMMM");
									if( $.inArray(month, filters.Months) === -1 ) filters.Months.push(month);

									// populate "time" array (assume "night" > 6PM)
									var time = dateFns.format(item.Date, "H") >= 18 ? "Night" : "Day";
									if ( $.inArray(time, filters.Times) === -1 ) filters.Times.push(time);
								});
							}

							// function for applying the filters to the result set, returns true or false (to be used with the JS "filter" method)
							function filterResults( item ) {
								var day = dateFns.format(item.Date, "dddd");
								var month = dateFns.format(item.Date, "MMMM");
								var time = dateFns.format(item.Date, "H") >= 18 ? "Night" : "Day";


								if( $.inArray(item.Name, filters.Shows) == -1 ) return false;
								if( $.inArray(day, filters.Days) == -1 ) return false;
								if( $.inArray(month, filters.Months) == -1 ) return false;
								if( $.inArray(time, filters.Times) == -1 ) return false;
								if( $.inArray(item.Venue, filters.Venues) == -1 ) return false;

								var cat = filters.Categories.filter(function ( obj ) {
								   return obj.id === item.ChildCategoryID;
								})[0];
								if( cat === undefined ) return false;

								return true;
							}

							// initial population of the filters to be manipulated
							populateFilters( result );

							// create array to hold original filters, to be used when resetting a single filter
							var defaultFilters = {
								Days: filters.Days,
								Categories: filters.Categories,
								Shows: filters.Shows,
								Months: filters.Months,
								Venues: filters.Venues,
								Times: filters.Times
							};

						</script>

						<div id="stache-holder"></div>

						<script type="text/javascript">
							// pagination helper
							Handlebars.registerHelper( "numResults", function( arrEvents, offset ) {
								offset = offset === undefined ? 0 : offset;
								return arrEvents.length == 0 ? null : arrEvents.slice(offset, offset + 25);
							} );

							Handlebars.registerHelper( "formatDate", function( rawDate ) {
								var date = new Date(rawDate);
								
								var formattedDay = dateFns.format( rawDate, "dddd" );
								var formattedDate = dateFns.format( rawDate, "MMMM D, YYYY" );
								var formattedTime = dateFns.format( rawDate, "h:mm a" );

								// build out and return the display
								return new Handlebars.SafeString(
									//day + "<br />" + month + " " + monthDay + ", " + year + "<br />" + hour + ":" + minute + " " + period
									formattedDay + "<br />" + formattedDate + "<br />" + formattedTime
								);
							});
							
							Handlebars.registerHelper( "buildTicketURL", function( ticketID ) {
								var getURL = window.location;
								var baseURL = getURL.protocol + "//" + getURL.host + "/" + getURL.pathname.split('/')[1];

								var ticketURL = baseURL + "/tickets/?eventID=" + ticketID;

								return ticketURL;
							});

							Handlebars.registerHelper( "buildList", function( filterName, theFilter ) {
								var html = "";
								if(theFilter instanceof Object) {
									html += "<li data-value='" + theFilter.id + "' data-name='" + filterName + "'>" + theFilter.name + "</li>";
								} else {
									html += "<li data-value='" + theFilter + "' data-name='" + filterName + "'>" + theFilter + "</li>";
								}
								return new Handlebars.SafeString( html );
							});
						</script>

						<script id="entry-template" type="text/x-handlebars-template">
							<table id="events-results-table">
								<tr id="header">
									<td>Date & Time</td>
									<td>Event</td>
									<td>Location</td>
									<td></td>
								</tr>
								{{#each (numResults theResult 0)}}

								<tr class="event-entry" >
									<td class="date">{{formatDate Date}}</td>
									<td class="show-title">{{Name}}</td>
									<td class="location">
										{{Venue}} <br />
										{{City}}, {{StateProvince}}
									</td>
									<td class="link"><a href="{{buildTicketURL ID}}" class="buy-tickets">Buy Tickets</a></td>
								</tr>
								{{/each}}
							</table>

						</script>

						<script id="filter-template" type="text/x-handlebars-template">
							
							{{#each filters as |filter name|}}
								<ul id={{name}}>
									<li data-value='all' data-name='{{name}}'>All {{name}}</li>
								{{#each filter}}
									{{buildList name this}}
								{{/each}}
								</ul>
							{{/each}}

						</script>

						<script type="text/javascript">
							var source   = $("#entry-template").html();
							var template = window.template = Handlebars.compile(source);

							var filterSource = $("#filter-template").html();
							var filterTemplate = window.filterTemplate = Handlebars.compile(filterSource);


							$("#filter-holder").append(filterTemplate( {filters:filters} ) );
							$("#stache-holder").append(template( {theResult:result} ) );

							
						</script>

						<script type="text/javascript">
							//let's start applying some filters!
							$("#filter-holder li").click( function() {
								var name = $(this).data("name");
								var data = $(this).data("value");

								//select filter array to change
								switch(name) {
									case "Days":
										if( data == "all" ) {
											console.log("all days clicked!");
											filters.Days = defaultFilters.Days;
										} else {
											filters.Days = [];
											filters.Days.push(data);
										}
										break;
									case "Categories":
										if( data == "all" ) {
											filters.Categories = defaultFilters.Categories;
										} else {
											var cat = {id: data, name: name};
											filters.Categories = [];
											filters.Categories.push(cat);
										}
										break;
									case "Shows":
										if( data == "all" ) {
											filters.Shows = defaultFilters.Shows;
										} else {
											filters.Shows = [];
											filters.Shows.push(data);
										}
										break;
									case "Months":
										if( data == "all" ) {
											filters.Months = defaultFilters.Months;
										} else {
											filters.Months = [];
											filters.Months.push(data);
										}
										break;
									case "Venues":
										if( data == "all" ) {
											filters.Venues = defaultFilters.Venues;
										} else {
											filters.Venues = [];
											filters.Venues.push(data);
										}
										break;
									case "Times":
										if( data == "all" ) {
											filters.Times = defaultFilters.Times;
										} else {
											filters.Times = [];
											filters.Times.push(data);
										}
										break;
								}

								var filteredResults = result.filter(filterResults);
								populateFilters(filteredResults);

								$("#stache-holder").html(template( {theResult:filteredResults} ) );
								$("#filter-holder").html(filterTemplate( {filters:filters} ) );

								console.log( "Defaults filters is",defaultFilters);
							});
							

						</script>

					</div>

				</div>
			</div>

<?php
//get_sidebar();
get_footer();
