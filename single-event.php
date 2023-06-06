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
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>"><i
                        class="fa fa-home" aria-hidden="true"></i> Events Home
                </a> <span class="metabox__main">
                    <?php the_title(); ?>
                </span>
            </p>
        </div>
        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <?php
        $related_programs = get_field('related_programs');
        // print_r($related_programs);
    
        // check if related programs array is not empty, if not then show related programs
        if ($related_programs) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Related Program(s)</h2>';
            echo '<ul class="link-list min-list">';
            foreach ($related_programs as $program) { ?>
                <!-- need to pass in wp post object as argument -->
                <!-- echo get_the_title($program); -->
                <li><a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a></li>
            <?php }
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