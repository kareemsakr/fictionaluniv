<?php get_header();
pageBanner(array(
    'title' => 'Past events',
    'subTitle' => 'A recap of our past events'
));
?>

<div class="container container--narrow page-section">
    <?php
    $today = date('Ymd');
    $pastEvents = new WP_Query(array(
        'paged' => get_query_var('paged', 1),
        "posts_per_page" => 1,
        'post_type' => 'event',
        'orderby' => 'meta_value_num',
        'meta_key' => 'event_date',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => 'event_date',
                'compare' => '<',
                'type' => 'numeric',
                'value' => $today
            )
            // array() other conditions
        )
    ));
    while ($pastEvents->have_posts()) {
        $pastEvents->the_post();
        get_template_part('template_parts/content', get_post_type()); // will look for event-event.php

    }
    echo paginate_links(array(
        'total' => $pastEvents->max_num_pages
    ))
    ?>
</div>

<?php get_footer(); ?>