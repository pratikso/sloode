<?php

/*
  Plugin Name: Sloode
  Plugin URI: http://www.sloode.com
  Description: Plugin to get videos from sloode API
  Author: Microvision s.r.l.
  Version: 1.3.7
  Author URI: http://www.sloode.com
 */
//----------------

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('PLUGIN_PATH', plugin_dir_path(__FILE__));
$krono_url = get_option('krono_api_url') . "/";
if (get_option('krono_api_url') == "") {
    $krono_url = "http://api.kronopress.com/";
}
define('KRONO_URL', $krono_url);
define('KRONO_API_LINK', KRONO_URL . 'WCFServices/Kronopress.svc/');
define('KRONO_API_LINK2', 'http://vod1.kronopress.com/KronopressService.svc/');


$api_key = get_option('krono_api_key');
$api_secret = get_option('krono_api_secret');
define('API_KEY', $api_key);
define('API_SECRET', $api_secret);
define('ALL_VIDEO_API_URL', KRONO_API_LINK . "GetVideoList/" . API_KEY . "/" . API_SECRET . "/300");


$check_valid_key_url = KRONO_API_LINK . "IsPortalExist/" . API_KEY;
$check_valid_key_response = wp_remote_retrieve_body(wp_remote_get($check_valid_key_url, array('sslverify' => false)));
$check_valid_key_arr = json_decode($check_valid_key_response, true);

if (API_KEY != "" && $check_valid_key_arr) {
    define('IS_VALID_API', 1);
} else {
    define('IS_VALID_API', 0);
}


if (API_SECRET != "") {
    define('IS_VALID_SECRET', 1);
} else {
    define('IS_VALID_SECRET', 0);
}


if (API_KEY != "" && $check_valid_key_arr && IS_VALID_SECRET) {
    $check_url = KRONO_API_LINK . "IsKeyValid/" . API_KEY . "/" . API_SECRET;
    $check_response = wp_remote_retrieve_body(wp_remote_get($check_url, array('sslverify' => false)));
    $check_arr = json_decode($check_response, true);

    define('API_MODE', $check_arr);
}




include_once PLUGIN_PATH . 'functions.php';
include_once PLUGIN_PATH . 'admin/admin.php';
include_once PLUGIN_PATH . 'frontend/frontend.php';
include_once PLUGIN_PATH . 'widgets/widgets.php';

add_action('admin_enqueue_scripts', 'add_kronopress_admin_scripts');
add_action('wp_enqueue_scripts', 'add_kronopress_front_scripts');

function add_kronopress_front_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('js_frontend', plugins_url('frontend/js/front_js.js', __FILE__), 'jquery');
    wp_localize_script('js_frontend', 'front_js_vars', array(
        'video_placeholder' => plugins_url('images/video_placeholder.jpg', __FILE__),
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
    wp_enqueue_style('kronopress_front_style', plugins_url('frontend/css/front_style.css', __FILE__));
}

function add_kronopress_admin_scripts() {
    wp_enqueue_style('kronopress_admin_style', plugins_url('admin/css/admin-style.css', __FILE__));
    wp_enqueue_style('datepicker_ui_style', plugins_url('admin/css/jquery-ui.css', __FILE__));
    wp_enqueue_style('datatable_style', plugins_url('admin/css/ jquery.dataTables.css', __FILE__));
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery_ui', plugins_url('admin/js/jquery-ui.js', __FILE__), 'jquery');
    wp_enqueue_script('js_datatable', plugins_url('admin/js/jquery.dataTables.js', __FILE__), 'jquery');
    wp_enqueue_script('js_validation', plugins_url('admin/js/jquery.validate.min.js', __FILE__), 'jquery');
    wp_enqueue_script('js_validation_methods', plugins_url('admin/js/additional-methods.min.js', __FILE__), 'jquery');
    wp_enqueue_script('js_admin', plugins_url('admin/js/admin_js.js', __FILE__), 'jquery');
    wp_localize_script('js_admin', 'admin_js_vars', array(
        'video_placeholder' => plugins_url('images/video_placeholder.jpg', __FILE__),
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
    wp_enqueue_script('js_editor', plugins_url('admin/js/editor-button.js', __FILE__), 'jquery');
    wp_enqueue_script('js_import_btn', plugins_url('admin/js/import_button.js', __FILE__), 'jquery');
}

?>