<?php get_header();
while (have_posts()) {
    the_post();
    pageBanner();
?>



    <div class="container container--narrow page-section">

        <div class="generic-content">
            <div class="row group">
                <div class="one-third">
                    <?php the_post_thumbnail('progessorPortrait');
                    ?>
                </div>
                <div class="two-thirds">

                    <?php
                    $likeCount = new WP_Query(array(
                        'post_type' => 'like',
                        'meta_query' => array(
                            array(
                                'key' => 'liked_professor_id',
                                'compare' => '=',
                                'value' => get_the_ID()
                            )
                        )
                    ));

                    $exists = 'no';
                    if (is_user_logged_in()) {
                        $existQuery = new WP_Query(array(
                            'author' => get_current_user_id(),
                            'post_type' => 'like',
                            'meta_query' => array(
                                array(
                                    'key' => 'liked_professor_id',
                                    'compare' => '=',
                                    'value' => get_the_ID()
                                )
                            )
                        ));
                        $exists = $existQuery->found_posts ? 'yes' : 'no';
                    }


                    ?>

                    <span class="like-box" data-like="<?php echo $existQuery->posts[0]->ID; ?>" data-professor="<?php the_ID(); ?>" data-exists="<?= $exists ?>">
                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        <span class="like-count"><?php echo $likeCount->found_posts; ?></span>
                    </span>
                    <?php the_content() ?>
                </div>
            </div>
        </div>

        <?php
        $related_programs = get_field('related_programs'); // this is an array

        if ($related_programs) { ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Subjects taught</h2>
            <ul class="link-list min-list">
                <?php
                // print_r($related_programs); 
                foreach ($related_programs as $item) {
                ?>
                    <li><a href="<?php echo get_the_permalink($item) ?>"><?php echo get_the_title($item) ?></a></li>
                <?php
                }
                ?>
            </ul>
        <?php } ?>

    </div>


<?php
}
get_footer();
?>