<?php
function univ_post_types(): void
{

    register_post_type('like', array(
        // 'capability_type' => 'note',  we're handling permissions ourselves
        // 'map_meta_cap' => true, // enforce and require the permissions when they apply
        // 'show_in_rest' => true, // we dont want the wp version of rest, we'll build it ourselves
        'public' => false, //hide to users but also hides in admin dashboard
        'show_ui' => true, // shows in admin dashboard
        'supports' => array(
            'title',
        ),
        'menu_icon' => 'dashicons-heart',
        'labels' => array(
            'name' => 'Likes',
            'add_new_item' => 'Add new Like',
            'edit_item' => 'Edit Like',
            'all_items' => 'All Likes',
            'singular_name' => 'Like'
        )
    ));


    register_post_type('note', array(
        'capability_type' => 'note', // we're giving it brand new permissions  so it does'nt inherit from something else
        'map_meta_cap' => true, // enforce and require the permissions when they apply
        'show_in_rest' => true,
        'public' => false, //hide to users but also hides in admin dashboard
        'show_ui' => true, // shows in admin dashboard
        'supports' => array(
            'title',
            'editor',
        ),
        'menu_icon' => 'dashicons-welcome-write-blog',
        'labels' => array(
            'name' => 'Notes',
            'add_new_item' => 'Add new Note',
            'edit_item' => 'Edit Note',
            'all_items' => 'All Notes',
            'singular_name' => 'Note'
        )
    ));

    register_post_type('campus', array(
        'capability_type' => 'campus',
        'map_meta_cap' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt'
        ),
        'has_archive' => true,
        'rewrite' => array(
            'slug' => 'campuses'
        ),
        'public' => true,
        'menu_icon' => 'dashicons-location-alt',
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Campuses',
            'add_new_item' => 'Add new Campus',
            'edit_item' => 'Edit Campus',
            'all_items' => 'All Campuses',
            'singular_name' => 'Campus'
        )
    ));

    register_post_type('event', array(
        'capability_type' => 'event', //here we are telling wp to seperate the permissions for event from the post type
        'map_meta_cap' => true, // here we are asking wp to enforce the rules ?
        'supports' => array(
            'title',
            'editor',
            'excerpt'
        ),
        'has_archive' => true,
        'rewrite' => array(
            'slug' => 'events'
        ),
        'public' => true,
        'menu_icon' => 'dashicons-calendar',
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add new Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        )
    ));

    register_post_type('program', array(
        'supports' => array(
            'title',
            'editor',
        ),
        'has_archive' => true,
        'rewrite' => array(
            'slug' => 'programs' //changes the url for the archive
        ),
        'public' => true,
        'menu_icon' => 'dashicons-awards',
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Programs', // side meny link
            'add_new_item' => 'Add new Progam',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program' //add new program in the menu in admin
        )
    ));



    register_post_type('professor', array(
        'supports' => array(
            'title',
            'editor',
            'thumbnail' // needed to enable featured images for custom post types
        ),
        // 'has_archive' => true,
        // 'rewrite' => array(
        //     'slug' => 'professors' //changes the url for the archive
        // ),
        'public' => true,
        'menu_icon' => 'dashicons-welcome-learn-more',
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'professors', // side meny link
            'add_new_item' => 'Add new Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'professor' //add new professor in the menu in admin
        )
    ));
}
add_action('init', 'univ_post_types');
