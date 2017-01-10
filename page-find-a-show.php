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

				<div id="inner-content" class="wrap cf search-results find-a-show">

						<!--<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">-->

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<div class='sidebar d-2of7'>
								<form role="search" action="<?php echo site_url('/') . 'find-a-show'; ?>" method="post" id="searchform">
									<input type="hidden" name="search_post_type" value="shows" /> <!-- // hidden 'products' value -->
									<input type="hidden" name="search_tosearch" value="" />
									<div class="genre-filter">
										<input type="hidden" name="search_genre" value="" />
										<h4>Filter by Genre</h4>
										<?php
										// Alright, let's try fetching a list of genres that have "include_filter" set to true (or 1)
										$genreArgs = array(
											"taxonomy"		=>	"genre",
											"fields"		=>	"all",
											"meta_query"	=>	array(
												array(
													"key"		=> "include_filter",
													"value"		=>	true,
													"compare"	=>	"="
												)
											)
										);
										$terms = get_terms( $genreArgs );
										?>
										<select id="genre-filter">
											<option value="">All Genres</option>
											<?php foreach( $terms as $term ) { ?>
											<option value="<?php echo $term->slug; ?>" ><?php echo $term->name; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="city-filter">
										<input type="hidden" name="search_city" value="" />
										<h4>Filter by City</h4>
										<?php
										// let get a list of all cities in the DB, build a selector for each one
										$cities = get_posts( array( "post_type" => "city" ) );
										?>
										<select id="city-filter">
											<option value="">All Cities</option>
											<?php foreach( $cities as $city ) { ?>
											<option value="<?php echo $city->ID; ?>" ><?php echo $city->post_title; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="month-filter">
										<input type="hidden" name="search_month" value="" />
										<h4>Filter by Month<h4>
										<select class="month-filter" id="month-filter" name="mStr" multiple>
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
									</div>
								</form>
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
									// Call "getShowResults()", which is a function that takes all the various filters and search params, and returns a WP_Query object
									$shows_query = getShowResults();

									/*echo "The Result Query is:<br />";
									echo "<pre>";
									print_r( $shows_query ) ;
									echo "</pre>";*/

									echo "<div class='shows-results-list'>";

									// Commence the Loop!
									//if( $shows_query->have_posts() ) : while( $shows_query->have_posts() ) : $shows_query->the_post();
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
