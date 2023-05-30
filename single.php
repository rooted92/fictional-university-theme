<?php
    get_header();
// using wp function have_posts() to get all posts
    while(have_posts()){
        // this func will keep track which post we are working with
        the_post(); ?>
        <!-- By droping out of php the dropping back in we enter HTML mode! -->
        <!-- the title func give you title -->
        <h2><?php the_title(); ?></h2>
        <?php the_content(); ?>
    <?php }

    get_footer();
?>