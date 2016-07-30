<?php
function get_kronopress_video_cat() {
    $data = get_transient('cat_data_new');
    if (false === ($data ) || !is_array($data)) {
        $api_url = KRONO_API_LINK . "GetVideoCategoryList/" . API_KEY . "/" . API_SECRET;
        $api_response = file_get_contents($api_url);
        $cat_arr = json_decode($api_response, true);
        $data = set_transient('cat_data_new', $cat_arr, WEEK_IN_SECONDS);
    }
    //$cat_data = $data;
    return $data;
}

function refresh_category_button() {
    if (isset($_REQUEST['refresh_cat'])) {
        refresh_category_list();
    }
}

add_action('admin_init', 'refresh_category_button');

function refresh_category_list() {
    $api_url = KRONO_API_LINK . "GetVideoCategoryList/" . API_KEY . "/" . API_SECRET;
    $cat_data = wp_remote_retrieve_body(wp_remote_get($api_url, array('sslverify' => false)));
    set_transient('cat_data', $cat_data, WEEK_IN_SECONDS);
}

function get_kronopress_video_by_date_arr($startdate, $enddate) {
    $api_url = KRONO_API_LINK . "GetVideoListByDate?APIKey=" . API_KEY . "&SecretKey=" . API_SECRET . "&StartDate=" . $startdate . "&EndDate=" . $enddate;
    $api_response = wp_remote_retrieve_body(wp_remote_get($api_url, array('sslverify' => false)));
    $video_by_date = json_decode($api_response, true);
    return $video_by_date;
}

function get_kronopress_video_by_videoid($videoid) {
    $api_url = KRONO_API_LINK . "GetVideoDetail/" . API_KEY . "/" . API_SECRET . "/" . $videoid;
    $api_response = file_get_contents($api_url);

    $video_detail_arr = json_decode($api_response, true);
    return $video_detail_arr;
}

function krono_latest_videos_arr($numberposts = 20) {
    $api_url = KRONO_API_LINK . "GetVideoList/" . API_KEY . "/" . API_SECRET . "/" . $numberposts;

    $api_response = file_get_contents($api_url);
    $video_arr = json_decode($api_response, true);
    return $video_arr;
}

function krono_most_viewd_videos_arr($date, $cat_id, $order = "") {
    if ($order == "") {
        $api_url = KRONO_API_LINK . 'GetVideoListByHits?APIKey=' . API_KEY . '&SecretKey=' . API_SECRET . '&FromDate=' . $date . '&CategoryId=' . $cat_id;
    } else {
        $api_url = KRONO_API_LINK . 'GetVideoListByHits?APIKey=' . API_KEY . '&SecretKey=' . API_SECRET . '&FromDate=' . $date . '&CategoryId=' . $cat_id . "&Orderby=asc";
    }
    $api_response = file_get_contents($api_url);
    $video_arr = json_decode($api_response, true);
    return $video_arr;
}

function krono_all_videos_arr() {
    //$api_url = KRONO_API_LINK."GetVideoList/".API_KEY."/".API_SECRET."/100";

    $api_url = ALL_VIDEO_API_URL;

    $api_response = file_get_contents($api_url);
    $video_arr = json_decode($api_response, true);

    return $video_arr;
}

function krono_update_video_list() {

    if (false === ( get_transient('is_updated_videos') )) {
        $api_url = KRONO_API_LINK . "GetVideoList/" . API_KEY . "/" . API_SECRET . "/40";
        $all_video_api_url = ALL_VIDEO_API_URL;
        if (false === ( get_transient('krono_all_videos_data'))) {
            $all_video_data = wp_remote_retrieve_body(wp_remote_get($all_video_api_url, array('sslverify' => false)));
            set_transient('krono_all_videos_data', $all_video_data, 5 * WEEK_IN_SECONDS);
        }


        $recent_videos_jason = wp_remote_retrieve_body(wp_remote_get($api_url, array('sslverify' => false)));
        $all_videos_jason = get_transient('krono_all_videos_data');
        $recent_videos_arr = json_decode($recent_videos_jason, true);
        $all_videos_arr = json_decode($all_videos_jason, true);
        $all_videos_arr_slice = array_slice($all_videos_arr, 0, 40);
        $new_videos_arr = array();
        $is_new = 1;
        foreach ($recent_videos_arr as $recent_video) {

            foreach ($all_videos_arr_slice as $all_video) {
                if ($recent_video['VideoId'] == $all_video['VideoId']) {
                    $is_new = 0;
                }
            }
            if ($is_new) {
                array_push($new_videos_arr, $recent_video);
            }
            $is_new = 1;
        }

        $return_arr = array_merge($new_videos_arr, $all_videos_arr);
        $return_jason = json_encode($return_arr);
        set_transient('krono_all_videos_data', $return_jason, WEEK_IN_SECONDS);
        set_transient('is_updated_videos', 'yes', 300);
    }
}

