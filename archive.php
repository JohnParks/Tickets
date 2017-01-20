<?php get_header(); ?>
			<div id="content">

				<div id="inner-content" class="wrap cf blog">

					<div class="t-1of3 d-2of7">
						<?php get_sidebar( "blog" ); ?>
					</div>

					<div id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

						<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
						?>
						
						<?php $cntr = 0; ?>

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

						<article id="<?php the_ID(); ?>" >
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark" class="thumbnail dropshadow">
								<?php if ( $cntr == 0 ) { 
									echo get_the_post_thumbnail( get_the_ID(), 'full' );
								} else {
									echo get_the_post_thumbnail( get_the_ID(), 'small' );
								} ?>
							</a>
							<div class="preview-content">
								<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
								<div class="article-header">
									<time datetime="<?php echo date(DATE_W3C); ?>" pubdate ><?php the_time('F jS, Y'); ?></time> | <span class="social-icons">
										<a href="https://twitter.com/intent/tweet?original_referer=<?php the_permalink(); ?>&url=<?php the_permalink(); ?>" target="_blank"><img src="https://ticketsbroadway.com/wp-content/themes/ticketbroadway/images/twitter_color_64.png" /></a>
										<a href="http://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank"><img src="https://ticketsbroadway.com/wp-content/themes/ticketbroadway/images/facebook_color_64.png" /></a>
									</span>
								</div>
								<div class="entry-content">
									<?php the_excerpt(); ?>
								</div>
								<div class="read-more">
									<a href="<?php the_permalink(); ?>">READ MORE</a>
								</div>
							</div>
						</article>

						<?php $cntr++; ?>

						<?php endwhile; ?>

								<?php bones_page_navi(); ?>

						<?php else : ?>

								<article id="post-not-found" class="hentry cf">
									<header class="article-header">
										<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
									</header>
									<section class="entry-content">
										<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
									</section>
									<footer class="article-footer">
											<p><?php _e( 'This is the error message in the archive.php template.', 'bonestheme' ); ?></p>
									</footer>
								</article>

						<?php endif; ?>

					</div>

				</div>

			</div>

<?php get_footer(); ?>
