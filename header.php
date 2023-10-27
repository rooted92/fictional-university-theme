<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <!-- Instead of using link we use php and call wp_head() to allow wp to be in control of head section
        now we just tell wp to load css file, we add functions.php file -->
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    wp_head();
    ?>

</head>

<body <?php body_class(); ?>>
    <header class="site-header">
        <div class="container">
            <h1 class="school-logo-text float-left">
                <!-- if we call site_url() it will automatically take you back to homepage -->
                <a href="<?php echo site_url() ?>"><strong>Fictional</strong> University</a>
            </h1>
            <a href="<?php echo esc_url(site_url('/search')); ?>"
                class="js-search-trigger site-header__search-trigger"><i class="fa fa-search"
                    aria-hidden="true"></i></a>
            <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
            <div class="site-header__menu group">
                <nav class="main-navigation">
                    <!-- <?php
                    // this function requires array of arguments * ALSO THIS IS EXAMPLE OF DYNAMIC NAV MENUS(see footer too)
                    wp_nav_menu(
                        array(
                            'theme_location' => 'headerMenuLocation' // same as in functions file
                        )
                    );
                    ?> -->
                    <ul>
                        <!-- added logic to check if current item if so give it css style -->
                        <!-- is_page() returns a boolean if the argument you give is true, the arg needs to be a slug (section of url) -->
                        <!-- or if  the current pages' parent page is the about us page, then five it the current-menu-item class-->
                        <li <?php if (is_page('about-us') or wp_get_post_parent_id(0) == 12)
                            echo 'class="current-menu-item"' ?>><a href="<?php echo site_url('/about-us') ?>">About Us</a>
                        </li>
                        <li <?php if (get_post_type() == 'program')
                            echo 'class="current-menu-item"' ?>><a
                                href="<?php echo get_post_type_archive_link('program') ?>">Programs</a></li>
                        <li <?php if (get_post_type() == 'event' or is_page('past-events'))
                            echo 'class="current-menu-item"' ?>><a
                                href="<?php echo get_post_type_archive_link('event') ?>">Events</a></li>
                        <li <?php if (get_post_type() == 'campus')
                            echo 'class="current-menu-item"' ?>><a
                                href="<?php echo get_post_type_archive_link('campus'); ?>">Campuses</a></li>
                        <li <?php if (get_post_type() == 'post')
                            echo 'class="current-menu-item"' ?>><a
                                href="<?php echo site_url('/blog') ?>">Blog</a></li>
                    </ul>
                </nav>
                <div class="site-header__util">
                    <?php
                    if (is_user_logged_in()) { ?>
                        <a href="<?php echo wp_logout_url(); ?>"
                            class="btn btn--small btn--dark-orange float-left btn--with-photo">
                            <span class="site-header__avatar"><?php echo get_avatar(get_current_user_id(), 60) ?></span>
                            <span class="btn__text">Log Out</span>
                        </a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(site_url('/wp-login.php')); ?>" class="btn btn--small btn--orange float-left push-right">Login</a>
                        <a href="<?php echo esc_url(site_url('/wp-signup.php')); ?>"
                            class="btn btn--small btn--dark-orange float-left">Sign Up</a>
                    <?php }
                    ?>

                    <a href="<?php echo esc_url(site_url('/search')); ?>" class="search-trigger js-search-trigger"><i
                            class="fa fa-search" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </header>

    <!-- deleted closing body and html because we want to close it out at the end of the last content that gets generated (footer) -->