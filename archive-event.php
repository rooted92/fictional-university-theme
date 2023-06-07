<?php
get_header();
pageBanner(array(
    'title' => 'All Events',
    'subtitle' => 'See what is going on in our world.'
));
?>
<div class="container container--narrow page-section">
    <?php

    // custom queries aren't always the answer!
    // if we can work with the default query, there is no need  for a custom query
    // we can just tweak the default query



    while (have_posts()) {
        the_post();
        get_template_part('template-parts/content-event');
    }
    echo paginate_links()
        ?>
    <hr class="section-break">
    <p>Looking for a recap of past events? <a href="<?php echo site_url('/past-events') ?>">Check out our past events archive.</a> </p>
</div>

<?php
get_footer();
?>