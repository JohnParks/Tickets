<?php

global $post; ?>

<div class="thumbnail">

<?php if( has_post_thumbnail() ) {
		the_post_thumbnail( 'small' );
	} else {
		echo "<img src='" . get_template_directory_uri() . "/library/assets/placeholder.jpg' class='placeholder'/>";
	} ?>

</div>

<div class="result-body">
	<header class="entry-header">
		<h3 class="search-title entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
		<a href="<?php the_permalink(); ?>" class="buy-tickets"><div>Buy Tickets</div></a>
	</header>

	<div class="show-info">
		<h4>This show playing at:</h4>
		<ul>
		<?php
		$venueIDs = get_post_meta( $post->ID, "venues", true );

		$args = array (
				"include"			=> $venueIDs,
				"post_type"			=> "venue"
			);

		$venues = get_posts( $args );

		foreach( $venues as $venue ) {
			?>
			<li><a href="<?php echo get_permalink( $venue->ID ); ?>"><?php echo $venue->post_title; ?></a></li>
			<?php
		}
		?>
		</ul>
	</div>
</div>