add_action('init', 'krono_update_video_list');

function videos_by_cat_arr($catid = 0, $numberpost) {
    if (!$catid == 0) {
        $api_url = KRONO_API_LINK . "GetVideoListByCategory/" . API_KEY . "/" . API_SECRET . "/" . $numberpost . "/" . $catid;
        if (false === (get_transient('video_by_cat_new' . $catid) )) {
            $video_by_cat = wp_remote_retrieve_body(wp_remote_get($api_url, array('sslverify' => false)));
            set_transient('video_by_cat_new' . $catid, $video_by_cat, 200);
        }
        $video_by_cat_transient = get_transient('video_by_cat_new' . $catid);
        $video_by_cat_arr = json_decode($video_by_cat_transient, true);
        return $video_by_cat_arr;
    }
}

function get_latest_videos_by_cat($catid, $limit = 10) {

    $api_url = KRONO_API_LINK . "GetVideoListByCategory/" . API_KEY . "/" . API_SECRET . "/" . $limit . "/" . $catid;
    $api_response = file_get_contents($api_url);
    $video_by_cat_arr = json_decode($api_response, true);

    return $video_by_cat_arr;
}

function get_latest_videos_by_multi_cat($catid_arr, $limit = 10) {
    $catid = implode(",", $catid_arr);
    $api_url = KRONO_API_LINK . "GetVideoListByCategory/" . API_KEY . "/" . API_SECRET . "/" . $limit . "/" . $catid;
    $api_response = file_get_contents($api_url);
    $video_by_cat_arr = json_decode($api_response, true);

    return $video_by_cat_arr;
}

function get_latest_videos_by_multi_cat_sp($catid_arr_normal, $catid_arr_special, $limit = 10) {
    $catid_normal = implode(",", $catid_arr_normal);
    $catid_special = implode(",", $catid_arr_special);
    //http://api.kronopress.com/WCFServices/Kronopress.svc/GetVideoListbyCategoryTypes/{APIKey}/{SecretKey}/{Top}/{NormalCategoryIds}/{SpecialCategoryIds}

    $api_url = KRONO_API_LINK . "GetVideoListbyCategoryTypes/" . API_KEY . "/" . API_SECRET . "/" . $limit . "/" . $catid_normal . "/" . $catid_special;
    $api_response = file_get_contents($api_url);
    $video_by_cat_arr = json_decode($api_response, true);

    return $video_by_cat_arr;
}

function get_limited_videos_by_cat($catid, $limit, $video_id) {
    $api_url = KRONO_API_LINK . "GetVideoListByCategoryFilter/" . API_KEY . "/" . API_SECRET . "/" . $limit . "/" . $video_id . "/" . $catid;
    $api_response = file_get_contents($api_url);
    $video_list = json_decode($api_response, true);
    return $video_list;
}

function get_limited_videos_by_multi_cat($catid_arr, $limit, $video_id) {
    $catid = implode(",", $catid_arr);
    $api_url = KRONO_API_LINK . "GetVideoListByCategoryFilter/" . API_KEY . "/" . API_SECRET . "/" . $limit . "/" . $video_id . "/" . $catid;
    $api_response = file_get_contents($api_url);
    $video_list = json_decode($api_response, true);
    return $video_list;
}

