<?php

function show_special_category() {
    $cat_arr = get_kronopress_video_cat();
    $special_cat_arr = array();
    if (is_array($cat_arr)) {
        foreach ($cat_arr as $cat) {
            if ($cat['Type'] == "Special") {
                $special_cat_arr[] = $cat;
            }
        }
    }
    $count = count($special_cat_arr);
    ob_start();
    if ($count > 0) {
        echo '<div class="special_categories">';
        echo "<ul>";
        foreach ($special_cat_arr as $cat_s) {
            echo "<li>" . $cat_s['CategoryName'] . "</li>";
        }
        echo "</ul>";
        echo '</div>';
    }
    return ob_get_clean();
}

add_shortcode('show_special_category', 'show_special_category');
