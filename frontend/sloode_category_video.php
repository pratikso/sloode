<?php

function sloode_category_video($atts) {
    if (!isset($_GET['sloode_category'])) {
        return;
    } else {
        $sloode_category = (int) $_GET['sloode_category'];
        $atts_new = shortcode_atts(array(
            'count' => 0,
            'per_page' => 50,
            //'cat_id' => $sloode_category,
            'height' => 150,
            'width' => 150,
            'show_loadmore' => 'yes',
            'show_title' => 'yes',
            'widget' => 'no',
            'play_button' => 'no',
            'template' => 'style1',
            'show_hits' => 'yes',
            'show_cat' => 'yes',
            'show_date' => 'no',
                ), $atts);
        $atts_new['cat_id'] = $sloode_category;
        return show_videos_by_cat_func($atts_new);
    }
}

add_shortcode('sloode_category_video', 'sloode_category_video');
