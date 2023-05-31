<?php
get_header();
?>

<div class="page-banner">
    <div class="page-banner__bg-image"
        style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg') ?>)"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title">
            Past Events
        </h1>
        <div class="page-banner__intro">
            <p>A recap of our past events.</p>
        </div>
    </div>
</div>
<div class="container container--narrow page-section">
    <?php
    $today = date('Ymd');
    // make custo query to show past events
    // remember to pass in an array to this class
    $past_events = new WP_Query(
        array(
            // *** make sure toa dd paged parameter to keep track of what page youre on
            // get query var can be used to get info about current url
            'paged' => get_query_var('paged', 1),//add 1 as a fallback if paged cannot be found by wp
            'post_type' => 'event',
            'meta_key' => 'event_date', //
            'orderby' => 'meta_value_num', // how we tell wp we want to order by meta key, we add num to meta value since dates are nums
            'order' => 'DESC',
            'meta_query' => array( //meta querys give you more control when searching for particular values
                // takes multiple arrays, we only use one because we are checking for dates that are in the past(just one thing)
                array(
                    // these are our three parameters
                    'key' => 'event_date', // the type of cusomt field
                    'compare' => '<=',
                    //the comparison operator
                    'value' => $today,
                    //todays date
                    'type' => 'numeric' // compares dates as numeric value
                )
            ) // this filters out past events, we can give it an array because it takes multiple conditions
        )
    );

    while ($past_events->have_posts()) {
        $past_events->the_post(); ?>
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
    // paginate_links only wants to work with default queries
    // since we are using custom, we need to give paginate links more info
    echo paginate_links(array(
        'total' => $past_events->max_num_pages
    ));
        ?>

</div>

<?php
get_footer();
?>