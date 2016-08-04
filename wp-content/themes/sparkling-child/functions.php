<?php

/***mio peche */
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Content Top Widgets',
        'id'   => 'content-top-widgets',
        'description'   => 'These are widgets for the content top.',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => ''
    ));
}


if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Check plans and Pricing',
        'id'   => 'content-check-plans-and-pricing',
        'description'   => 'These are widgets for plans and Pricing.',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => ''
    ));
}

if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Content Shiping',
        'id'   => 'content-shiping',
        'description'   => 'These are widgets for Content Shiping.',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => ''
    ));
}


if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Content Our Services',
        'id'   => 'content-our-services',
        'description'   => 'These are widgets for Content Our Services.',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => ''
    ));
}

if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Content Our Clients',
        'id'   => 'content-our-clients-widgets',
        'description'   => 'These are widgets for the content top.',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => ''
    ));
}

if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Content Testimonials Video',
        'id'   => 'testimonials-video-widgets',
        'description'   => 'These are widgets for Content Testimonials Video.',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => ''
    ));
}

// Talking Guy
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Talking Guy',
        'id'   => 'talking-guy',
        'description'   => 'Talking Guy',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => ''
    ));
}

function is_child($pageID) {
    global $post;
    if( is_page() && ($post->post_parent==$pageID) ) {
        return true;
    } else {
        return false;
    }
}

// Get the id of a page by its name
function get_page_id($page_name){
    global $wpdb;
    $page_name = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$page_name."'");
    return $page_name;
}

//remove_action( 'the_content', "sidebar-1" );

