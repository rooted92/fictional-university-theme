<?php
get_header();
// using wp function have_posts() to get all posts
while (have_posts()) {
    // this func will keep track which post we are working with
    the_post(); ?>
    <!-- By droping out of php the dropping back in we enter HTML mode! -->
    <!-- the title func give you title -->
    <!-- <h1>This is a page not a post</h1>
        <h2><?php the_title(); ?></h2>
        <?php the_content(); ?> -->
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
        <?php
        // example of using post ID's in wp built in functions to get parent id from child page.
        // pass in id of our goals page (using get the id func) into get parent id func and it will return the id of that childs parent
        // will return 0 if page does not have parent
        $the_parent = wp_get_post_parent_id(get_the_ID());
        if ($the_parent) { ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?php echo get_permalink($the_parent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($the_parent); ?></a> <span class="metabox__main">
                        <?php the_title(); ?>
                    </span>
                </p>
            </div>
        <?php }
        ?>


        <!-- <div class="page-links">
            <h2 class="page-links__title"><a href="#">About Us</a></h2>
            <ul class="min-list">
                <li class="current_page_item"><a href="#">Our History</a></li>
                <li><a href="#">Our Goals</a></li>
            </ul>
        </div> -->

        <div class="generic-content">
            <?php the_content(); ?>
        </div>
    </div>
<?php }

get_footer();
?>