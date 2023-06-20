<?php
// ca
the_ID();

get_header();
// using wp function have_posts() to get all posts
while (have_posts()) {
    // this func will keep track which post we are working with
    the_post();
    pageBanner();
    ?>

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
            <?php the_field('main_body_content'); ?>
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

            echo '<ul class="professor-cards">';
            // use have_posts method on homepageevents object to access array of events
            while ($related_professors->have_posts()) {
                $related_professors->the_post(); //call the post to get our data for displaying in browser withing the object NOT globally
                ?>
                <li class="professor-card__list-item">
                    <a class="professor-card" href="<?php the_permalink(); ?>">
                        <img class="professor-card__image" src="<?php the_post_thumbnail_url('professor_landscape'); ?>" alt="">
                        <span class="professor-card__name">
                            <?php the_title(); ?>
                        </span>
                    </a>
                </li>
            <?php }
            echo '</ul>';
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
                get_template_part('template-parts/content-event');
            }
        }

        // remember to reset data for clean slate
        wp_reset_postdata();
        $related_campuses = get_field('related_campus');
        // only output headlilne if not empty
        if ($related_campuses) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">' . get_the_title() . ' is Available At These Campuses: </h2>';
            echo '<ul class="min-list link-list">';
            foreach($related_campuses as $campus){
                ?>
                <li><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title($campus); ?></a></li>
                <?php
            }
            echo '</ul>';
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