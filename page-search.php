<?php
get_header();
// using wp function have_posts() to get all posts
while (have_posts()) {
    // this func will keep track which post we are working with
    the_post();
    pageBanner(); ?>
    <!-- By droping out of php the dropping back in we enter HTML mode! -->
    <!-- the title func give you title -->
    <!-- <h1>This is a page not a post</h1>
        <h2><?php the_title(); ?></h2>
        <?php the_content(); ?> -->
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
                    <a class="metabox__blog-home-link" href="<?php echo get_permalink($the_parent); ?>"><i class="fa fa-home"
                            aria-hidden="true"></i> Back to
                        <?php echo get_the_title($the_parent); ?>
                    </a> <span class="metabox__main">
                        <?php the_title(); ?>
                    </span>
                </p>
            </div>
        <?php }
        ?>

        <?php
        // get pages returns pages in memory
        $testArray = get_pages(
            array(
                'child_of' => get_the_ID()
            )
        );

        if ($the_parent or $testArray) { ?>
            <div class="page-links">
                <h2 class="page-links__title"><a href="<?php echo get_permalink($the_parent); ?>"><?php echo get_the_title($the_parent); ?></a></h2>
                <ul class="min-list">
                    <?php
                    if ($the_parent) {
                        $find_children_of = $the_parent;
                    } else {
                        $find_children_of = get_the_ID();
                    }
                    // must pass arguments(associative array) to get specific pages
                    wp_list_pages(
                        array(
                            'title_li' => null,
                            'child_of' => $find_children_of,
                            'sort_column' => 'menu-order'
                        )
                    );
                    ?>
                </ul>
            </div>
        <?php } ?>
        <div class="generic-content">
            <!-- use esc_url for security best practice(it increases security, but only to protect if site has already been hacked) -->
            <?php get_search_form(); ?>
        </div>
    </div>
<?php }

get_footer();
?>