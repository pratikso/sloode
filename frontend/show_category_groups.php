<?php

function show_category_groups($atts) {
    $saved_data = get_option('category_groups');
    if (empty($saved_data)) {
        return;
    } else {
        $a = shortcode_atts(array(
            'height' => 150,
            'width' => 150,
            'view' => 'list',
            'template' => 'style1',
                ), $atts);
        extract($a, EXTR_PREFIX_ALL, 'attr');
        $sloode_category_page_id = get_option('sloode_category_page_id');
        $sloode_category_page_link = get_permalink($sloode_category_page_id);

        ob_start();
        if ($attr_view == 'grid') {
            $groups = get_group_data($saved_data);
            $ThumbnailPlaceholder = plugins_url('images/video_placeholder.jpg', dirname(__FILE__));
            if ($attr_template == 'style2') {
                foreach ($groups as $group) {
                    ?>
                    <div class="video_thumb2 count_thumb" style="height: 140px; width: 140px;">
                        <div class="thumb_img2">
                            <a href="<?= $group[2] ?>">
                                <img src="<?= $group[1] ?>" onerror="this.src='<?= $ThumbnailPlaceholder; ?>'">
                            </a>
                        </div>
                        <div class="thumb_detail">
                            <div class="thumb_ttl"><?= $group[0]; ?></div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                foreach ($groups as $group) {
                    ?>
                    <div class="video_thumb count_thumb" style="height: 150px; width: 150px;">
                        <img src="<?= $group[1] ?>" onerror="this.src='<?= $ThumbnailPlaceholder; ?>'">
                        <a href="<?= $group[2] ?>">
                            <div class="thumb_overlay">
                                <div class="video_title"><?= $group[0]; ?></div>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            }
        } else {
            //$groups_name_array = array();
            echo '<ul class="category_groups">';
            foreach ($saved_data as $value) {
                $groups_name = $value['group_name'];
                ?>
                <li>
                    <a href="<?php echo esc_url($sloode_category_page_link . '?sloode_group=' . $groups_name); ?>">
                        <?php echo $groups_name; ?>
                    </a>
                </li>
                <?php
            }
            echo '</ul>';
        }
        return ob_get_clean();
    }
}

add_shortcode('show_category_groups', 'show_category_groups');

function get_group_thumbnail_src($cat_array) {
    foreach ($cat_array as $cat_value) {
        $CategoryId = $cat_value;
        $Thumbnail_cat = get_transient('sloode_category_thumbs_' . $CategoryId);
        if (!$Thumbnail_cat) {
            $video = get_latest_videos_by_cat($CategoryId, 1);
            $Thumbnail_cat = $video[0]['Thumbnail'];
            if (!empty($Thumbnail_cat)) {
                set_transient('sloode_category_thumbs_' . $CategoryId, $Thumbnail_cat, 12 * HOUR_IN_SECONDS);
                break;
            }
        } else {
            break;
        }
    }
    if (!$Thumbnail_cat) {
        $Thumbnail_cat = plugins_url('images/video_placeholder.jpg', dirname(__FILE__));
    }
    return $Thumbnail_cat;
}

function get_group_data($saved_data) {
    $groups = array();
    $sloode_category_page_id = get_option('sloode_category_page_id');
    $sloode_category_page_link = get_permalink($sloode_category_page_id);
    foreach ($saved_data as $value) {
        $group_slug = sanitize_title($value['group_name']);
        $Thumbnail = get_transient('sloode_group_thumb_' . $group_slug);
        if (!$Thumbnail) {
            $Thumbnail = get_group_thumbnail_src($value['group_cate']);
            set_transient('sloode_category_thumbs_' . $group_slug, $Thumbnail, 24 * HOUR_IN_SECONDS);
        }
        $link = esc_url($sloode_category_page_link . '?sloode_group=' . $value['group_name']);
        $groups[] = array($value['group_name'], $Thumbnail, $link);
    }
    return $groups;
}
