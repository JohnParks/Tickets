<?php
/*
Author: Eddie Machado
URL: http://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, etc.
*/

// LOAD BONES CORE (if you remove this, the theme will break)
require_once( 'library/bones.php' );

// Load in API related libraries
include_once( 'library/api-lib/api-includes.php' );

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
// require_once( 'library/admin.php' );

/*********************
LAUNCH BONES
Let's get everything up and running.
*********************/

function bones_ahoy() {

  //Allow editor style.
  add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

  // let's get language support going, if you need it
  load_theme_textdomain( 'bonestheme', get_template_directory() . '/library/translation' );

  // USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
  require_once( 'library/custom-post-type.php' );

  // launching operation cleanup
  add_action( 'init', 'bones_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'bones_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'bones_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );

  // enqueue Tickets Broadway specific scripts
  add_action( 'wp_enqueue_scripts', 'tickets_enqueue_scripts' );

  // ie conditional wrapper

  // launching this stuff after theme setup
  bones_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'bones_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'bones_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'bones_excerpt_more' );

  // run function for building out tickets table
  build_event_tbl();

} /* end bones ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'bones_ahoy' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'bones-thumb-600' => __('600px by 150px'),
        'bones-thumb-300' => __('300px by 100px'),
    ) );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* THEME CUSTOMIZE *********************/

/* 
  A good tutorial for creating your own Sections, Controls and Settings:
  http://code.tutsplus.com/series/a-guide-to-the-wordpress-theme-customizer--wp-33722
  
  Good articles on modifying the default options:
  http://natko.com/changing-default-wordpress-theme-customization-api-sections/
  http://code.tutsplus.com/tutorials/digging-into-the-theme-customizer-components--wp-27162
  
  To do:
  - Create a js for the postmessage transport method
  - Create some sanitize functions to sanitize inputs
  - Create some boilerplate Sections, Controls and Settings
*/

function bones_theme_customizer($wp_customize) {
  // $wp_customize calls go here.
  //
  // Uncomment the below lines to remove the default customize sections 

  // $wp_customize->remove_section('title_tagline');
  // $wp_customize->remove_section('colors');
  // $wp_customize->remove_section('background_image');
  // $wp_customize->remove_section('static_front_page');
  // $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');
  
  // Uncomment the following to change the default section titles
  // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
}

