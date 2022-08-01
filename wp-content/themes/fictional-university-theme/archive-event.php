<?php get_header();
pageBanner(array(
    'title' => 'All events',
    'subTitle' => 'See what\'s going on in our world'
    // 'photo' => 'https://images.pexels.com/photos/414102/pexels-photo-414102.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'
));
?>



<div class="container container--narrow page-section">
    <?php while (have_posts()) {
        the_post();
        get_template_part('template_parts/content', get_post_type()); // will look for event-event.php

    }
    echo paginate_links()
    ?>
    <hr class="section-break">
    <p>Looking for past events ? <a href="<?= site_url('/past-events') ?>">Click here.</a></p>
</div>

<?php get_footer(); ?>