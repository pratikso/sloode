<?php

add_filter('widget_text', 'do_shortcode');
include_once PLUGIN_PATH . 'frontend/show_latest_video.php';
include_once PLUGIN_PATH . 'frontend/show_video_by_cat.php';
include_once PLUGIN_PATH . 'frontend/show_video_player_by_cat.php';
include_once PLUGIN_PATH . 'frontend/show_video_by_series.php';
include_once PLUGIN_PATH . 'frontend/show_video_player.php';
include_once PLUGIN_PATH . 'frontend/embed_video.php';
include_once PLUGIN_PATH . 'frontend/show_related_video.php';
include_once PLUGIN_PATH . 'frontend/break.php';
include_once PLUGIN_PATH . 'frontend/show_live_streaming.php';
include_once PLUGIN_PATH . 'frontend/autoimport.php';
include_once PLUGIN_PATH . 'frontend/hide_thumb.php';
include_once PLUGIN_PATH . 'frontend/comments_functions.php';
include_once PLUGIN_PATH . 'frontend/comments.php';
include_once PLUGIN_PATH . 'frontend/show_like_dislike.php';
include_once PLUGIN_PATH . 'frontend/sloode_search.php';

include_once PLUGIN_PATH . 'frontend/show_special_category.php';
include_once PLUGIN_PATH . 'frontend/show_normal_category.php';
include_once PLUGIN_PATH . 'frontend/sloode_category_video.php';
include_once PLUGIN_PATH . 'frontend/sloode_category_list.php';
include_once PLUGIN_PATH . 'frontend/show_category_groups.php';

