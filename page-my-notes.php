<?php
// check if user is logged in
if (!is_user_logged_in()) {
    wp_redirect(esc_url(esc_url(site_url('/'))));
    exit;// run exit to stop the redirect from running
}

get_header();
// using wp function have_posts() to get all posts
while (have_posts()) {
    // this func will keep track which post we are working with
    the_post(); 
    pageBanner(); ?>

    <div class="container container--narrow page-section">
        <ul class="min-list link-list" id="my-notes">
            <?php

            // get all notes for current user
            $userNotes = new WP_Query(array(
                'post_type' => 'note',
                'posts_per_page' => -1, // -1 means get all posts
                'author' => get_current_user_id() // get current user id
            ));

            // When ever we use information from the database we use esc_attr() to escape the data
            while($userNotes->have_posts()){
                $userNotes->the_post(); ?>
                <li>
                    <input class="note-title-field" value="<?php echo esc_attr( get_the_title() ); ?>">
                    <span class="edit-note"><i class="fa fa-pencil" area-hidden="true"></i>Edit</span>
                    <span class="delete-note"><i class="fa fa-trash-o" area-hidden="true"></i>Delete</span>
                    <textarea class="note-body-field"><?php echo esc_attr(get_the_content()); ?></textarea>
                </li>
            <?php }
            
            ?>
        </ul>
    </div>
<?php }

get_footer();
?>