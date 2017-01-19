<?php
/*
 Template Name: Custom Page Example
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
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<main id="main" class="m-all t-3of3 d-7of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<section class="entry-content cf" itemprop="articleBody" class="country-wrapper">
									<img class="country-map" src="<?php echo get_template_directory_uri(); ?>/library/assets/map.png" />
								</section>

								<section class="state-list">
									<?php
									// Get list of cities cleared to be displayed on this page
									$args = array (
										"post_type"		=> "city",
										"meta_key"		=> "display_city",
										"meta_value"	=> 1
									);
									$cities = get_posts( $args );

									// Iterate through cities, pulling out unique instances of states
									$states = array();
									foreach ( $cities as $city ) {
										$state = get_post_meta( $city->ID, 'state', true );
										if( in_array( $city, $states ) === false ) {
											$states[] = $state;
										}
									}

									foreach( $states as $state) {
										$camelState = camelCase( $state );
										echo "<div data-state='$camelState'>";
										echo "<h3 class='state-header'>$state</h3>";
										echo "<ul>";
										foreach( $cities as $city ) {
											$daState = get_post_meta( $city->ID, 'state', true );
											if ( $daState == $state ) {
												$daLink = get_permalink( $city );
												echo "<li><a href='$daLink'>$city->post_title</a></li>";
											}
										}
										echo "</ul>";
										echo "</div>";
									}
									?>
								</section>

							</article>

							<?php endwhile; else : ?>

									<article id="post-not-found" class="hentry cf">
											<header class="article-header">
												<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
										</header>
											<section class="entry-content">
												<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
										</section>
										<footer class="article-footer">
												<p><?php _e( 'This is the error message in the page-custom.php template.', 'bonestheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						</main>

				</div>

			</div>


<?php get_footer(); ?>
