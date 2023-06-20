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
        if (get_post_type() == 'post' or get_post_type() == 'page') {
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
            // the get_field function is how you retrieve value of advanced custom field
            $related_campuses = get_field('related_campus'); // returns an array

            if ($related_campuses) {
                foreach ($related_campuses as $campus) {
                    // pass in associative array with properties we want
                    array_push($results['campuses'], array(
                        // title and permalink by default are only related to the post, because that is the post that is being looped through
                        'title' => get_the_title($campus),// we want title and permalink for the campus, so pass in campus
                        'permalink' => get_the_permalink($campus)
                    ));
                }
            }


            // here we are saying the array we want to add on to is 'general_info' array
            array_push(
                $results['programs'],
                array(
                    // we will add a new array to empty array
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'id' => get_the_ID()
                )
            );
        }

        if (get_post_type() == 'event') {
            $event_date = new DateTime(get_field('event_date'));

            $description = null;
            if (has_excerpt()) {
                $description = get_the_excerpt();
            } else {
                $description = wp_trim_words(get_the_content(), 18);
            }
            // here we are saying the array we want to add on to is 'general_info' array
            array_push(
                $results['events'],
                array(
                    // we will add a new array to empty array
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $event_date->format('M'),
                    'day' => $event_date->format('d'),
                    'description' => $description
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

    // this if statement is checking first if programs array is empty or not. If empty run the code else don't run the code.
    if ($results['programs']) {
        $programs_meta_query = array(
            'relation' => 'OR'
        );

        foreach ($results['programs'] as $item) {
            array_push(
                $programs_meta_query,
                array(
                    'key' => 'related_programs',
                    //the key just needs the custom field name
                    'compare' => 'LIKE',
                    //we are using like because we are searching for number disguised as strings
                    'value' => '"' . $item['id'] . '"'
                )
            );
        }

        $program_relationship_query = new WP_Query(
            array(
                'post_type' => array('professor', 'event'),
                'meta_query' => $programs_meta_query
            )
        );

        while ($program_relationship_query->have_posts()) {
            $program_relationship_query->the_post();
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

            if (get_post_type() == 'event') {
                $event_date = new DateTime(get_field('event_date'));

                $description = null;
                if (has_excerpt()) {
                    $description = get_the_excerpt();
                } else {
                    $description = wp_trim_words(get_the_content(), 18);
                }
                // here we are saying the array we want to add on to is 'general_info' array
                array_push(
                    $results['events'],
                    array(
                        // we will add a new array to empty array
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'month' => $event_date->format('M'),
                        'day' => $event_date->format('d'),
                        'description' => $description
                    )
                );
            }

        }

        // function to remove duplicates, array_values gets rid of default 'IDs'
        $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
        $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
    }

    // this returns array of objects, we extract posts from professors array
    return $results;
}

?>