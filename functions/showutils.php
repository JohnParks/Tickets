<?php

function get_top_sellers($count = 12){
    $args = array (
        'post_type'		=> 'show',
        'meta_key' 		=> 'top_seller',
        'meta_value'	=> 1,
        'posts_per_page'=> $count,
        'no_found_rows'	=> true
    );

    $seller_query = new WP_Query( $args );
    return $seller_query;
}

function get_home_slider($count = 4, $offset = 0){
    $args = array (
        'post_type'		=> 'show',
        'meta_key' 		=> 'display_in_slider',
        'meta_value'	=> true,
        'posts_per_page'=> $count,
        'no_found_rows'	=> true,
        'offset'        => $offset
    );
    
    $query = new WP_Query( $args );
    return $query;
}
