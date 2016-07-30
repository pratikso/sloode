<?php

function register_settings_page() {
//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
    add_menu_page('Sloode Video', 'Sloode Video', 'publish_posts', 'Kronopress', 'all_videos_page', plugins_url('sloode/images/settings.png'), 33.3);

    if (IS_VALID_API == 1 && IS_VALID_SECRET == 1 && API_MODE == "fullaccess") {
        add_submenu_page('show_videos', 'Add Video', 'Add Video', 'publish_posts', 'add_video', 'add_video_page');
        add_submenu_page('add_video', 'Update Video', 'Update Video', 'publish_posts', 'update_video', 'update_video_page');
        add_submenu_page('Kronopress', 'Show Videos', 'Videos', 'publish_posts', 'show_videos', 'all_videos_page');
        add_submenu_page('Kronopress', 'Categories', 'Categories', 'publish_posts', 'categories', 'kronopress_category_page');
        add_submenu_page('categories', 'Categories Group', 'Categories Group', 'publish_posts', 'categories_groups', 'kronopress_category_group_page');
        $series_page = add_submenu_page('Kronopress', 'Series', 'Series', 'publish_posts', 'series', 'kronopress_series_page');
        $episode_page = add_submenu_page('series', 'Episodes', 'Episodes', 'publish_posts', 'episodes', 'kronopress_episodes_page');
        $add_series_page = add_submenu_page('series', 'Add Series', 'Add Series', 'publish_posts', 'add_series', 'kronopress_add_series_page');
        $update_series_page = add_submenu_page('series', 'Update Series', 'Update Series', 'publish_posts', 'update_series', 'kronopress_update_series_page');
        $add_episode_page = add_submenu_page('series', 'Add Episode', 'Add Episode', 'publish_posts', 'add_episode', 'kronopress_add_episode_page');
        $preroll_page = add_submenu_page('Kronopress', 'Preroll', 'Preroll', 'publish_posts', 'preroll', 'kronopress_preroll_page');
        $add_preroll_page = add_submenu_page('preroll', 'Add Preroll', 'Add Preroll', 'publish_posts', 'add_preroll', 'kronopress_add_preroll_page');
        add_submenu_page('Kronopress', 'Playlist', 'Playlist', 'publish_posts', 'playlist', 'kronopress_playlist_page');
        add_submenu_page('Kronopress', 'Import_Videos', 'Import', 'publish_posts', 'inport_videos', 'kronopress_import_page');


        add_action('admin_print_styles-' . $series_page, 'add_series_page_scripts');
        add_action('admin_print_styles-' . $episode_page, 'add_series_page_scripts');
        add_action('admin_print_styles-' . $add_series_page, 'add_series_page_scripts');
        add_action('admin_print_styles-' . $update_series_page, 'add_series_page_scripts');
        add_action('admin_print_styles-' . $add_episode_page, 'add_series_page_scripts');
        add_action('admin_print_styles-' . $preroll_page, 'preroll_page_scripts');
        add_action('admin_print_styles-' . $add_preroll_page, 'preroll_page_scripts');
    }

    add_submenu_page('Kronopress', 'Settings', 'Settings', 'manage_options', 'kronopress_settings', 'kronpress_settings_page');
//add_submenu_page( 'Kronopress','Help', 'Help', 'publish_posts', 'kronopress_help', 'kronpress_help_page' );
    add_submenu_page('Kronopress', 'Shortcodes', 'Shortcodes Guid', 'publish_posts', 'shortcode_help', 'kronpress_shortcode_help_page');
}

