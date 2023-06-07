<!-- <?php
function myFirstFunction($number = 2)
{
    echo "<h1>(5 + 5) / $number * 4</h1>";
}
myFirstFunction(4);
myFirstFunction();
?> -->


<!-- FUNCTIONS -->
<!-- <?php
function greet($name, $color)
{
    echo "<p>Hi, my name is $name and my favorite color is $color</p>";
}
greet('John', 'black');
greet('Jane', 'green');
?> -->

<!-- Example of WP built in function -->
<!-- <h1><?php bloginfo('name'); ?></h1>
<p><?php bloginfo('description'); ?></p> -->

<!-- ARRAYS -->
<?php
// arr example
$names = array('Pedro', 'Brad', 'Jane', 'Daniela');

$count = 0;
// we use count() method instead of .length
// while($count < count($names)){
//     echo "<li>Hi, my name is $names[$count]</li>";
//     $count++;
// }

// the while loop, takes in condition and will run until condition is false
// $count = 1;
// while($count <= 100){
//     echo "<li>$count</li>";
//     $count++;
// }

?>

<!-- Loop Through Blog Posts -->
<!-- get_header();// think of this func like a component in react, we are importing the header from header.php -->
<!-- // using wp function have_posts() to get all posts -->
<?php get_header(); ?>

<?php
// if wp function begins with get it likely returns value, else if the it echoes something
// the_title();
// get_the_title();
// the_ID();
// get_the_id();
?>

<div class="page-banner">
    <div class="page-banner__bg-image"
        style="background-image: url(<?php echo get_theme_file_uri('/images/library-hero.jpg') ?>)"></div>
    <div class="page-banner__content container t-center c-white">
        <h1 class="headline headline--large">Welcome!</h1>
        <h2 class="headline headline--medium">We think you&rsquo;ll like it here.</h2>
        <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re
            interested in?</h3>
        <a href="<?php echo get_post_type_archive_link('program') ?>" class="btn btn--large btn--blue">Find Your
            Major</a>
    </div>
</div>

<div class="full-width-split group">
    <div class="full-width-split__one">
        <div class="full-width-split__inner">
            <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>
            <?php
            // this is a custom query, we can also sort items from here
            $today = date('Ymd');
            $homepage_events = new WP_Query(
                array(
                    'posts_per_page' => 2, // when we add -1 we tell wp to return everything that meets the query
                    'post_type' => 'event',
                    //getting event type posts
                    'meta_key' => 'event_date', //
                    'orderby' => 'meta_value_num', // how we tell wp we want to order by meta key, we add num to meta value since dates are nums
                    'order' => 'ASC',
                    'meta_query' => array( //meta querys give you more control when searching for particular values
                        // takes multiple arrays, we only use one because we are checking for dates that are in the past(just one thing)
                        array(
                            // these are our three parameters
                            'key' => 'event_date', // the type of cusomt field
                            'compare' => '>=',
                            //the comparison operator
                            'value' => $today,
                            //todays date
                            'type' => 'numeric' // compares dates as numeric value
                        )
                    ) // this filters out past events, we can give it an array because it takes multiple conditions
                )
            );

            // use have_posts method on homepageevents object to access array of events
            while ($homepage_events->have_posts()) {
                $homepage_events->the_post(); //call the post to get our data for displaying in browser withing the object NOT globally
                // think of the get_template_part function like a component in React
                get_template_part('template-parts/content', 'event');
            }
            ?> <!-- remember this function automatically gets links to page from post -->
            <p class="t-center no-margin"><a href="<?php echo get_post_type_archive_link('event'); ?>"
                    class="btn btn--blue">View All Events</a></p>
        </div>
    </div>
    <div class="full-width-split__two">
        <div class="full-width-split__inner">
            <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>
            <?php
            // Creating our CUSTOM QUERY
            
            // make an instance of wp query class takes in array as arg
            $homepage_posts = new WP_Query(
                array(
                    // this will help us query for only two posts
                    'posts_per_page' => 2,
                    // 'post_type' => 'page'
                    // 'category_name' => 'awards'
                )
            );

            // while we stll have posts
            // now we can use properties from our new $homepage_posts object
            while ($homepage_posts->have_posts()) {
                // the post gets data ready
                // using homepage_posts-> allows us to see what is inside of homepageposts
                $homepage_posts->the_post(); ?>
                <div class="event-summary">
                    <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
                        <span class="event-summary__month">
                            <?php the_time('M'); ?>
                        </span>
                        <span class="event-summary__day">
                            <?php the_time('d'); ?>
                        </span>
                    </a>
                    <div class="event-summary__content">
                        <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h5>
                        <!-- trim words takes two arguments: content you want to limit, how many words to limit too-->
                        <p>
                            <?php if (has_excerpt()) {
                                the_excerpt();
                            } else {
                                echo wp_trim_words(get_the_content(), 18);
                            } ?><a href="<?php the_permalink(); ?>" class="nu gray">Read more</a>
                        </p>
                    </div>
                </div>
            <?php }
            // right after using a custom query we want to reset all our data, sets everything back to default make this a habit
            wp_reset_postdata();
            ?>
            <p class="t-center no-margin"><a href="<?php echo site_url('/blog') ?>" class="btn btn--yellow">View All
                    Blog Posts</a></p>
        </div>
    </div>
</div>

<div class="hero-slider">
    <div data-glide-el="track" class="glide__track">
        <div class="glide__slides">
            <div class="hero-slider__slide"
                style="background-image: url(<?php echo get_theme_file_uri('/images/bus.jpg') ?>)">
                <div class="hero-slider__interior container">
                    <div class="hero-slider__overlay">
                        <h2 class="headline headline--medium t-center">Free Transportation</h2>
                        <p class="t-center">All students have free unlimited bus fare.</p>
                        <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
                    </div>
                </div>
            </div>
            <div class="hero-slider__slide"
                style="background-image: url(<?php echo get_theme_file_uri('/images/apples.jpg') ?>)">
                <div class="hero-slider__interior container">
                    <div class="hero-slider__overlay">
                        <h2 class="headline headline--medium t-center">An Apple a Day</h2>
                        <p class="t-center">Our dentistry program recommends eating apples.</p>
                        <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
                    </div>
                </div>
            </div>
            <div class="hero-slider__slide"
                style="background-image: url(<?php echo get_theme_file_uri('/images/bread.jpg') ?>)">
                <div class="hero-slider__interior container">
                    <div class="hero-slider__overlay">
                        <h2 class="headline headline--medium t-center">Free Food</h2>
                        <p class="t-center">Fictional University offers lunch plans for those in need.</p>
                        <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="slider__bullets glide__bullets" data-glide-el="controls[nav]"></div>
    </div>
</div>

<?php get_footer(); ?>