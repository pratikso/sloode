<?php

function show_normal_category() {
    $cat_arr = get_kronopress_video_cat();
    $normal_cat_arr = array();
    $sloode_category_page_id = get_option('sloode_category_page_id');
    $sloode_category_page_link = get_permalink($sloode_category_page_id);
    if (is_array($cat_arr)) {
        foreach ($cat_arr as $cat) {
            if ($cat['Type'] == "Normal") {
                $normal_cat_arr[] = $cat;
            }
        }
    }
    $count = count($normal_cat_arr);
    ob_start();
    if ($count > 0) {
        echo '<div class="normal_categories">';
        echo "<ul>";
        foreach ($normal_cat_arr as $cat_s) {
            ?>
            <li>
                <a href="<?php echo $sloode_category_page_link . '?sloode_category=' . $cat_s['CategoryId']; ?>">
                    <?php echo $cat_s['CategoryName']; ?>
                </a>
            </li>
            <?php
        }
        echo "</ul>";
        echo '</div>';
    }
    return ob_get_clean();
}

add_shortcode('show_normal_category', 'show_normal_category');
