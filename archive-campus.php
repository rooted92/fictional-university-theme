<?php
get_header();
pageBanner(
    array(
        'title' => 'Our Campuses',
        'subtitle' => 'We have several conveniently located campuses.'
    )
)
    ?>

<div class="container container--narrow page-section">
    <div class="acf-map">
        <?php
        // custom queries aren't always the answer!
        // if we can work with the default query, there is no need  for a custom query
        // we can just tweak the default query
        while (have_posts()) {
            the_post();
            $map_location = get_field('map_location'); ?>
            <div class="marker" data-lat="<?php echo $map_location['lat']; ?>" data-lng="<?php echo $map_location['lng']; ?>">

            </div>
        <?php }
        echo paginate_links()
            ?>
    </div>
</div>

<?php
get_footer();
?>