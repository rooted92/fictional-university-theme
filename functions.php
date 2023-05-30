<?php
// we can give wp instructions by using add_action() function
// first argument tells wordpress what you want to do second arg tells wp what func to run
function university_files()
{
    // here we are loading js file there is a third argument for dependencies, in this case jquery, 4th arg is version, 5th arg is do you want to laod this file right before the closing body tag, set to true, so it loads at end, not beginning
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    // in here we will tell what file to run (css)
    // if you wanted to downloand a js file you would change 'style' to 'script'
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); //here we are getting font awesome icons
    wp_enqueue_style('custom_google_fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css')); //importing css files from folder we downloaded from github
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}
add_action('wp_enqueue_scripts', 'university_files');

// ths function will add features
function university_features()
{
    // this function will add title to headers
    add_theme_support('title-tag');
}

add_action('after_setup_theme', 'university_features');