function get_limited_videos_by_multi_cat_sp($catid_arr_normal, $catid_arr_special, $limit, $video_id) {
    $catid_normal = implode(",", $catid_arr_normal);
    $catid_special = implode(",", $catid_arr_special);
//http://api.kronopress.com/WCFServices/Kronopress.svc/GetVideoListbyCategoryTypesFilter/{APIKey}/{SecretKey}/{Top}/{VideoId}/{NormalCategoryIds}/{SpecialCategoryIds}
    $api_url = KRONO_API_LINK . "GetVideoListbyCategoryTypesFilter/" . API_KEY . "/" . API_SECRET . "/" . $limit . "/" . $video_id . "/" . $catid_normal . "/" . $catid_special;
    $api_response = file_get_contents($api_url);
    $video_list = json_decode($api_response, true);
    return $video_list;
}

add_action('wp_ajax_delete_kronopress_video', 'delete_kronopress_video');

function delete_kronopress_video() {
    $videoid = $_REQUEST['video_id'];
    $api_url = KRONO_API_LINK . "DeleteVideo/" . API_KEY . "/" . API_SECRET . "/" . $videoid;
    $api_response = wp_remote_retrieve_body(wp_remote_post($api_url, array('sslverify' => false, 'method' => 'POST')));
    echo $api_response;
    die;
}

add_action('wp_ajax_upload_on_youtube', 'upload_on_youtube');

function upload_on_youtube() {
    $videoid = $_REQUEST['video_id'];
    $api_url = KRONO_API_LINK2 . "UploadOnYouTube?VideoId=" . $videoid . "&APIKey=" . API_KEY . "&secretkey=" . API_SECRET;
    $api_response = wp_remote_retrieve_body(wp_remote_post($api_url, array('sslverify' => false, 'method' => 'POST')));
    $api_response_arr = json_decode($api_response, true);
    echo $api_response_arr['Message'];
    die;
}

//

function get_date_by_timestamp($timestamp, $format = "") {
    $timestamp = substr($timestamp, 6, -2);
    $timestamp = explode("+", $timestamp);
    $timestamp = floor($timestamp[0] / 1000);
    if ($format == "") {
        $format = get_option('date_format');
    }
    $date = date_i18n($format, $timestamp);
    return $date;
}

function get_time_by_timestamp($timestamp, $format = "") {
    $timestamp = substr($timestamp, 6, -2);
    $timestamp = explode("+", $timestamp);
    $timestamp = floor($timestamp[0] / 1000);
    if ($format == "") {
        $format = get_option('time_format');
    }
    $time = date_i18n($format, $timestamp);
    return $time;
}

function video_listing_popup_for_editor() {
    $btn_url = plugins_url('images/close_button.jpg', __FILE__);
    $loading_img_url = plugins_url('images/loading.gif', __FILE__);
    echo '<div class="video_list_pop"> <img src="' . $btn_url . '" class="close_btn">
	<div class="video_list_pop_inner">
	<div class="loading_img">Loading....<br/><img src="' . $loading_img_url . '"> </div>
	<div class="thumb_list_in_popup">
	</div>
	</div>
	</div>';
    echo '<div class="vid_pop">
	<img src="' . $btn_url . '" class="close_btn">
	<div class="loading_img2">Loading....<br/><img src="' . $loading_img_url . '"></div>
	<div class="vid_pop_inner"></div>
	</div>';
}

add_action('admin_footer', 'video_listing_popup_for_editor');

add_action('wp_ajax_get_video_list_in_popup', 'get_video_list_popup');

