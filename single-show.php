<?php
/*
 * CITY TEMPLATE
*/
?>

<?php get_header(); ?>

			<div id="content">

				<?php
				// variable to hold current tab...pull from URL, or default to "show"
				$tab = get_query_var( "tab", "show" );

				?>

				<div id="inner-content" class="wrap cf">

						<!--<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">-->

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<?php
							// URL that will have appropriate tab parameter appended to it
							$daURL = get_permalink($post->ID) . "?tab=";
							?>

							<div class="sidebar d-2of7">
								<?php
								echo "<div class='sidebar-img'>";
								if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'medium' );
								} else {
									echo "<img src='" . get_template_directory_uri() . "/library/assets/placeholder.jpg' />";
								}
								echo "</div>";

								?>

								<div class="buy-tickets">
									<h3>Buy Tickets</h3>
								</div>
							</div>

							<div id="post-<?php the_ID(); ?>" class="body-content d-5of7 show-content" <?php post_class('cf'); ?> role="article">

								<h2><?php echo $post->post_title; ?></h2>

								<!-- section to the four tabs for this show -->
								<ul class="show-nav">
									<li><a href='<?php echo $daURL . "show"; ?>' <?php if( $tab == "show" ) echo "class='active'"; ?> >The Show</a></li>
									<li><a href='<?php echo $daURL . "cast"; ?>' <?php if( $tab == "cast" ) echo "class='active'"; ?> >Cast & Creative</a></li>
									<li><a href='<?php echo $daURL . "venues"; ?>' <?php if( $tab == "venues" ) echo "class='active'"; ?>>Venues</a></li>
									<li><a href='<?php echo $daURL . "reviews"; ?>' <?php if( $tab == "reviews" ) echo "class='active'"; ?>>Reviews</a></li>
								</ul>

								<section class="entry-content cf">
									<?php
										get_template_part( "show", $tab );
									?>
								</section> <!-- end article section -->


								<?php
								// Let's initialize the variables we'll need to make available to JS to get this rolling
								$week = ''; // the week of the year we're displaying, defaults to today's week
								$month = ''; // used for when a different month is changed
								$venueWPID = '';

								$events = getShowEvents( $post->ID, $venueWPID, $month, $week );

								/*echo "<pre>";
								print_r($events);
								echo "</pre>";*/

								?>

								<!-- Section to display listing of events -->
								<section class="event-list">
									<h3>Show Tickets</h3>
									<div class="date-range">
										<span class="previous-arrow"> <- </span>
										<span class="dates"></span>
										<span class="next-arrow"> -> </span>
									</div>
									<select class="month-selector" name="mStr">
										<option value="0">January</option>
										<option value="1">February</option>
										<option value="2">March</option>
										<option value="3">April</option>
										<option value="4">May</option>
										<option value="5">June</option>
										<option value="6">July</option>
										<option value="7">August</option>
										<option value="8">September</option>
										<option value="9">October</option>
										<option value="10">November</option>
										<option value="11">December</option>
									</select>
									<script type="text/javascript">
										$('.month-selector').val(new Date().getMonth());
									</script>
									<select class="venue-selector">
										<option value="">All Venues</option>
										<?php
										// grab the array of venues from the show object
										$venues = get_post_meta( $post->ID, "venues", true );

										// iterate over the resulting array, adding a select option for each venue
										foreach ( $venues as $daVenue ) {
											$venue = get_post( $daVenue );
											echo "<option value='" . $venue->ID . "'>$venue->post_title</option>";
										}
										?>
									</select>
									<table>
										<tr class="days-row">
											<td>Sun</td>
											<td>Mon</td>
											<td>Tue</td>
											<td>Wed</td>
											<td>Thu</td>
											<td>Fri</td>
											<td>Sat</td>
										</tr>
									</table>
								</section>

							</div>

							<?php endwhile; ?>

							<?php else : ?>

									<article id="post-not-found" class="hentry cf">
										<header class="article-header">
											<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
										</header>
										<section class="entry-content">
											<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
										</section>
										<footer class="article-footer">
											<p><?php _e( 'This is the error message in the single-custom_type.php template.', 'bonestheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						<!--</main>-->

				</div>

			</div>

<?php get_footer(); ?>
