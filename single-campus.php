<?php
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
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i
                        class="fa fa-home" aria-hidden="true"></i> All Campuses
                </a> <span class="metabox__main">
                    <?php the_title(); ?>
                </span>
            </p>
        </div>
        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <?php $map_location = get_field('map_location'); ?>

        <div class="acf-map">
            <div class="marker" data-lat="<?php echo $map_location['lat']; ?>"
                data-lng="<?php echo $map_location['lng']; ?>">
                <h3>
                    <?php the_title(); ?>
                </h3>
                <?php echo $map_location['address']; ?>
            </div>
        </div>

        <?php
        $related_programs = new WP_Query(
            array(
                'posts_per_page' => -1,
                // when we add -1 we tell wp to return everything that meets the query
                'post_type' => 'program',
                'orderby' => 'title',
                // how we tell wp we want to order by meta key, we add num to meta value since dates are nums
                'order' => 'ASC',
                'meta_query' => array(
                    //meta querys give you more control when searching for particular values
                    array(
                        'key' => 'related_campus',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"' // we need to wrap ID in quotes for serialization
                    )
                ) // this filters out past events, we can give it an array because it takes multiple conditions
            )
        );

        if ($related_programs->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Programs available at this campus.</h2>';

            echo '<ul class="min-list link-list">';
            // use have_posts method on homepageevents object to access array of events
            while ($related_programs->have_posts()) {
                $related_programs->the_post(); //call the post to get our data for displaying in browser withing the object NOT globally
                ?>
                <li>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </li>
            <?php }
            echo '</ul>';
        }

        // this function will: reset global post object and other functions to default url based query
        // when ever you run multiple custom queries be sure to use this wp reset function
        wp_reset_postdata();

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