function get_video_list_popup() {
    ob_start();
    $per_page = 20;
    $video_arr = krono_latest_videos_arr(10);
    $video_id = $video_arr[0]['VideoId'];
    $video_id_arr = array();
    $counter = 1;
    $record_size = 50;
    ?>

    <table id="filter_video" class="display" cellspacing="0" width="95%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Thumb</th>
                <th>Title</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <input type="hidden" class="recent_perpage" name="recent_perpage" value="<?php echo $per_page; ?>">		
        <?php
        while ($counter <= $per_page) {
            $video_arr = get_limited_videos($record_size, $video_id);
            $array_size = count($video_arr);
            foreach ($video_arr as $video_thumb) {
                $video_id = $video_thumb['VideoId'];
                $videoid = $video_id;

                if (!in_array($video_id, $video_id_arr) && $counter <= $per_page) {
                    if ($counter % 2 == 0) {
                        $trclass = "odd";
                    } else {
                        $trclass = "even";
                    }
                    ?>
                    <tr class="vid_row_<?php echo $videoid . " " . $trclass; ?>">
                        <td><?php echo $videoid; ?></td>
                        <td>
                            <div class="video_thumb_in_editor" id="krono_thumb_id_<?php echo $videoid ?>">
                                <input type="hidden" value="<?php echo $videoid ?>" name="thumb_id" class="krono_thumb_id_<?php echo $videoid ?>">
                                <?php
                                $thumb_url = $video_thumb['Thumbnail'];
                                if ($thumb_url == "") {
                                    $thumb_url = plugins_url('images/video_placeholder.jpg', __FILE__);
                                }
                                ?>
                                <img src="<?php echo $thumb_url; ?>" />
                                <?php $magnifier_icon = plugins_url('images/magni-icon.png', __FILE__); ?>
                                <div class="thumb_overlay">
                                    <img src="<?php echo $magnifier_icon; ?>" class="magni_ico">
                                </div>
                            </div>
                        </td>

                        <td><?php echo $video_thumb['Title']; ?> </td>

                        <td>
                            <input type="text" name="video_height" class="video_height_<?php echo $videoid; ?>" placeholder="Height">
                            <input type="text" name="video_width" class="video_width_<?php echo $videoid; ?>" placeholder="Width">
                            <input type="button" id="krono_video_<?php echo $videoid; ?>" value="Insert Video" name="insert_video" class="button insert_video_btn">
                            <input type="hidden" value="<?php echo $videoid; ?>" name="video_id" class="krono_video_<?php echo $videoid; ?>">
                            <input type="hidden" name="last_video_id_<?php echo $videoid; ?>" class="last_video_id" value="<?php echo $videoid; ?>">
                        </td>
                    </tr>

                    <?php
                    array_push($video_id_arr, $video_id);
                    $counter++;
                }
                $last_video_id = $video_id;

                if ($counter > $per_page) {
                    break;
                }
            }
            if ($array_size < $record_size) {

                break;
            }
        }

        echo ob_get_clean();
        ?>
    </tbody>

    </table>
    <?php
    if ($counter >= $per_page) {
        $loading_img_url = plugins_url('images/loading.gif', __FILE__);
        ?>
        <center>
            <input type="button" class="load_more_videos_button_editor" value="Load More &raquo;">
            <br/>
            <div class="loading_more_videos_editor"><img src="<?php echo $loading_img_url ?>" height="50px" width="50px"><br/>Loading....</div>
            <br/>	<br/>	</center>
    <?php } ?>
    <?php
    echo ob_get_clean();
    die;
}

function get_video_list_popup_bkp() {
    ob_start();
    $video_arr = krono_all_videos_arr();
    ?>
    <table id="filter_video" class="display" cellspacing="0" width="95%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Thumb</th>
                <th>Title</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $video_id_arr = array();
            foreach ($video_arr as $video_thumb) {
                $videoid = $video_thumb['VideoId'];
                if (!in_array($videoid, $video_id_arr)) {
                    ?>
                    <tr>
                        <td><?php echo $videoid; ?></td>
                        <td>
                            <div class="video_thumb_in_editor" id="krono_thumb_id_<?php echo $videoid ?>">
                                <input type="hidden" value="<?php echo $videoid ?>" name="thumb_id" class="krono_thumb_id_<?php echo $videoid ?>">
                                <?php
                                $thumb_url = $video_thumb['Thumbnail'];
                                if ($thumb_url == "") {
                                    $thumb_url = plugins_url('images/video_placeholder.jpg', __FILE__);
                                }
                                ?>
                                <img src="<?php echo $thumb_url; ?>" />
                                <?php $magnifier_icon = plugins_url('images/magni-icon.png', __FILE__); ?>
                                <div class="thumb_overlay">
                                    <img src="<?php echo $magnifier_icon; ?>" class="magni_ico">
                                </div>
                            </div>
                        </td>

                        <td><?php echo $video_thumb['Title']; ?> </td>

                        <td>
                            <input type="text" name="video_height" class="video_height_<?php echo $videoid; ?>" placeholder="Height">
                            <input type="text" name="video_width" class="video_width_<?php echo $videoid; ?>" placeholder="Width">
                            <input type="button" id="krono_video_<?php echo $videoid; ?>" value="Insert Video" name="insert_video" class="button insert_video_btn">
                            <input type="hidden" value="<?php echo $videoid; ?>" name="video_id" class="krono_video_<?php echo $videoid; ?>">

                        </td>
                    </tr>

                    <?php
                    array_push($video_id_arr, $videoid);
                }
            }
            ?></tr>
        </tbody>

    </table>
    <?php
    echo ob_get_clean();
    die;
}