function add_series_page_scripts() {
    wp_enqueue_style('series_page_style', plugins_url('admin/css/series-style.css', dirname(__FILE__)));
    wp_enqueue_script('series_page_js', plugins_url('admin/js/series_page.js', dirname(__FILE__)), 'jquery');
    wp_localize_script('series_page_js', 'admin_js_vars', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}

function preroll_page_scripts() {
    wp_enqueue_style('series_page_style', plugins_url('admin/css/series-style.css', dirname(__FILE__)));
    wp_enqueue_script('preroll_page_js', plugins_url('admin/js/preroll_page.js', dirname(__FILE__)), 'jquery');
    wp_localize_script('preroll_page_js', 'admin_js_vars', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}

add_action('admin_menu', 'register_settings_page');

add_action('admin_init', 'register_kronopress_settings');

function register_kronopress_settings() {
    register_setting('krono-settings-group', 'krono_api_key');
    register_setting('krono-settings-group', 'krono_api_secret');
    register_setting('krono-settings-group', 'video_player_page_id');
    register_setting('krono-settings-group', 'display_video_tag');
    register_setting('krono-settings-group', 'krono_player_width');
    register_setting('krono-settings-group', 'krono_player_height');
    register_setting('krono-settings-group', 'enable_autoimport');
    register_setting('krono-settings-group', 'display_city');
    register_setting('krono-settings-group', 'hide_thumb');
    register_setting('krono-settings-group', 'krono_api_url');
    register_setting('krono-settings-group', 'sloode_search_page_id');
    register_setting('krono-settings-group', 'sloode_category_page_id');
    register_setting('krono-settings-group', 'sloode_category_video_page_id');
}

add_action('admin_init', 'krono_category_settings');

function krono_category_settings() {
    $cat_arr = get_kronopress_video_cat();
    foreach ($cat_arr as $cat) {
        register_setting('krono_category_setting_group', 'map_cat_' . $cat['CategoryId']);
    }
}

function kronpress_settings_page() {
    include_once PLUGIN_PATH . 'admin/settings-page.php';
}

function all_videos_page() {
    if (IS_VALID_API == 1 && IS_VALID_SECRET == 1 && API_MODE == "fullaccess") {
        include_once PLUGIN_PATH . 'admin/video-listing.php';
    } else {
        include_once PLUGIN_PATH . 'admin/settings-page.php';
    }
}

function kronopress_category_page() {
    if (IS_VALID_API == 1 && IS_VALID_SECRET == 1 && API_MODE == "fullaccess") {
        include_once PLUGIN_PATH . 'admin/categories-page.php';
    } else {
        include_once PLUGIN_PATH . 'admin/settings-page.php';
    }
}

function kronopress_category_group_page() {
    if (IS_VALID_API == 1 && IS_VALID_SECRET == 1 && API_MODE == "fullaccess") {
        include_once PLUGIN_PATH . 'admin/category-group-page.php';
    } else {
        include_once PLUGIN_PATH . 'admin/settings-page.php';
    }
}

function kronpress_help_page() {
    include_once PLUGIN_PATH . 'admin/help.php';
}

function kronpress_shortcode_help_page() {
    include_once PLUGIN_PATH . 'admin/shortcode_help.php';
}

function kronopress_import_page() {

    if (IS_VALID_API == 1 && IS_VALID_SECRET == 1 && API_MODE == "fullaccess") {
        include_once PLUGIN_PATH . 'admin/import_videos_page.php';
    } else {
        include_once PLUGIN_PATH . 'admin/settings-page.php';
    }
}

function add_video_page() {
    if (IS_VALID_API == 1 && IS_VALID_SECRET == 1 && API_MODE == "fullaccess") {
        include_once PLUGIN_PATH . 'admin/add_video_page.php';
    } else {
        include_once PLUGIN_PATH . 'admin/settings-page.php';
    }
}

function update_video_page() {
    if (IS_VALID_API == 1 && IS_VALID_SECRET == 1 && API_MODE == "fullaccess") {
        include_once PLUGIN_PATH . 'admin/update_video_page.php';
    } else {
        include_once PLUGIN_PATH . 'admin/settings-page.php';
    }
}

function kronopress_series_page() {
    if (IS_VALID_API == 1 && IS_VALID_SECRET == 1 && API_MODE == "fullaccess") {
        include_once PLUGIN_PATH . 'admin/show_series.php';
    } else {
        include_once PLUGIN_PATH . 'admin/settings-page.php';
    }
}

function kronopress_episodes_page() {
    if (IS_VALID_API == 1 && IS_VALID_SECRET == 1 && API_MODE == "fullaccess") {
        include_once PLUGIN_PATH . 'admin/show_episodes.php';
    } else {
        include_once PLUGIN_PATH . 'admin/settings-page.php';
    }
}

function kronopress_add_series_page() {
    if (IS_VALID_API == 1 && IS_VALID_SECRET == 1 && API_MODE == "fullaccess") {
        include_once PLUGIN_PATH . 'admin/add_series_page.php';
    } else {
        include_once PLUGIN_PATH . 'admin/settings-page.php';
    }
}

function kronopress_update_series_page() {
    if (IS_VALID_API == 1 && IS_VALID_SECRET == 1 && API_MODE == "fullaccess") {
        include_once PLUGIN_PATH . 'admin/update_series_page.php';
    } else {
        include_once PLUGIN_PATH . 'admin/settings-page.php';
    }
}

function kronopress_add_episode_page() {
    if (IS_VALID_API == 1 && IS_VALID_SECRET == 1 && API_MODE == "fullaccess") {
        include_once PLUGIN_PATH . 'admin/add_episode_page.php';
    } else {
        include_once PLUGIN_PATH . 'admin/settings-page.php';
    }
}

function kronopress_preroll_page() {
    if (IS_VALID_API == 1 && IS_VALID_SECRET == 1 && API_MODE == "fullaccess") {
        include_once PLUGIN_PATH . 'admin/show_preroll.php';
    } else {
        include_once PLUGIN_PATH . 'admin/settings-page.php';
    }
}

function kronopress_add_preroll_page() {
    if (IS_VALID_API == 1 && IS_VALID_SECRET == 1 && API_MODE == "fullaccess") {
        include_once PLUGIN_PATH . 'admin/add_preroll_page.php';
    } else {
        include_once PLUGIN_PATH . 'admin/settings-page.php';
    }
}

function kronopress_playlist_page() {
    if (IS_VALID_API == 1 && IS_VALID_SECRET == 1 && API_MODE == "fullaccess") {
        include_once PLUGIN_PATH . 'admin/playlist_page.php';
    } else {
        include_once PLUGIN_PATH . 'admin/settings-page.php';
    }
}

?>
