<h2> Import Videos</h2>
<br/>
<?php
//$video_id_arr = get_all_api_video_id();
//echo end($video_id_arr) . "--"; 
//echo prev($video_id_arr);


?>


<?php $loading_img_url = plugins_url('images/loading.gif',dirname(__FILE__));

$cat_arr = get_kronopress_video_cat();
$check_mapping = "";
?> 
 
<div id="krono_selected_cat">
<?php
foreach($cat_arr as $cat)
{
	$map_with = get_option('map_cat_'.$cat['CategoryId']);
         if($map_with!="")
	 {
         $check_mapping .= $map_with; 
	 echo '<input type="hidden" name="map_cat_'.$cat['CategoryId'].'" value="'.$map_with.'">';
	 }
 }
?>
</div>
<?php
$video_arr = krono_latest_videos_arr(1);
$last_video_id = $video_arr[0]['VideoId'];
?>

<?php if($check_mapping!=""){ ?>
<input type="button" value="Import" name="import_videos" class="button button-primary" id="import_videos_btn">
<br/>
<div class="loading_import_videos"><center><h3>Please wait<br/></h3><img src="<?php echo $loading_img_url; ?>"></center></div>
<div class="import_responce"><input type="hidden" name="last_video_id" id="krono_video_id" value="<?php echo $last_video_id; ?>"></div>
<?php
}
else
{
	$category_page_url = admin_url('admin.php?page=categories');
	echo '<div class="error_msg"><b>You can not import data.  <br/>
	You have to map category from <a href="'.$category_page_url.'">category page</a>.<br/>
	</b></div>';
}
?>

