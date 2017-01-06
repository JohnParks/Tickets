<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ticketsbroadway
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<h1>THIS IS A TEST PAGE!</h1>

			<?php

			$conn = new TicketNetworkConnection();

			// snag the three constant arrays containing categories, unserialize them
			$parentCats = unserialize( PARENT_CATS );
			$childCats = unserialize( CHILD_CATS );
			$grandchildCats = unserialize( GRANDCHILD_CATS );

			$cntr = 0;

			// grab collection of performers from performers table
			global $wpdb;
			$query = "SELECT * FROM " . $wpdb->prefix . "performers";
			$perfArray = $wpdb->get_results( $query, ARRAY_A );

			//print_r( $perfArray );
			// we've got an array of unique Shows, cycle through and commence import
			foreach( $perfArray as $performer ) {

				$id = $performer['id'];
				$name = $performer['name'];

				$exists = Show::exists( $id );

				/*if ( $exists ) {
					echo "show $name exists, skipping this one";
					continue;
				} else {
					echo "show $name does not exist.  continuing with import.";
				}*/

				//echo "<br />Commencing import<br />";
					

				$show = new Show( (object) [
						'id' => $id,
						'name' => $name
						] );

				$venueIDs = array(); // Array to hold IDs for any Venues that appear while importing these events, for later processing
				$cities = array(); // Array to hold the cities associated with Venues, in case we need it to add them to Shows
				$cats = array(); // Array to hold categories to be added to show

				// Build a list of new events by comparing API and Show events
				$APIEvents = $conn->GetEventsByPerformer( $show->performerID );
				
				// cycle through the new Events
				foreach( $APIEvents as $obj ) {

					$event = new Event( $obj );
					$event->setPerformerID( $show->performerID );

					if( in_array( $event->venueID, $venueIDs ) === false )
						$venueIDs[] = $event->venueID;

					// NOTE: add in a check to confirm whether the fetched term exists in constants array...if not, fetch from API

					// grab categories from event, push into array for adding to show after loop
					$pCat = $parentCats[ $obj->ParentCategoryID ];
					$cCat = $childCats[ $obj->ChildCategoryID ];
					$gCat = $grandchildCats[ $obj->GrandchildCategoryID ];

					// check that various categories haven't been added to the cats array
					if ( in_array( $pCat, $cats ) === false )
						$cats[] = $pCat;
					if ( in_array( $cCat, $cats ) === false )
						$cats[] = $cCat;
					if ( in_array( $gCat, $cats ) === false && $gCat != "Empty Grandchild" )
						$cats[] = $gCat;
				}

				// cycle through array of venue IDs, either grabbing existing or creating/saving new ones, then register relationship between show and venue
				foreach( $venueIDs as $vID ) {
					// fetch venue from API, use to instantiate a new Venue object
					$vAPI = $conn->getVenue( $vID );
					$venue = new Venue( $vAPI );

					$city = new City( $vAPI->City, $vAPI->StateProvince );
					$city->addShow( $show->showID );
					$show->addCity( $vAPI->City  );

					/*if ( in_array( $vAPI->City, $cities ) === false  ) {
						
						$cities[] = $cityInfo;
					}*/

					$show->addVenue( $venue->wpVenueID );

					$venue->addShow( $show->showID );
				}

				// cycle through array of cities, either grabbing existing or creating/saving new ones, then add city to show (venue already has it)
				/*foreach( $cities as $place ) {
					$city = new City( $place );
					$show->addCity( $place );
				}*/

				// lastly, add categories to the show
				$show->addCats( $cats );

				/*echo "<pre>";
				echo $show->performerID . "<br />";
				print_r( $cats );
				//print_r( $APIEvents );
				echo "</pre>";*/

				$result = $wpdb->delete( $wpdb->prefix . 'performers', array( "id" => $id ) );

				if ( $result === false ) {
					//echo "deletion of " . $name . " failed.<br />";
				} else {
					//echo "<br />$result rows deleted (presumably with name $name)";
				}

				$cntr++;

				/*if ( $cntr > 5 ) {
					break;
				}*/
			}

			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
//get_footer();
