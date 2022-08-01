<?php get_header();
pageBanner();
while (have_posts()) {
    the_post(); ?>



    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> Event Home</a>
                <span class="metabox__main">
                    <?php the_title() ?>
                </span>
            </p>
        </div>

        <div class="generic-content">
            <?php the_content() ?>
        </div>

        <?php
        $related_programs = get_field('related_programs'); // this is an array

        if ($related_programs) { ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Related Programs</h2>
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