add_action('wp_ajax_get_video_in_popup', 'get_video_popup');

function get_video_popup() {
    $video_id = $_REQUEST['vid_id'];
    $video_detail_arr = get_kronopress_video_by_videoid($video_id);
    $embed_code = $video_detail_arr['EmbedURL'];
    preg_match('/(src=["\'](.*?)["\'])/', $embed_code, $match);  //find src="X" or src='X'
    $split = preg_split('/["\']/', $match[0]); // split by quotes
    $embed_url = $split[1];
    //echo '<iframe src="'.$embed_url.'" width="100%" height="100%"></iframe>';
    echo '<iframe src="' . $embed_url . '" width="100%" height="100%"></iframe>';

    die;
}

//check if blog lisging page
function is_blog() {
    global $post;
    $posttype = get_post_type($post);
    return ( ((is_archive()) || (is_author()) || (is_category()) || (is_home()) || (is_tag()) || (is_search())) && ( $posttype == 'post') ) ? true : false;
}

function display_video_tag() {
    if (get_option('display_video_tag') == "on") {
        echo '<style>
		.video_title_tag
		{
			color: red !important;
			display:block !important;
		}
		</style>';
    }
}

add_action('wp_footer', 'display_video_tag');

function get_add_video_url() {
    $api_url = KRONO_API_LINK . "GetVideoPopURL/" . API_KEY . "/" . API_SECRET;
    $add_video_url_data = wp_remote_retrieve_body(wp_remote_get($api_url, array('sslverify' => false)));
    $add_video_url = json_decode($add_video_url_data, true);

    if (!preg_match("~^(?:f|ht)tps?://~i", $add_video_url)) {
        $add_video_url = "http://" . $add_video_url;
    }

    return $add_video_url;
}

function get_delete_video_url($video_id) {
    $delete_video_url = KRONO_API_LINK . "DeleteVideo/" . API_KEY . "/" . API_SECRET . "/" . $video_id;
    return $delete_video_url;
}

function add_video_tag_in_title($title, $id) {

    $format = get_post_format($id);
    if ($format == 'video' && !is_admin()) {
        $title = $title . "<span class='video_title_tag'> -Video</span>";
    }
    return $title;
}

add_filter('the_title', 'add_video_tag_in_title', 10, 2);

function get_categories_by_video_id($video_id) {
    $api_url = KRONO_API_LINK . "GetCategoryByVideoId/" . API_KEY . "/" . API_SECRET . "/" . $video_id;
    $api_response = file_get_contents($api_url);
    $cat_arr = json_decode($api_response, true);
    return $cat_arr;
}

function get_category_type_by_cat_id($cat_id) {

    if (false === (get_transient('cat_type' . $cat_id) )) {
        $cat_arr = get_kronopress_video_cat();
        foreach ($cat_arr as $cat) {
            if ($cat['CategoryId'] == $cat_id) {
                $cat_type = $cat['Type'];
            }
        }


        set_transient('cat_type' . $cat_id, $cat_type, WEEK_IN_SECONDS);
    }

    $cat_type_transient = get_transient('cat_type' . $cat_id);
    return $cat_type_transient;
}

function get_category_name_by_cat_id($cat_id) {

    if (false === (get_transient('cat_name' . $cat_id) )) {
        $cat_arr = get_kronopress_video_cat();
        foreach ($cat_arr as $cat) {
            if ($cat['CategoryId'] == $cat_id) {
                $cat_name = $cat['CategoryName'];
            }
        }


        set_transient('cat_name' . $cat_id, $cat_name, WEEK_IN_SECONDS);
    }

    $cat_name_transient = get_transient('cat_name' . $cat_id);
    return $cat_name_transient;
}

