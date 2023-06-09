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
            <p>
                <a class="metabox__blog-home-link" href="<?php echo site_url('/blog'); ?>"><i class="fa fa-home"
                        aria-hidden="true"></i> Blog Home
                </a> <span class="metabox__main">
                Posted by: <?php the_author_posts_link(); ?> on <?php the_time('F j, Y'); ?> in <?php echo get_the_category_list(', '); ?>
                </span>
            </p>
        </div>
        <div class="generic-content">
            <?php the_content(); ?>
        </div>
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