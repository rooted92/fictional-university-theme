<?php
// We will create our custom rest api fields here to create a custom search to retrieve specific data 

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch()
{
    // takes 3 args: a namespace (must be unique), a route( then ending part of url, think c# routes),  
    register_rest_route(
        'university/v1',
        'search',
        array(
            'methods' => WP_REST_SERVER::READABLE,
            // instead of 'GET' we use WP REST SERVER to dynamically set get request depending on server
            'callback' => 'universitySearchResults'
            // 'permission_callback' => '__return_true'
        )
    );
}

// this function returns whatever we search for, we use data parameter to access whatever the search was
function universitySearchResults($data)
{
    // PHP can convert to JSON format
    // creating new query class
    // main query will
    $mainQuery = new WP_Query(
        array(
            // an array as value for 'post' will allow us to search through all post types
            'post' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
            // now the data for ten most recent professors will get returned
            's' => sanitize_text_field($data['term']) // $data is an array that wp puts together, term is a property we acces from the array(it can be any name you give it). sanitize text field will add protection
        )
    );

    // results array will hold arrays for each post type
    $results = array(
        'general_info' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array()
    );

    // we will loop through professors array. have_posts will get you all posts
    while ($mainQuery->have_posts()) {
        $mainQuery->the_post(); // will get you all data for each post
        // set up logic to add correct data to corresponding array
        if (get_post_type() == 'post' OR get_post_type() == 'page') {
            // here we are saying the array we want to add on to is 'general_info' array
            array_push(
                $results['general_info'],
                array(
                    // we will add a new array to empty array
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'post_type' => get_post_type(),
                    'author_name' => get_the_author()
                )
            );
        }

        if (get_post_type() == 'professor') {
            // here we are saying the array we want to add on to is 'general_info' array
            array_push(
                $results['professors'],
                array(
                    // we will add a new array to empty array
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professor_landscape')
                )
            );
        }

        if (get_post_type() == 'program') {
            // here we are saying the array we want to add on to is 'general_info' array
            array_push(
                $results['programs'],
                array(
                    // we will add a new array to empty array
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink()
                )
            );
        }

        if (get_post_type() == 'event') {
            // here we are saying the array we want to add on to is 'general_info' array
            array_push(
                $results['events'],
                array(
                    // we will add a new array to empty array
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink()
                )
            );
        }

        if (get_post_type() == 'campus') {
            // here we are saying the array we want to add on to is 'general_info' array
            array_push(
                $results['campuses'],
                array(
                    // we will add a new array to empty array
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink()
                )
            );
        }

    }

    // this returns array of objects, we extract posts from professors array
    return $results;
}

?>