function add_post_format_support() {
    $supportedTypes = get_theme_support('post-formats');
    if ($supportedTypes === false) {
        add_theme_support('post-formats', array('video'));
    } elseif (is_array($supportedTypes)) {
        $supportedTypes[0][] = 'video';
        add_theme_support('post-formats', $supportedTypes[0]);
    }
}

add_action('after_setup_theme', 'add_post_format_support', 11);

function add_krono_category_func() {
    if (isset($_REQUEST['krono_new_cat'])) {
        $cat_name = $_REQUEST['krono_new_cat'];
        $api_url = KRONO_API_LINK . "AddVideoCategory/" . API_KEY . "/" . API_SECRET . "/" . $cat_name;
        $api_response = wp_remote_retrieve_body(wp_remote_post($api_url, array('sslverify' => false, 'method' => 'POST')));
        refresh_category_list();
    }
}

add_action('admin_init', 'add_krono_category_func');

function add_krono_category_func_old() {
    $cat_name = $_REQUEST['cat_name'];
    $api_url = KRONO_API_LINK . "AddVideoCategory/" . API_KEY . "/" . API_SECRET . "/" . $cat_name;
    $api_response = wp_remote_retrieve_body(wp_remote_post($api_url, array('sslverify' => false, 'method' => 'POST')));
    refresh_category_list();
    if ($api_response == '"Success"') {
        ob_start();
        ?>
        <table class="krono_cat_list_tbl">
            <tr>
                <th class="id_col"> ID </th>
                <th> Name </th>
                <th> </th>
            </tr>

            <?php
            $cat_arr = get_kronopress_video_cat();
            ?>

            <?php
            foreach ($cat_arr as $cat) {
                ?>
                <tr class="cat_row_<?php echo $cat['CategoryId']; ?>">
                    <td> <?php echo $cat['CategoryId']; ?> </td>
                    <td> <?php echo $cat['CategoryName']; ?> </td>
                    <td> <input type="button" value="Delete" class="delete_cat_btn" id="delete_cat_<?php echo $cat['CategoryId']; ?>">
                        <input type="hidden" name="delete_cat" class="delete_cat_<?php echo $cat['CategoryId']; ?>" value="<?php echo $cat['CategoryId']; ?>">
                    </td>
                </tr>
            <?php } ?>
        </table>
        <?php
        echo ob_get_clean();
    } else {
        echo "Error";
    }
    die;
}

add_action('wp_ajax_add_krono_category_old', 'add_krono_category_func_old');


add_action('wp_ajax_delete_krono_category', 'delete_krono_category_func');

function delete_krono_category_func() {
    $cat_id = $_REQUEST['cat_id'];

    $api_url = KRONO_API_LINK . "DeleteVideoCategory/" . API_KEY . "/" . API_SECRET . "/" . $cat_id;
    $api_response = wp_remote_retrieve_body(wp_remote_post($api_url, array('sslverify' => false, 'method' => 'POST')));
    refresh_category_list();
    echo $api_response;
    die;
}

function get_limited_videos($limit, $video_id) {
    $api_url = KRONO_API_LINK . "GetVideoListByFilter/" . API_KEY . "/" . API_SECRET . "/" . $limit . "/" . $video_id;
    $api_response = file_get_contents($api_url);
    $video_list = json_decode($api_response, true);
    return $video_list;
}

function get_live_streaming_list() {
    $api_url = KRONO_API_LINK . "GetLiveStreamingList/" . API_KEY . "/" . API_SECRET;

    $live_streaming_data = wp_remote_retrieve_body(wp_remote_get($api_url, array('sslverify' => false)));
    $live_streaming_arr = json_decode($live_streaming_data, true);
    return $live_streaming_arr;
}

function get_live_streaming_by_channel_id($channel_id) {
    $api_url = KRONO_API_LINK . "GetLiveStreamingListByChannelID/" . API_KEY . "/" . API_SECRET . "/" . $channel_id;
    $api_response = file_get_contents($api_url);
    $live_streaming_arr = json_decode($api_response, true);
    return $live_streaming_arr;
}

