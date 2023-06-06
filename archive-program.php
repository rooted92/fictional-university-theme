<?php
get_header();
pageBanner(array(
    'title' => 'All programs',
    'subtitle' => 'There is something for everyone. Have a look around.'
))
?>

<div class="container container--narrow page-section">
    <ul class="link-list min-list">
        <?php
        // custom queries aren't always the answer!
        // if we can work with the default query, there is no need  for a custom query
        // we can just tweak the default query
        while (have_posts()) {
            the_post(); ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php }
        echo paginate_links()
            ?>
    </ul>    
</div>

<?php
get_footer();
?>