add_action( 'customize_register', 'bones_theme_customizer' );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'bonestheme' ),
		'description' => __( 'The first (primary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Sidebar 2', 'bonestheme' ),
		'description' => __( 'The second (secondary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'bonestheme' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'bonestheme' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'bonestheme' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


/*
This is a modification of a function found in the
twentythirteen theme where we can declare some
external fonts. If you're using Google Fonts, you
can replace these fonts, change it in your scss files
and be up and running in seconds.
*/
function bones_fonts() {
  wp_enqueue_style('googleFonts', '//fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
}

add_action('wp_enqueue_scripts', 'bones_fonts');

/*

END OF BONES THEME FUNCTIONS

Begin Tickets Broadway specific code

*/

// hook into init, register our various custom post types!
add_action( 'init', 'create_tb_post_types' );
function create_tb_post_types() {
  //register Show post type
  $showArgs = array(
    'labels' => array(
      'name'          => __( 'Shows' ),
      'singular_name'     => __( 'Show' ),
      'add_new_item'      => __( 'Add New Show' ),
      'edit_item'       => __( 'Edit Show' ),
      'new_item'        => __( 'New Show' ),
      'view_item'       => __( 'View Show' ),
      'search_items'      => __( 'Search Shows' ),
      'not_found'       => __( 'No shows found' ),
      'not_found_in_trash'  => __( 'No shows found in Trash' ),
      'all_items'       => __( 'All Shows' )
    ),
    'public'      =>  true,
    'has_archive' =>  true,
    'description'   =>  'Shows Tickets Broadway has inventory for.',
    'menu_position'   =>  5,
    'capability_type' =>  'post',
    'supports'      =>  array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
    'rewrite' => array(
      'with_front'  => false,
      'slug'      => 'show'
    ),
    'query_var'     => true,
    'publicly_queryable' => true,
    'delete_with_user'  => false,
    'show_in_rest'    => true
  );

  $result = register_post_type( 'show', $showArgs );
  if( is_wp_error(  $result ) ) {
    $error_string = $result->get_error_message();
    echo "<div class='error'>We've got a register_post_type (Show) issue here: " . $error_string . "<br />";
  }

  //register Venue post type
  $venueArgs =  array(
    'labels' => array(
      'name'          => __( 'Venues' ),
      'singular_name'     => __( 'Venue' ),
      'add_new_item'      => __( 'Add New Venue' ),
      'edit_item'       => __( 'Edit Venue' ),
      'new_item'        => __( 'New venue' ),
      'view_item'       => __( 'View venue' ),
      'search_items'      => __( 'Search Venues' ),
      'not_found'       => __( 'No venues found' ),
      'not_found_in_trash'  => __( 'No venues found in Trash' ),
      'all_items'       => __( 'All Venues' )
    ),
    'public'      =>  true,
    'description'   =>  'Venues where shows are potentially ocurring.',
    'menu_position'   =>  5,
    'capability_type' =>  'post',
    'supports'      =>  array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
    'rewrite' => array(
      'with_front'  => false,
      'slug'      => 'venue'
    ),
    'query_var'     => true,
    'publicly_queryable' => true,
    'delete_with_user'  => false,
    'show_in_rest'    => true
  );

  $result = register_post_type( 'venue', $venueArgs );
  if( is_wp_error(  $result ) ) {
    $error_string = $result->get_error_message();
    echo "<div class='error'>We've got a register_post_type (Venue) issue here: " . $error_string . "<br />";
  }


  //register City post type
  $cityArgs = array(
    'labels' => array(
      'name'          => __( 'Cities' ),
      'singular_name'     => __( 'City' ),
      'add_new_item'      => __( 'Add New City' ),
      'edit_item'       => __( 'Edit City' ),
      'new_item'        => __( 'New city' ),
      'view_item'       => __( 'View city' ),
      'search_items'      => __( 'Search Cities' ),
      'not_found'       => __( 'No cities found' ),
      'not_found_in_trash'  => __( 'No cities found in Trash' ),
      'all_items'       => __( 'All Cities' )
    ),
    'public'      =>  true,
    'description'   =>  'Cities holding Venues where shows are potentially ocurring.',
    'menu_position'   =>  5,
    'capability_type' =>  'post',
    'supports'      =>  array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
    'rewrite' => array(
      'with_front'  => false,
      'slug'      => 'city'
    ),
    'query_var'     => true,
    'publicly_queryable' => true,
    'delete_with_user'  => false,
    'show_in_rest'    => true
  );

  $result = register_post_type( 'city', $cityArgs );
  if( is_wp_error(  $result ) ) {
    $error_string = $result->get_error_message();
    echo "<div class='error'>We've got a register_post_type (City) issue here: " . $error_string . "<br />";
  }


  //register Cast post type
  $castArgs = array(
    'labels' => array(
      'name'          => __( 'Cast & Crew' ),
      'singular_name'     => __( 'Cast' ),
      'add_new_item'      => __( 'Add New Cast' ),
      'edit_item'       => __( 'Edit Cast' ),
      'new_item'        => __( 'New Cast' ),
      'view_item'       => __( 'View Cast' ),
      'search_items'      => __( 'Search Cast & Crew' ),
      'not_found'       => __( 'No Cast found' ),
      'not_found_in_trash'  => __( 'No Cast found in Trash' ),
      'all_items'       => __( 'All Cast & Crew' )
    ),
    'public'      =>  true,
    'description'   =>  'Cast & Crew participating in Ticket Broadway shows.',
    'menu_position'   =>  5,
    'capability_type' =>  'post',
    'supports'      =>  array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
    'rewrite' => array(
      'with_front'  => false,
      'slug'      => 'cast'
    ),
    'query_var'     => true,
    'publicly_queryable' => true,
    'delete_with_user'  => false,
    'show_in_rest'    => true
  );

  $result = register_post_type( 'cast', $castArgs );
  if( is_wp_error(  $result ) ) {
    $error_string = $result->get_error_message();
    echo "<div class='error'>We've got a register_post_type (Cast) issue here: " . $error_string . "<br />";
  }

  //register Cast post type
  $reviewArgs = array(
    'labels' => array(
      'name'          => __( 'Reviews' ),
      'singular_name'     => __( 'Review' ),
      'add_new_item'      => __( 'Add New Review' ),
      'edit_item'       => __( 'Edit Review' ),
      'new_item'        => __( 'New Review' ),
      'view_item'       => __( 'View Review' ),
      'search_items'      => __( 'Search Reviews' ),
      'not_found'       => __( 'No Review found' ),
      'not_found_in_trash'  => __( 'No Reviews found in Trash' ),
      'all_items'       => __( 'All Reviews' )
    ),
    'public'      =>  true,
    'description'   =>  'Show reviews.',
    'menu_position'   =>  5,
    'capability_type' =>  'post',
    'supports'      =>  array('title', 'editor', 'author', 'custom-fields', 'revisions'),
    'rewrite' => array(
      'with_front'  => false,
      'slug'      => 'review'
    ),
    'query_var'     => true,
    'publicly_queryable' => true,
    'delete_with_user'  => false,
    'show_in_rest'    => true
  );

  $result = register_post_type( 'review', $reviewArgs );
  if( is_wp_error(  $result ) ) {
    $error_string = $result->get_error_message();
    echo "<div class='error'>We've got a register_post_type (Review) issue here: " . $error_string . "<br />";
  }
}

// hook (back) into init to register taxonomies!
add_action( 'init', 'create_ticket_tax' );
function create_ticket_tax() {

  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name'              => _x( 'Genres', 'taxonomy general name', 'textdomain' ),
    'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'textdomain' ),
    'search_items'      => __( 'Search Genres', 'textdomain' ),
    'all_items'         => __( 'All Genres', 'textdomain' ),
    'parent_item'       => __( 'Parent Genre', 'textdomain' ),
    'parent_item_colon' => __( 'Parent Genre:', 'textdomain' ),
    'edit_item'         => __( 'Edit Genre', 'textdomain' ),
    'update_item'       => __( 'Update Genre', 'textdomain' ),
    'add_new_item'      => __( 'Add New Genre', 'textdomain' ),
    'new_item_name'     => __( 'New Genre Name', 'textdomain' ),
    'menu_name'         => __( 'Genre', 'textdomain' ),
  );

  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'genre', 'with_front' => false ),
    'description'   => 'Genre for shows and cast.  Parent categories are Sports, Theater, Concerts or Other.',
  );

  //register_taxonomy( 'genre', array( 'show', 'cast' ), $args );
  register_taxonomy( 'genre', array( 'show' ), $args );

  register_taxonomy_for_object_type( 'genre', 'show' );
  //register_taxonomy_for_object_type( 'genre', 'cast' );

}

