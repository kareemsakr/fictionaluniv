<?php

require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/like-route.php');

function pageBanner($args = null)
{
    $title = $args['title'] ?: get_the_title();
    $subTitle = $args['subTitle'] ?: get_field('page_banner_subtitle');
    //AND !is_archive() AND !is_home() 
    // instructor said this will cause a future error since it might take the data from the first post and put it for the whole archive
    $bgImage = $args['photo'] ?: get_field('page_banner_bg_image')['sizes']['pageBanner'] ?: get_theme_file_uri("images/ocean.jpg");

?>
    <div class="page-banner">
        <!-- <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri("images/ocean.jpg") ?>)"></div> -->
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $bgImage ?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo  $title ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $subTitle ?></p>
            </div>
        </div>
    </div>
<?php }

function univ_files()
{
    // wp_enqueue_style('univ_main_styles', get_stylesheet_uri());
    wp_enqueue_script('google_map', '//maps.googleapis.com/maps/api/js?key=AIzaSyDbtC2fN7R8USGiLIkd5E7hgZrwCUv_sG8', NULL, '1.0', true); // dependencies, could be null
    wp_enqueue_script('main-university-js', get_theme_file_uri('build/index.js'), array('jquery'), '1.0', true); // dependencies, could be null
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('univ_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('univ_extra_styles', get_theme_file_uri('/build/index.css'));

    wp_localize_script('main-university-js', 'univData', array(
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')
    )); //will let us output js data into the html source of the webpage , I should be able to access root_url from my js
}

function univ_features()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails'); // wont enable feature images for custom post types
    add_image_size('progessorLandscape', 400, 260, true);
    add_image_size('progessorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
    register_nav_menu('header-menu-location', 'Header Menu Location');
    register_nav_menu('footer-learn-location', 'Footer Learn Location');
    register_nav_menu('footer-explore-location', 'Footer Explore Location');
}

function univ_adjust_queries($query)
{
    // $query->set('key', 'val');
    if (!is_admin() and is_post_type_archive('event') and $query->is_main_query()) { // if we dont put 'event' this query eill impact ALL archives
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set(
            'orderby',
            'meta_value_num',
        );
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'type' => 'numeric',
                'value' => $today
            )
        ));
    }

    if (!is_admin() and is_post_type_archive('program') and $query->is_main_query()) { // if we dont put 'event' this query eill impact ALL archives
        $query->set(
            'orderby',
            'title',
        );
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }


    if (!is_admin() and is_post_type_archive('campus') and $query->is_main_query()) { // if we dont put 'event' this query eill impact ALL archives
        $query->set('posts_per_page', -1);
    }
}
function univ_map_key($api)
{
    $api['key'] = 'AIzaSyDbtC2fN7R8USGiLIkd5E7hgZrwCUv_sG8';
    return $api;
}

function univ_custom_rest()
{
    // we are adding a custom prop to the posts response
    register_rest_field('post', 'authorName', array(
        'get_callback' => function () {
            return get_the_author();
        }
    ));
}

add_action('rest_api_init', 'univ_custom_rest');
add_action('wp_enqueue_scripts', 'univ_files');
add_action('after_setup_theme', 'univ_features');
add_action('pre_get_posts', 'univ_adjust_queries');
add_filter('acf/fields/google_map/api', 'univ_map_key');



// redirect subscriber accounts to homepafe and out of the admin panel
add_action('admin_init', 'redirectSubsToFrontend');
function redirectSubsToFrontend()
{
    $currentUser = wp_get_current_user();
    if (count($currentUser->roles) == 1 and $currentUser->roles[0] == "subscriber") {
        wp_redirect('/');
        exit;
    }
}

add_action('wp_loaded', 'noSubsAdminBar');
function noSubsAdminBar()
{
    $currentUser = wp_get_current_user();
    if (count($currentUser->roles) == 1 and $currentUser->roles[0] == "subscriber") {
        show_admin_bar(false);
    }
}


// customize login screen
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl()
{
    return esc_url(site_url('/'));
}


// adding out own css to the login page which apparently wp doesn't do by default
add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS()
{
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('univ_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('univ_extra_styles', get_theme_file_uri('/build/index.css'));
}


//chanfing the 'powered by wordpress to our site's name'
add_filter('login_headertitle', 'ourLoginTitle');
function ourLoginTitle()
{
    return get_bloginfo('name');
}




// Force note posts to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postArr)
{
    if ($data['post_type'] == 'note') {
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);

        if (!$postArr['ID'] and count_user_posts(get_current_user_id(), 'note') > 4) {
            die('You have reach your note limit');
        }
    }

    if ($data['post_type'] == 'note' and $data['post_status'] != 'trash') {
        $data['post_status'] = "private";
    }

    return $data;
}
