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
						<!--<ul class="nav top-nav" id="shows-nav">
							<li class="menu-item menu-item-has-children">
								<a href="<?php echo home_url(); ?>/find-a-show">Shows</a>
								<ul class="sub-menu">
									<li class="menu-item menu-item-has-children">
										<a href="<?php echo home_url();?>/genre/theater">Theater</a>
								</ul>
							</li>
						</ul>-->
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
                        <style>
                            div.posters{
                                display: none;
                                background-color: black;
                                
                                position: relative;
                                z-index: 999;
                                left: 238px;
                                width: 500px;
                                height: 300px;
                                /*display: flex;*/
                                align-items: baseline;
                                justify-content: space-between;
                                flex-wrap: wrap;
                                padding: 10px;
                            }
                            
                             div#theater-posters a {
                                    width: 18%;
                                    height: auto;
                                 display:block
                                }
                            div#theater-posters a img{
                                width:100%;
                                height:auto;
                            }
                        
                        </style>
                        <div clas="show-dropdown" style="position:absolute;" >
                            <ul class="nav top-nav" id="shows-nav">
                                <li id="showslink" class="menu-item menu-item-has-children">
                                    <a href="<?php echo home_url(); ?>/find-a-show">Shows</a>
                                    <ul class="sub-menu plays" style="height:300px;border-right:none !important">
                                        <li class="menu-item menu-item-has-children" id="theater-link">
                                            <a href="<?php echo home_url();?>/genre/theater">Theater</a>
                                    </ul>
                                </li>
                            </ul>
                            <div id="theater-posters" class="posters" style="display:none">
                                <?php foreach($theaterShows as $show){?>
                                <a href="<?php echo get_the_permalink($show->ID); ?>"><?php echo get_the_post_thumbnail($show->ID, 'full'); ?></a>
                                    <?php }?>
                            </div>
                        </div>
                        <script>
                            $('#showslink').hover(function(){
                                $('.sub-menu.plays').css('display', 'block');
                            })
                            $('#theater-link').hover(function(){
                                console.log("hover");
                                $('#theater-posters').css('display', 'flex');
                                $('.sub-menu.plays').css('display', 'block');
                            }, function(){
                                $('.sub-menu.plays').css('visibility', 'visible');
                                //$('#theater-posters').css('display', 'none');
                                //$('.sub-menu.plays').css('display', 'none');
                            })
                            $('#theater-posters').hover(function(){
                                $('.sub-menu.plays').css('display', 'block');
                            },function(){
                                $('#theater-posters').css('display', 'none');
                            })
                        </script>
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
