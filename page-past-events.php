<?php
get_header();
pageBanner(array(
    'title' => 'Past Events',
    'subtitle' => 'A recap of our past events.'
));
?>

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
        $past_events->the_post();
        get_template_part('template-parts/content-event');
    }
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