<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<div id="home-page-slider">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							

							<?php endwhile; endif; ?>

						</div>

						<div id="top-sellers">

							<h1>Top Sellers</h1>
							<?php
								// Build out the query to grab first 12 top sellers (based on the meta field in show object)
								$args = array (
									'post_type'		=> 'show',
									'meta_key' 		=> 'top_seller',
									'meta_value'	=> 1,
									'posts_per_page'=> '12',
									'no_found_rows'	=> true
								);

								$seller_query = new WP_Query( $args );

								/*echo "<pre>";
								print_r( $seller_query );
								echo "</pre>";*/

								echo "<div class='top-sellers-list'>";

								// Commence the Loop!
								if( $seller_query->have_posts() ) : while( $seller_query->have_posts() ) : $seller_query->the_post();

									echo "<span class='top-seller-poster'>";
									echo "<a href='" . get_the_permalink( $post->ID ) . "'>";
									echo the_post_thumbnail( 'full' );
									echo "</a></span>";

								endwhile; endif;

								echo "</div>";
							?>

						</div>

						<div id="home-banner-image">
							<?php
								$theme_options = get_option( "tb_theme_options" );

								$banner_url = wp_get_attachment_url( $theme_options['banner_id'] );
							?>
							<img src="<?php echo $banner_url; ?>" style="width:100%;"/>
						</div>

						<div id="beyond-buzz">
							<h1>Broadway News and Entertainment</h1>

						</div>

						<div id="home-bottom-banner">
							<img src="<?php echo get_template_directory_uri(); ?>/library/assets/homepage-icons.png" style="width:100%;" />
						</div>

				</div>

			</div>

<?php get_footer(); ?>
