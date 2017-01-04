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

				<?php
					$toSearch = get_query_var( "tosearch", "none" );
					//echo $toSearch;

					// Create URL, to be loaded with additional parameters when clicking on sidebar filters
					$daURL = site_url('/') . 'find-a-show/?tosearch=' . $toSearch;

					echo $daURL;
				?>

				<div id="inner-content" class="wrap cf">

						<!--<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">-->

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<div class='sidebar d-2of7'>
							</div>

							<div id="post-<?php the_ID(); ?>" class="d-5of7" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<header class="article-header">

									<h1 class="page-title"><?php the_title(); ?></h1>

								</header>

								<section class="entry-content cf" itemprop="articleBody">
									<?php
										the_content();
									?>
								</section>

								<div>
									<h3>Search Shows</h3>
									<form role="search" action="<?php echo site_url('/') . 'find-a-show'; ?>" method="get" id="searchform">
									    <input type="text" name="tosearch" placeholder="Search Shows"/>
									    <input type="hidden" name="post_type" value="shows" /> <!-- // hidden 'products' value -->
									    <input type="submit" alt="Search" value="Search" />
									</form>
								</div>

								<div>
									<div>Search Results</div>
									<?php
									// Build out the query to grab first 12 top sellers (based on the meta field in show object)
									$args = array (
										'post_type'		=> 'show',
										's'				=> $toSearch,
										'posts_per_page'=> '12'
									);

									$shows_query = new WP_Query( $args );

									/*echo "<pre>";
									print_r( $seller_query );
									echo "</pre>";*/

									echo "<div class='shows-results-list'>";

									// Commence the Loop!
									if( $shows_query->have_posts() ) : while( $shows_query->have_posts() ) : $shows_query->the_post();

										echo "<span class='top-seller-poster'>";
										if ( has_post_thumbnail() ) {
											echo the_post_thumbnail( 'full' );
										} else {
											echo "<img src='" . get_template_directory_uri() . "/library/assets/placeholder.jpg' />";
										}
										echo "</span>";

									endwhile; endif;

									echo "</div>";
								?>

							</div>

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

						<!--</main>-->

				</div>

			</div>


<?php get_footer(); ?>
