<?php

function sloode_category_list($atts) {
    if (!isset($_GET['sloode_group'])) {
        return;
    }
    $a = shortcode_atts(array(
        'height' => 150,
        'width' => 150,
        'template' => 'style2',
            ), $atts);
    extract($a);
    //$template = 'style2';
    $group_name = urldecode($_GET['sloode_group']);
    $saved_data = get_option('category_groups');
    $all_category_data = $cat_arr = get_kronopress_video_cat();
    $sloode_category_video_page_id = get_option('sloode_category_video_page_id');
    $result_data = array();
    if (!empty($saved_data) && is_array($saved_data)) {
        foreach ($saved_data as $value) {
            if ($value['group_name'] == $group_name) {
                $result_data = $value['group_cate'];
            }
        }
    }
    ob_start();
    if (!empty($result_data) && !empty($all_category_data)) {
        //echo '<ul class="group_child_category">';
        foreach ($all_category_data as $value) {
            $CategoryId = $value['CategoryId'];
            if (in_array($value['CategoryId'], $result_data)) {
                $Thumbnail = get_transient('sloode_category_thumbs_' . $CategoryId);
                $ThumbnailPlaceholder = plugins_url('images/video_placeholder.jpg', dirname(__FILE__));
                if (!$Thumbnail) {
                    $video = get_latest_videos_by_cat($CategoryId, 1);
                    $Thumbnail = $video[0]['Thumbnail'];
                    if (empty($Thumbnail)) {
                        $Thumbnail = $ThumbnailPlaceholder;
                    } else {
                        set_transient('sloode_category_thumbs_' . $CategoryId, $Thumbnail, 12 * HOUR_IN_SECONDS);
                    }
                }
                $link = get_permalink($sloode_category_video_page_id) . '?sloode_category=' . $value['CategoryId'];
                if ($template == 'style1') {
                    ?>
                    <div class="video_thumb count_thumb" style="height: 150px; width: 150px;">
                        <img src="<?= $Thumbnail ?>" onerror="this.src='<?= $ThumbnailPlaceholder; ?>'">
                        <a href="<?= esc_url($link) ?>">
                            <div class="thumb_overlay">
                                <div class="video_title"><?= $value['CategoryName']; ?></div>
                            </div>
                        </a>
                    </div>
                    <?php
                } elseif ($template == 'style2') {
                    ?>
                    <div class="video_thumb2 count_thumb" style="height: 140px; width: 140px;">
                        <div class="thumb_img2">
                            <a href="<?= esc_url($link) ?>">
                                <img src="<?= $Thumbnail ?>" onerror="this.src='<?= $ThumbnailPlaceholder; ?>'">
                            </a>
                        </div>
                        <div class="thumb_detail">
                            <div class="thumb_ttl"><?= $value['CategoryName']; ?></div>
                        </div>
                    </div>
                    <?php
                }
            }
        }
    }
    return ob_get_clean();
}

add_shortcode('sloode_category_list', 'sloode_category_list');
