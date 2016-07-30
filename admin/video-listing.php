<?php $logo = plugins_url('images/sloode.png', dirname(__FILE__)); ?>
<a href="http://www.sloode.com" target="_blank"><img src="<?php echo $logo; ?>" class="sloode_logo"></a>
<h2>Videos</h2>
<div class="wrap">
    <form action="" method="post">
        <?php $add_video_url = admin_url('admin.php?page=add_video'); ?>
        <a href="<?php echo $add_video_url; ?>"><input type="button" class="button" name="add_video" value="Add New Video"></a>
        <input type="submit" class="button" name="load_video_list" value="Load Video List">
        <input type="submit" class="button" name="refresh_video_list" value="Refresh Video List">
    </form>

    <br/>


    <?php $cat_arr = get_kronopress_video_cat(); ?>
    <form id="posts-filter" method="post" action="javascript:void(0)">
        <div class="tablenav top">
            <div class="alignleft actions">
                <select id="cat" class="postform" name="cat">
                    <option value="">-Select Category-</option>
                    <?php
                    foreach ($cat_arr as $cat) {
                        ?>
                        <option class="level-0" value="<?php echo $cat['CategoryId'] ?>"><?php echo $cat['CategoryName'] ?></option>
                    <?php }
                    ?>
                </select>
                <input id="post-query-submit cat_filter" class="button cat_filter" type="button" value="Filter" name="filter_cat">

                Start Date: <input type="text" id="date_start" class="datepicker">
                End Date: <input type="text" id="date_end" class="datepicker">
                <input id="post-query-submit date_filter" class="button date_filter" type="button" value="Filter" name="date_filter">	
            </div>
        </div>
    </form>

    <?php $loading_img_url = plugins_url('images/loading.gif', dirname(__FILE__)); ?>
    <div class="loading_img_in_video_listing"><center><h3>Loading...</h3><br/><img src="<?php echo $loading_img_url; ?>"></center><br/><br/></div>
    <div id="cat_data"> 

        <table id="filter_video" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Thumb</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                <?php
                ob_start();

                $per_page = 20;
                $video_arr = krono_latest_videos_arr(10);
                $video_id = $video_arr[0]['VideoId'];
                //echo $video_id;
                $video_id_arr = array();
                $counter = 1;
                $record_size = 50;
                ?>
            <input type="hidden" class="recent_perpage" name="recent_perpage" value="<?php echo $per_page; ?>">		
            <?php
            while ($counter <= $per_page) {
                $video_arr = get_limited_videos($record_size, $video_id);
                $array_size = count($video_arr);
//echo $array_size."hh";
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
                                <div class="video_thumb_small" id="krono_thumb_id_<?php echo $videoid ?>">
                                    <input type="hidden" value="<?php echo $videoid ?>" name="thumb_id" class="krono_thumb_id_<?php echo $videoid ?>">
                        <?php
                        $thumb_url = $video_thumb['Thumbnail'];
                        if ($thumb_url == "") {
                            $thumb_url = plugins_url('images/video_placeholder.jpg', dirname(__FILE__));
                        }
                        ?>
                                    <img src="<?php echo $thumb_url; ?>" />
                                    <?php $magnifier_icon = plugins_url('images/magni-icon.png', dirname(__FILE__)); ?>
                                    <div class="thumb_overlay">
                                        <img src="<?php echo $magnifier_icon; ?>" class="magni_ico">
                                    </div>

                                </div>
                            </td>
                            <td><?php echo $video_thumb['Title']; ?> </td>
                            <td><?php echo $video_thumb['Description']; ?></td>
                            <td><?php
                        $cat_list = "";
                        $cat_id_arr = get_categories_by_video_id($videoid);
                        foreach ($cat_id_arr as $cat_id) {
                            $cat_list.= get_category_name_by_cat_id($cat_id) . ", ";
                        }
                        $cat_list = substr($cat_list, 0, -2);
                        echo $cat_list;
                        ?>
                            </td>
                            <td><?php echo get_date_by_timestamp($video_thumb['Date']); ?></td>
                            <td>
                                <a id="delete_video_link_<?php echo $videoid; ?>" class="delete_video" href="javascript:void(0)"><input type="button" class="button" value="Delete">
                                    <input type="hidden" name="delete_video_link" class="delete_video_link_<?php echo $videoid; ?>" value="<?php echo $videoid; ?>">
                                </a>
                                <?php
                                $edit_video_url = admin_url('admin.php?page=update_video&video_id=' . $videoid);
                                ?>
                                <a href="<?php echo $edit_video_url ?>" class="edit" target="_blank"><input type="button" class="button" value="Edit"></a>
            <?php
            if ($video_thumb['IsonYoutube'] == false) {
                ?>
                                    <a id="upload_yt_link_<?php echo $videoid; ?>" class="upload_yt" href="javascript:void(0)"><input type="button" class="button" value="Upload on Youtube">
                                        <input type="hidden" name="upload_yt" class="upload_yt_link_<?php echo $videoid; ?>" value="<?php echo $videoid; ?>">
                                    </a>
                                    <?php
                                } else {
                                    echo '<div class="success_msg">Already uploaded on youtube.</div>';
                                }
                                ?> 
                                <div class="uploading_on_yt_wait">Please wait... </div>
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

                    //echo ob_get_clean();
                    ?>
            </tbody>
        </table>
            <?php
            if ($counter >= $per_page) {
                $loading_img_url = plugins_url('images/loading.gif', dirname(__FILE__));
                ?>
            <center>
                <input type="button" class="load_more_videos_button_admin" value="Load More &raquo;">
                <br/>
                <div class="loading_more_videos_admin"><img src="<?php echo $loading_img_url ?>" height="50px" width="50px"><br/>Loading....</div>
                <br/>	<br/>	</center>
            <?php } ?>
            <?php echo ob_get_clean(); ?>
    </div>


</div>