function get_url_by_embed_code($embed_code) {

    preg_match('/(src=["\'](.*?)["\'])/', $embed_code, $match);  //find src="X" or src='X'
    $split = preg_split('/["\']/', $match[0]); // split by quotes
    $embed_url = $split[1];
    return $embed_url;
}

add_filter('the_excerpt', 'do_shortcode');

function get_http_response_code($url) {
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
    return $headers;
}

function get_like_dislike_count($video_id) {
    $api_url = KRONO_API_LINK . "GetLikeDislikeCount/" . API_KEY . "/" . API_SECRET . "/" . $video_id;
    $api_response = file_get_contents($api_url);
    $like_arr = json_decode($api_response, true);
    return $like_arr;
}

function do_like($video_id, $user_name = "", $user_email = "") {
    $user_name = "" ? "Guest" : $user_name;
    $user_email = "" ? "guest@noreply.com" : $user_email;
    $api_url = KRONO_API_LINK . "DoLikeDislike/" . API_KEY . "/" . API_SECRET . "/" . $video_id . "/true/" . $user_name . "/" . $user_email;


    $api_response = wp_remote_retrieve_body(wp_remote_post($api_url, array('sslverify' => false, 'method' => 'POST')));
    $like_result = json_decode($api_response, true);
    return $like_result;
}

function do_dislike($video_id, $user_name = "", $user_email = "") {
    $user_name = "" ? "Guest" : $user_name;
    $user_email = "" ? "guest@noreply.com" : $user_email;
    $api_url = KRONO_API_LINK . "DoLikeDislike/" . API_KEY . "/" . API_SECRET . "/" . $video_id . "/false/" . $user_name . "/" . $user_email;
    $api_response = wp_remote_retrieve_body(wp_remote_post($api_url, array('sslverify' => false, 'method' => 'POST')));
    $like_result = json_decode($api_response, true);
    return $like_result;
}

function get_like_dislike($video_id, $user_name = "", $user_email = "") {
    $user_name = "" ? "Guest" : $user_name;
    $user_email = "" ? "guest@noreply.com" : $user_email;
    $api_url = KRONO_API_LINK . "GetLikeDislike/" . API_KEY . "/" . API_SECRET . "/" . $video_id . "/" . $user_email;
    $api_response = file_get_contents($api_url);
    $result = json_decode($api_response, true);
    return $result;
}

function random_string($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function set_temp_user() {
    if (!isset($_COOKIE['temp_user_name'])) {
        $rand_name = random_string();
        setcookie('temp_user_name', $rand_name, time() + (3600 * 12), "/");
    }
}

add_action('init', 'set_temp_user');

function get_videos_by_keyword($key, $limit = 10) {
    $key = str_replace(' ', '%20', trim($key));
    $api_url = KRONO_API_LINK . "GetVideoListByTitle/" . API_KEY . "/" . API_SECRET . "/" . $limit . "/" . $key;
    $api_response = file_get_contents($api_url);
    $video_by_key_arr = json_decode($api_response, true);
    return $video_by_key_arr;
}

function get_limited_videos_by_keyword($key, $limit = 10, $video_id) {

    $key = str_replace(' ', '%20', trim($key));
    $api_url = KRONO_API_LINK . "GetVideoListByTitleFilter/" . API_KEY . "/" . API_SECRET . "/" . $limit . "/" . $video_id . "/" . $key;

    $api_response = file_get_contents($api_url);
    $video_by_key_arr = json_decode($api_response, true);
    return $video_by_key_arr;
}

function test_url($key, $limit = 10) {
    $key = str_replace(' ', '%20', trim($key));
    $api_url = KRONO_API_LINK . "GetVideoListByTitle/" . API_KEY . "/" . API_SECRET . "/" . $limit . "/" . $key;
    return $api_url;
}


//function added by pratik
add_action('wp_ajax_save_category_group', 'save_category_group_callback');

function save_category_group_callback() {
    $data = $_POST['category_groups'];
    $check = update_option('category_groups', $data);
    if ($check) {
        echo 'true';
    } else {
        echo 'false';
    }
    die;
}