// create the table to hold events on theme activation (called in the general theme set up function above)
function build_event_tbl() {
  global $wpdb;

  $charset_collate = $wpdb->get_charset_collate();

  $table_name = $wpdb->prefix . "events";

  $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL,
    time datetime DEFAULT '0000-00-00 00:00:00',
    venue mediumint(9),
    performer mediumint(9),
    city text,
    PRIMARY KEY  (id)
  ) $charset_collate;";
  
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );
}

// Let's create our Tickets Broadway specific theme options
// First, register a submenu under "Appearance" menu
add_action( "admin_menu", "add_theme_options_menu" );

function add_theme_options_menu() {
  add_theme_page( "Theme Options", "Theme Options", "manage_options", "tb_theme_options", "build_tb_theme_options" );
  //add_submenu_page( "themes.php", "Theme Options", "Theme Options", "manage_options", "tb_theme_options", "build_tb_theme_options" );
}

// Do all the work to initialize the theme options and make them available
add_action( "admin_init", "tb_settings_init" );
function tb_settings_init() {

  //push default options if they're not created yet
  if ( get_option( "tb_theme_options" ) == false ) {
    add_option( "tb_theme_options", apply_filters( "tb_default_options", tb_default_options() ) );
  }

  // Add a section to our submenu
  add_settings_section (
    "tb_settings_section",
    "Tickets Broadway Theme Options",
    "tb_section_callback",
    "tb_theme_options"
  );

  // Add option for banner image on homepage
  add_settings_field (
    "Banner Image",
    "Homepage Banner Image",
    "banner_callback",
    "tb_theme_options",
    "tb_settings_section"
  );

  // Add option for selecting a city (for spinning out microsites)
  add_settings_field (
    "City Selection",
    "Microsite City",
    "microsite_city_callback",
    "tb_theme_options",
    "tb_settings_section"
  );

  // Lastly, register them settings
  register_setting (
    "tb_theme_options",
    "tb_theme_options"
  );
}

