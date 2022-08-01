<?php get_header();
pageBanner(array(
    'title' => 'All programs',
    'subTitle' => 'There\'s something for Everyone, have a look around'
));
?>


<div class="container container--narrow page-section">
    <ul class="link-list min-list">
        <?php while (have_posts()) {
            the_post();
        ?>
            <li><a href="<?php echo the_permalink() ?>"><?php the_title() ?></a></li>
        <?php  }
        echo paginate_links()
        ?>
</div>

<?php get_footer(); ?>