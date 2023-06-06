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
        the_post(); ?>
        <div class="event-summary">
            <a class="event-summary__date t-center" href="<?php echo the_permalink(); ?>">
                <span class="event-summary__month">
                    <?php
                    $event_date = new DateTime(get_field('event_date'));
                    echo $event_date->format('M');
                    ?>
                </span>
                <span class="event-summary__day">
                    <?php echo $event_date->format('d'); ?>
                </span>
            </a>
            <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a href="<?php echo the_permalink(); ?>"><?php the_title(); ?></a></h5>
                <p>
                    <?php echo wp_trim_words(get_the_content(), 18) ?><a href="<?php echo the_permalink() ?>"
                        class="nu gray">Learn more</a>
                </p>
            </div>
        </div>
    <?php }
    echo paginate_links()
        ?>
    <hr class="section-break">
    <p>Looking for a recap of past events? <a href="<?php echo site_url('/past-events') ?>">Check out our past events archive.</a> </p>
</div>

<?php
get_footer();
?>