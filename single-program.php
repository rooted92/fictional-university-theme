<?php
// ca
the_ID();

get_header();
// using wp function have_posts() to get all posts
while (have_posts()) {
    // this func will keep track which post we are working with
    the_post(); ?>

    <div class="page-banner">
        <div class="page-banner__bg-image"
            style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg') ?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">
                <?php the_title(); ?>
            </h1>
            <div class="page-banner__intro">
                <p>DONT FORGET TO REPLACE ME LATER</p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p> <!-- we can use wp function to dynamically get href for events home link -->
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i
                        class="fa fa-home" aria-hidden="true"></i> All Programs
                </a> <span class="metabox__main">
                    <?php the_title(); ?>
                </span>
            </p>
        </div>
        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <?php
        $related_professors = new WP_Query(
            array(
                'posts_per_page' => -1,
                // when we add -1 we tell wp to return everything that meets the query
                'post_type' => 'professor',
                'orderby' => 'title',
                // how we tell wp we want to order by meta key, we add num to meta value since dates are nums
                'order' => 'ASC',
                'meta_query' => array(
                    //meta querys give you more control when searching for particular values
                    array(
                        'key' => 'related_programs',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"' // we need to wrap ID in quotes for serialization
                    )
                ) // this filters out past events, we can give it an array because it takes multiple conditions
            )
        );

        if ($related_professors->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors</h2>';

            // use have_posts method on homepageevents object to access array of events
            while ($related_professors->have_posts()) {
                $related_professors->the_post(); //call the post to get our data for displaying in browser withing the object NOT globally
                ?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); the_ID() ?></a></li>
            <?php }
        }

        // this function will: reset global post object and other functions to default url based query
        // when ever you run multiple custom queries be sure to use this wp reset function
        wp_reset_postdata();

        // this is a custom query, we can also sort items from here
        $today = date('Ymd');
        $homepage_events = new WP_Query(
            array(
                'posts_per_page' => 2,
                // when we add -1 we tell wp to return everything that meets the query
                'post_type' => 'event',
                //getting event type posts
                'meta_key' => 'event_date',
                //
                'orderby' => 'meta_value_num',
                // how we tell wp we want to order by meta key, we add num to meta value since dates are nums
                'order' => 'ASC',
                'meta_query' => array(
                    //meta querys give you more control when searching for particular values
                    // takes multiple arrays, we only use one because we are checking for dates that are in the past(just one thing)
                    array(
                        // these are our three parameters
                        'key' => 'event_date',
                        // the type of cusomt field
                        'compare' => '>=',
                        //the comparison operator
                        'value' => $today,
                        //todays date
                        'type' => 'numeric' // compares dates as numeric value
                    ),
                    array(
                        'key' => 'related_programs',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"' // we need to wrap ID in quotes for serialization
                    )
                ) // this filters out past events, we can give it an array because it takes multiple conditions
            )
        );

        if ($homepage_events->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';

            // use have_posts method on homepageevents object to access array of events
            while ($homepage_events->have_posts()) {
                $homepage_events->the_post(); //call the post to get our data for displaying in browser withing the object NOT globally
                ?>
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
                            <?php if (has_excerpt()) {
                                the_excerpt();
                            } else {
                                echo wp_trim_words(get_the_content(), 18);
                            } ?><a href="<?php echo the_permalink() ?>" class="nu gray">Learn more</a>
                        </p>
                    </div>
                </div>
            <?php }
        }


        ?>

    </div>


    <!-- By droping out of php the dropping back in we enter HTML mode! -->
    <!-- the title func give you title -->
    <!-- <h2>
        <?php the_title(); ?>
    </h2>
    <?php the_content(); ?> -->
<?php }

get_footer();
?>