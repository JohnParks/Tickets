<?php

global $post; ?>

<div class="thumbnail dropshadow">

<?php if( has_post_thumbnail() ) {
		the_post_thumbnail( 'small' );
	} else {
		echo "<img src='" . get_template_directory_uri() . "/library/assets/placeholder.jpg' class='placeholder dropshadow'/>";
	} ?>

</div>

<div class="result-body">
	<header class="entry-header article-header">
		<h3 class="search-title entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>	
	</header>

	<div class="address-block">
		<h4>Venue Address:</h4>
		<?php
		$address1 = get_post_meta( get_the_ID(), 'Street 1', true );
		$address2 = get_post_meta( get_the_ID(), 'Street 2', true );
		$city = get_post_meta( get_the_ID(), 'city', true );

		echo "<div class='entry-meta'>";
		if( $address1 )
			echo "<p>" . $address1 . "</p>";
		if( $address2 )
			echo "<p>" . $address2 . "</p>";
		if( $city )
			echo "<p>" . $city . "</p>";
		echo "</div>";
		?>
	</div>
</div>

<?php
?>