// Set default theme options
function tb_default_options() {
  $defaults = array (
    'banner_id'   => '',
    'city'        => ''
  );
  return apply_filters( "tb_default_options", $defaults );
}

/* Commence the various setting callbacks
---------------------------------------------------*/

// Section callback function
function tb_section_callback() {
  echo "<p>Theme settings for Tickets Broadway</p>";
}

// Build out homepage banner image option section
function banner_callback() {
  $options = get_option( "tb_theme_options" );
?>

  <label id="banner-image-label" style="cursor:pointer;">Select a banner image to display on the homepage: </label>
  <input id="banner_id" name="tb_theme_options[banner_id]" value ="<?php echo $options['banner_id']; ?>" type="text" style="display:none;" />
  <input type="button" class="upload_image_button" value="Select Image" name="banner_url" /><img src="<?php echo wp_get_attachment_url( $options['banner_id'] ); ?>" id="img_preview" style="max-width:1150px; margin-top:10px" />

<?php
}

// build out radio button list to select a city
function microsite_city_callback() {
  $options = get_option( "tb_theme_options" );

  // Get a list of cities currently in DB
  global $wpdb;

  $results = $wpdb->get_results( "select post_title from $wpdb->posts where post_type = 'city'", ARRAY_A );

  $html = '<select id="city_select" name="tb_theme_options[city]">';
  $html .= '<option value="none" ' . selected( $options['city'], '', false ) . '>No City</option>';
  foreach( $results as $result ) {
    $html .= '<option value="' . $result["post_title"] . '" ' . selected( $options['city'], $result["post_title"], false ) . '>' . $result["post_title"] . '</option>';
  }
  $html .= '</select>';

  echo $html;

}


// Function to build out the theme options page
function build_tb_theme_options() {

  settings_errors();

  ?>
  <form method="post" action="options.php" />

  <?php
    settings_fields( "tb_theme_options" );
    do_settings_sections( "tb_theme_options" );
    submit_button();
  ?>
  </form>
<?php
}

function tb_options_enqueue_scripts() {
  // Enqueue script that handles WP media uploader
  $script_url = get_template_directory_uri() . "/library/js/upload-media.js";
  wp_enqueue_script( "tb-upload-media", $script_url, array( "jquery" ) );

  wp_enqueue_media();
}
add_action( "admin_enqueue_scripts", "tb_options_enqueue_scripts" );



/* Commence Template related functions!
------------------------------------------*/
// function to build out a list of of shows using the standard "show preview" format
function display_shows ( $postID, $numPosts = 4, $topSeller = false ) {
  // what parameters might we need?

  // grab array of show IDs
  $showIDs = get_post_meta( $postID, 'shows', true );

  // grab Show objects
  $args = array (
    "include" => $showIDs,
    "post_type" => "show",
    "posts_per_page" => $numPosts
  );
  if ( $topSeller ) {
    $args[ 'meta_key' ] = 'top_seller';
    $args[ 'meta_value' ] = 1;
  }
  $shows = get_posts( $args );

  /*echo "<pre>";
  print_r( $shows );
  echo "</pre>";*/

  // spit 'em out!
  $cntr = 1;
  echo "<div class='show-list'>";
  foreach ( $shows as $show ) {
    if ( $cntr > $numPosts )
      break;
    echo "<div class='show-list-item'>";
    echo "<a href='" . get_permalink( $show ) . "'>";
    echo "<div class='show-poster'>";
    if ( has_post_thumbnail( $show ) ) {
      echo get_the_post_thumbnail( $show, 'thumbnail' );
    } else {
      echo "<img src='" . get_template_directory_uri() . "/library/assets/placeholder.jpg' class='placeholder' />";
    }
    echo "</div></a>";
    echo "<div class='show-title'><a href='" . get_permalink( $show ) . "'>" . $show->post_title . "</a></div>";
    echo "<a href='" . get_permalink( $show ) . "' ><div class='buy-tickets'>Buy Tickets</div></a>";
    echo "</div>";
    $cntr++;
  }
  echo "</div>";

}

// function to spit out the start and end date of a given week (used for building out the event calendars)
function getStartEndDate( $week, $year ) {
  $dto = new DateTime();
  $dto->setISODate( $year, $week, 0 );
  $dates['start'] = $dto->format( "Y-m-d" );
  $dto->modify( "+6 days" ); // move forward one week
  $dates['end'] = $dto->format( "Y-m-d" );
  return $dates;
}

