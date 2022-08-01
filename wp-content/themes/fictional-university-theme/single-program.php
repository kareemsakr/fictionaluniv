<?php get_header();
pageBanner();
while (have_posts()) {
    the_post(); ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> All programs</a>
                <span class="metabox__main">
                    <?php the_title() ?>
                </span>
            </p>
        </div>

        <div class="generic-content">
            <?php the_content() ?>
        </div>

        <?php
        $today = date('Ymd');
        $latest2Events = new WP_Query(array(
            "posts_per_page" => 2,
            'post_type' => 'event',
            'orderby' => 'meta_value_num',
            'meta_key' => 'event_date',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'event_date',
                    'compare' => '>=',
                    'type' => 'numeric',
                    'value' => $today
                ),
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"' // we need to wrap the id in quotes since wp serializes the data 
                ),
                // array() other conditions
            )
        ));
        if ($latest2Events->have_posts()) {

        ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Upcoming <?php the_title() ?> Events</h2>
        <?php


            while ($latest2Events->have_posts()) {
                $latest2Events->the_post();
                get_template_part('template_parts/content', get_post_type()); // will look for event-event.php

            }
            wp_reset_postdata(); // resets what global object we're pointing at
        }

        ?>


        <?php
        $today = date('Ymd');
        $relatedProfs = new WP_Query(array(
            "posts_per_page" => -1,
            'post_type' => 'professor',
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"' // we need to wrap the id in quotes since wp serializes the data 
                ),
            )
        ));
        if ($relatedProfs->have_posts()) {

        ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Featured <?php the_title() ?> Pofessors</h2>


            <ul class="professor-cards">
                <?php
                while ($relatedProfs->have_posts()) {
                    $relatedProfs->the_post();
                    $eventDate = new DateTime(get_field('event_date'));
                ?>

                    <li class="professor-card__list-item">
                        <a class="professor-card" href="<?php the_permalink() ?>">
                            <img class="professor-card__image" src="<?php the_post_thumbnail_url('progessorLandscape') ?>">
                            <span class="professor-card__name"><?php the_title() ?></span>
                        </a>
                    </li>
                <?php
                } ?>
            </ul>
        <?php
        }
        wp_reset_postdata();
        $relatedCampuses = get_field('related_campus');
        if ($relatedCampuses) {
        ?>
            <hr class="section-break">
            <h2 class="headline headline--medium"><?php the_title() ?> is available at these campuses</h2>

            <ul class="min-list link-list">

                <?php
                foreach ($relatedCampuses as $campus) {
                ?>
                    <li><a href="<?= get_the_permalink($campus) ?>"><?= get_the_title($campus) ?></a></li>
            <?php
                }
            }

            ?>
            </ul>


    </div>


<?php
}
get_footer();
?>