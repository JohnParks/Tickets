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
					$theGenre = get_query_var( "genre", "" );
					//echo $toSearch;

					// Create URL, to be loaded with additional parameters when clicking on sidebar filters
					$daURL = site_url('/') . 'find-a-show/?tosearch=' . $toSearch;

					if( $theGenre != "" ) {
						//build out taxonomy array for addition to search query
						$genreArr = array (
							'taxonomy'	=>	'genre',
							'field'		=>	'slug',
							'terms'		=>	$theGenre
						);
					}

					//echo $daURL;
				?>

				<div id="inner-content" class="wrap cf search-results find-a-show">

						<!--<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">-->

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<div class='sidebar d-2of7'>
								<div class="genre-filter">
									<h4>Filter by Genre</h4>
									<ul>
										<li class="<?php if($theGenre=='musicals-and-plays') {echo 'active';}?>"><a href="<?php echo $daURL;?>&genre=musicals-and-plays">Musicals and Plays</a></li>
										<li class="<?php if($theGenre=='las-vegas') {echo 'active';}?>"><a href="<?php echo $daURL;?>&genre=las-vegas">Las Vegas</a></li>
										<li class="<?php if($theGenre=='broadway') {echo 'active';}?>"><a href="<?php echo $daURL;?>&genre=broadway">Broadway</a></li>
										<li class="<?php if($theGenre=='theater') {echo 'active';}?>"><a href="<?php echo $daURL;?>&genre=theater">Theater</a></li>
									</ul>
								</div>
							</div>

							<div id="post-<?php the_ID(); ?>" class="d-5of7" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<header class="article-header">

									<h1 class="page-title"><?php the_title(); ?></h1>

								</header>

								<div class="show-search-form">
									<form role="search" action="<?php echo site_url('/') . 'find-a-show'; ?>" method="get" id="searchform">
									    <input type="text" name="tosearch" placeholder="Search Shows"/>
									    <input type="hidden" name="post_type" value="shows" /> <!-- // hidden 'products' value -->
									    <button type="submit" value="Search" id="searchSubmit"><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/search.png" /></button>
									</form>
								</div>

								<div>
									<h2>Search Results</h2>
									<?php
									// Build out the query to grab first 12 top sellers (based on the meta field in show object)
									$args = array (
										'post_type'		=> 'show',
										's'				=> $toSearch,
										'posts_per_page'=> '12'
									);

									if ( isset( $genreArr ) ) {
										$args['tax_query'] = array( $genreArr );
									}

									$shows_query = new WP_Query( $args );

									/*echo "<pre>";
									print_r( $seller_query );
									echo "</pre>";*/

									echo "<div class='shows-results-list'>";

									// Commence the Loop!
									if( $shows_query->have_posts() ) : while( $shows_query->have_posts() ) : $shows_query->the_post();

									?>	<article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article">
									<?php

										get_template_part( "search", "show" );

									?> </article><?php

										/*echo "<span class='top-seller-poster'>";
										if ( has_post_thumbnail() ) {
											echo the_post_thumbnail( 'full' );
										} else {
											echo "<img src='" . get_template_directory_uri() . "/library/assets/placeholder.jpg' />";
										}
										echo "</span>";*/

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