// function to fetch the "sunday date" of the calendar as it stands now
function getDates( $week = '', $month = '' ) {

  $year;
  $dates;
  // First up, let's check whether the above variables are set (which would indicate the user has interacted with the calendar)
  if ( $month == '' ) {
    if ( $week == '' ) {
      // ok!  Neither are set, so we're displaying the current week
      $date = new DateTime();
      $week = $date->format('W');
      $year = $date->format('Y');
      //$dateArr = getStartEndDate( $date->format('W'), $date->format('Y') );
    } else {
      // we're on a particular week!
      // note: add logic to handle swinging around to a new year
      $year = date('Y');
      //$dateArr = getStartEndDate( $week, date('Y') );
    }
  } else {
    // user has selected a month!
    $month += 1;
    $monthDateString = date('Y') . "-";
    $monthDateString .= $month . "-01";
    $date = new DateTime( $monthDateString ); // this should correspond to the first of the selected month
    $week = $date->format('W');
    $year = $date->format('Y');
    //$dateArr = getStartEndDate( $date->format("W"), $date->format("Y") );
  }
  $startDate = new DateTime();
  $startDate->setISODate( $year, $week, 0 );

  $endDate = new DateTime();
  $endDate->setISODate( $year, $week, 0 );
  $endDate->modify( "+6 days" );

  $dates["start"] = $startDate;
  $dates["end"] = $endDate;

  return $dates;
}

// function to grab events based on a date range, venue, and performer
// accepts a show WP ID, venue WP ID, week (optional) or month (optional)
// defaults to spitting out the current week's events
function getShowEvents( $showID, $venueWPID='', $start, $end ) {
  // grab this Show's performer ID
  $perfID = get_post_meta( $showID, "performerID", true );

  $venueID;

  global $wpdb;
  // Put together the piece of the query that filters over performer ID
  $query = "SELECT * FROM " . $wpdb->prefix . "events WHERE performer = " . $perfID;

  // confirm whether venueID is set, if so, grab its API ID and add an additional filter to the query
  if ( $venueWPID != '' ) {
    $venueID = get_post_meta( $venueWPID, "venueID", true );

    $query .= " AND venue = " . $venueID;
  }

  $query .= " AND ( time >= '" . $start . " 00:00:00' AND time <= '" . $end . " 23:59:59' )";
  
  //echo "<br />The query is " . $query;
  // $events contains all the event objects
  $events = $wpdb->get_results( $query );

  return $events;
}

// function to handle events calendar ajax call
function handleCalendar( $showID, $dates=null, $venueWPID="" ) {
  
  /*if ( $dates == null ) {
    echo $_POST;
    $dates = getDates();
  }*/

  /*echo "<pre>";
  print_r($dates);
  echo "</pre>";*/

  $events = getShowEvents( $showID, $venueWPID, $dates['start']->format( "Y-m-d" ), $dates['end']->format( "Y-m-d" ) );

  // set previous and next week variables
  $prevWeek = new DateTime( $dates["start"]->format("Y-m-d") );
  $prevWeek->modify( "-1 week" );
  $nextWeek = new DateTime( $dates["start"]->format("Y-m-d") );
  $nextWeek->modify( "+1 week" );
  
  //start building out the HTML that will display the calendar
  $html = "<span><input type='hidden' id='prev-week' value='" . $prevWeek->format('W') . "' />Previous Week</span>";
  $html .= "<span id='date-range'>" . $dates['start']->format('M j') . " - " . $dates['end']->format( 'M j' ) . "</span>";
  $html .= "<span><input type='hidden' id='next-week' value='" . $nextWeek->format('W') . "' />Next Week</span>";
  $html .= "<table id='events-calendar'><tr>";
  $html .= "</table>";

  echo $html;

  if( $_POST )
    wp_die();
}
add_action( "wp_ajax_add_calendar", "handleCalendar" );

function tickets_enqueue_scripts(){
  wp_enqueue_script( 'ticket-script', get_template_directory_uri() . '/library/js/tb-scripts.js' );
  wp_localize_script( 'ticket-script', 'ticket_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}



// function to register some new URL parameters as query vars
function tb_register_query_vars( $vars ) {
  $vars[] = "tosearch";
  $vars[] = "tab";

  return $vars;
}
add_filter( "query_vars", "tb_register_query_vars" );

/* DON'T DELETE THIS CLOSING TAG */ ?>