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
								// Before heading into the section (which displays events), let's grab a list of tickets for the current week's worth of events (up to Saturday)

								// grab this Show's performer ID
								$perfID = get_post_meta( $post->ID, "performerID", true );
								global $wpdb;
								$query = "SELECT * FROM " . $wpdb->prefix . "events WHERE performer = " . $perfID;

								// get current date
								$curDate = date( "Y-m-d H:i:s" );
								echo "Current date is $curDate <br />";

								// get date a week from now
								$oneWeek = date("Y-m-d H:i:s", mktime( 0, 0, 0, date('m'), date('d')+7, date('Y') ) );
								echo "One week from now is $oneWeek <br />";

								//$query .= " AND ( $curDate <= DATE(time) AND $oneWeek >= DATE(time) )";
								$query .= " AND ( time <= '$oneWeek' AND time >= '$curDate' )";
								echo $query;
								$events = $wpdb->get_results( $query );

								echo "<pre>";
								print_r($events);
								echo "</pre>";

								/*foreach ( $events as $event ) {
									echo "Event's city is $event->city <br />";
								}*/
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
