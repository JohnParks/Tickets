<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<?php // force Internet Explorer to use the latest rendering engine available ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title><?php wp_title(''); ?></title>

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-touch-icon.png">
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		<![endif]-->
		<?php // or, set /favicon.ico for IE10 win ?>
		<meta name="msapplication-TileColor" content="#f01d4f">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">
            <meta name="theme-color" content="#121212">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>

		<?php // drop Google Analytics Here ?>
		<?php // end analytics ?>

	</head>

	<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

		<div id="container">

			<header class="header" role="banner" itemscope itemtype="http://schema.org/WPHeader">

				<div id="inner-header" class="wrap cf">

					<div class="above-nav">

						<a href="<?php echo home_url(); ?>" rel="nofollow" id="logo"><img src="<?php echo get_template_directory_uri(); ?>/library/assets/tickets-broadway-logo.png" /></a>

						<?php get_search_form(); ?>

						<a href="" class="phone-btn"><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/phone-speech-bubble.png" /></a>

					</div>

					<?php
						// build arrays to hold shows for each genre
						$theaterGenre = array (
							'taxonomy'	=>	'genre',
							'field'		=>	'slug',
							'terms'		=>	'theater'
						);
						$theaterArgs =  array (
							'post_type'		=> 'show',
							'tax_query'		=> $theaterGenre,
							'posts_per_page'=> '8'
						);
						$theaterShows = get_posts( $theaterArgs );

						// build arrays to hold shows for each genre
						$playGenre = array (
							'taxonomy'	=>	'genre',
							'field'		=>	'slug',
							'terms'		=>	'musicals-and-plays'
						);
						$playArgs =  array (
							'post_type'		=> 'show',
							'tax_query'		=> $playGenre,
							'posts_per_page'=> '8'
						);
						$playShows = get_posts( $playArgs );
					?>
                    
					<nav role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement" style="position:relative">

						<?php wp_nav_menu(array(
    					         'container' => false,                           // remove nav container
    					         'container_class' => 'menu cf',                 // class of container (should you choose to use it)
    					         'menu' => __( 'The Main Menu', 'bonestheme' ),  // nav name
    					         'menu_class' => 'nav top-nav cf',               // adding custom nav class
    					         'theme_location' => 'main-nav',                 // where it's located in the theme
    					         'before' => '',                                 // before the menu
        			               'after' => '',                                  // after the menu
        			               'link_before' => '',                            // before each link
        			               'link_after' => '',                             // after each link
        			               'depth' => 0,                                   // limit the depth of the nav
    					         'fallback_cb' => ''                             // fallback function (if there is one)
						)); ?>
                        
                        <div class="drop-down-shows" id="drop-down-shows">
                            <div class="ul-genre-list">
                                <ul>
                                    <li class="genre" data-genre="10"><a href="">Genre 10</a></li>
                                    <li class="genre" data-genre="11"><a href="">Genre 11</a></li>
                                </ul>
                            </div>
                            <div class="genre-show-list" id="genre-show-list-10">
                            <?php 
                                $arrayOfShowsForGenre10 = array(1,2,3,4,5,6,7,8);
                                foreach($arrayOfShowsForGenre10 as $Show){ ?>
                                    <div class="single-show">
                                        <a href="#">
                                            <img src="<?php echo get_template_directory_uri(); ?>/library/assets/placeholder.jpg">
                                        </a>
                                    </div>
                               <?php }
                            ?>
                            </div>
                            <div class="genre-show-list" id="genre-show-list-11">
                            <?php 
                                $arrayOfShowsForGenre11 = array(1,2,3,4,5,6,7,8);
                                foreach($arrayOfShowsForGenre11 as $Show){ ?>
                                    <div class="single-show">
                                        <a href="#">
                                            <img src="<?php echo get_template_directory_uri(); ?>/library/assets/placeholder.jpg">
                                        </a>
                                    </div>
                                <?php }
                            ?>
                            </div>
                        </div>
                        
						<span class="top-social">
							<a href="http://www.facebook.com/ticketsbroadway/" class="facebook-icon" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/color/facebook.png" /></a>
							<a href="http://www.twitter.com/ticksbroadway/" class="twitter-icon" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/color/twitter.png" /></a>
							<a href="https://www.instagram.com/ticketsbroadway/" class="instagram-icon" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/color/instagram.png" /></a>
							<a href="https://www.pinterest.com/ticketsbroadway/" class="pinterest-icon" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/color/pinterest.png" /></a>
							<a href="https://www.youtube.com/channel/UCiEZEbUab60ETExrru-SKUA" class="youtube-icon" ><img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/social/color/youtube.png" /></a>
						</span>

					</nav>

				</div>

			</header>
