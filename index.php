<?php get_header(); ?>
			<div id="content">

				<div id="inner-content" class="wrap cf">

					<div class="d-2of7">
						<?php get_sidebar( "blog" ); ?>
					</div>

					<div id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

					<?php 
					// the query
					if ( get_query_var( 'paged' ) ) { 
						$paged = get_query_var( 'paged' );
					} elseif ( get_query_var( 'page' ) ) {
						$paged = get_query_var( 'page' );
					} else { $paged = 1; }
					$the_query = new WP_Query( array( 'posts_per_page' => 4, 'paged' => $paged ) ); ?>

					<?php 

					if ( $the_query->have_posts() ) : 

						$cntr = 0; //keeps track of articles, adds horizontal rule after first two

						while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

							<?php $categories = get_the_category(); //array of categories attached to current post

							if ( $cntr > 1 || is_paged() ) {
								$xtra_class = 'half-width';
							} else if ( $cntr == 1 ) {
								$xtra_class = 'second-article';
							} else {
								$xtra_class = 'first-article';
							} ?>

							<?php
							if( is_paged() ) {
								$xtra_class = "half-width";
							} ?>

							<article id="<?php the_ID(); ?>" class="<?php echo $xtra_class; ?>" >
								<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
								<div class="article-header">
									<time datetime="<?php echo date(DATE_W3C); ?>" pubdate ><?php the_time('F jS, Y'); ?></time> | <span class="category"> <?php echo $categories[0]->name; ?></span>
									 | <span class="social-icons">
										<a href="https://twitter.com/intent/tweet?original_referer=<?php the_permalink(); ?>&url=<?php the_permalink(); ?>" target="_blank"><img src="https://ticketsbroadway.com/wp-content/themes/ticketbroadway/images/twitter_color_64.png" /></a>
										<a href="http://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank"><img src="https://ticketsbroadway.com/wp-content/themes/ticketbroadway/images/facebook_color_64.png" /></a>
									</span>
								</div>
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php echo get_the_post_thumbnail( get_the_ID(), 'full' ); ?></a>
								<div class="entry-content">
									<?php the_excerpt(); ?>
								</div>
								<div class="read-more">
									<a href="<?php the_permalink(); ?>">READ MORE</a>
								</div>
							</article>

							<?php 
							if ( $cntr <= 1 && !is_paged() ) { echo "<hr />"; }
							if ( ( $cntr % 2 ) == 1 && is_paged() ) { echo "<hr />"; }

							$cntr++;
							?>

							<?php endwhile; ?>

								<?php bones_page_navi(); ?>

								<?php wp_reset_postdata(); ?>

							<?php else : ?>

									<article id="post-not-found" class="hentry cf">
											<header class="article-header">
												<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
										</header>
											<section class="entry-content">
												<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
										</section>
										<footer class="article-footer">
												<p><?php _e( 'This is the error message in the index.php template.', 'bonestheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>


					</div>

				</div>
			</div>

	<?php get_footer(); ?>