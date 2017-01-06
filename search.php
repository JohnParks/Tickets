<?php
$post_type = array();
if ( isset($_GET['post_type']) ) {
	$post_type[0] = $_GET['post_type'];	
} else {
	$post_type[0] = "";
}

// check to see if there was a post type in the
// URL string and if a results template for that
// post type actually exists
if ( ( $post_type[0] != "" ) && locate_template( 'search-shows.php' ) ) {
  // if so, load that template
  get_template_part( 'search', 'shows' );
  
  // and then exit out
  exit;
}

?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

					<div class="sidebar d-2of7">
					</div>

					<div id="post-<?php the_ID(); ?>" class="body-content d-5of7" <?php post_class('cf'); ?> role="article">
						<h1 class="archive-title"><span><?php _e( 'Search Results for:', 'bonestheme' ); ?></span> <?php echo esc_attr(get_search_query()); ?></h1>

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

						<?php
						// get post-type to display here
						$theType = get_post_type();
						
						?>

							<article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article">

								<?php
									echo get_template_part( "search", $theType );
								?>

								<?php //the_excerpt( '<span class="read-more">' . __( 'Read more &raquo;', 'bonestheme' ) . '</span>' ); ?>

							</article>

						<?php endwhile; ?>

								<?php bones_page_navi(); ?>

							<?php else : ?>

									<article id="post-not-found" class="hentry cf">
										<header class="article-header">
											<h1><?php _e( 'Sorry, No Results.', 'bonestheme' ); ?></h1>
										</header>
										<section class="entry-content">
											<p><?php _e( 'Try your search again.', 'bonestheme' ); ?></p>
										</section>
										<footer class="article-footer">
												<p><?php _e( 'This is the error message in the search.php template.', 'bonestheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						</div>

					</div>

			</div>

<?php get_footer(); ?>
