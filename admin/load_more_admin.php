<?php
add_action( 'wp_ajax_load_more_videos_admin', 'load_more_recent_videos_admin_func' );

function load_more_recent_videos_admin_func()
{
	
	ob_start();
	
	$per_page= $_REQUEST['per_page'];
	$last_video_id = $_REQUEST['last_video_id'];
	$video_id_arr = array();
	$counter=1;
	$record_size = 50;
	while($counter<=$per_page)
	{	
		$video_arr=get_limited_videos($record_size,$last_video_id);
		
		$array_size = count($video_arr);
		foreach ($video_arr as $video_thumb)
		{		
			$video_id = $video_thumb['VideoId'];
			$videoid = $video_id;
			if(!in_array($video_id,$video_id_arr ) && $counter<=$per_page && $video_id != $last_video_id)
			{
	if($counter%2==0)
	{
		$trclass="even";
	}
	else
	{
		$trclass="odd";
	}
	
	
	
	?>
	
	<tr class="vid_row_<?php echo $videoid." ".$trclass; ?>">
	<td><?php echo $videoid; ?></td>
	<td>
			<div class="video_thumb_small" id="krono_thumb_id_<?php echo $videoid ?>">
			<input type="hidden" value="<?php echo $videoid ?>" name="thumb_id" class="krono_thumb_id_<?php echo $videoid ?>">
				<?php $thumb_url = $video_thumb['Thumbnail'];
			if($thumb_url=="")
			{
				 $thumb_url = plugins_url('images/video_placeholder.jpg',dirname(__FILE__));
			}
			?>
			<img src="<?php echo $thumb_url; ?>" />
			<?php $magnifier_icon = plugins_url('images/magni-icon.png',dirname(__FILE__)); ?>
			<div class="thumb_overlay">
			<img src="<?php echo $magnifier_icon; ?>" class="magni_ico">
		</div>
			
			</div>
			</td>
	<td><?php echo $video_thumb['Title']; ?> </td>
			<td><?php echo $video_thumb['Description']; ?></td>
			<td><?php
			$cat_list= "";
			$cat_id_arr= get_categories_by_video_id($videoid);
			foreach ($cat_id_arr as $cat_id)
			{
			$cat_list.= get_category_name_by_cat_id($cat_id).", ";
			}
			$cat_list= substr($cat_list, 0,-2);
			echo $cat_list;
			?>
			</td>
			<td><?php echo get_date_by_timestamp($video_thumb['Date']); ?></td>
			<td>
			<a id="delete_video_link_<?php echo $videoid; ?>" class="delete_video" href="javascript:void(0)"><input type="button" class="button" value="Delete">
			<input type="hidden" name="delete_video_link" class="delete_video_link_<?php echo $videoid; ?>" value="<?php echo $videoid; ?>">
		          </a>
			<?php
			$edit_video_url = admin_url('admin.php?page=update_video&video_id='.$videoid);
			?>
			<a href="<?php echo $edit_video_url ?>" class="edit" target="_blank"><input type="button" class="button" value="Edit"></a>
			<?php
			if($video_thumb['IsonYoutube']==false)
			{
			?>
			<a id="upload_yt_link_<?php echo $videoid; ?>" class="upload_yt" href="javascript:void(0)"><input type="button" class="button" value="Upload on Youtube">
			<input type="hidden" name="upload_yt" class="upload_yt_link_<?php echo $videoid; ?>" value="<?php echo $videoid; ?>">
		        </a>
			<?php
			}
			else
			{
				echo '<div class="success_msg">Already uploaded on youtube.</div>';
			}
			?> 
			  <div class="uploading_on_yt_wait">Please wait... </div>
			<input type="hidden" name="last_video_id_<?php echo $video_id; ?>" class="last_video_id" value="<?php echo $video_id; ?>">
			</td>
	</tr>
	
	<?php
	
	array_push($video_id_arr,$video_id);
				$counter++;
			}
			 $last_video_id = $video_id;
			
			if($counter>$per_page)
			{
				break;
			}
		}
		if($array_size<$record_size)
				{
					
					break;
				
				}
	
	}
	
	$response = ob_get_clean();
	if($response!="")
	{
		echo $response;
	}
	else
	{
		echo 0;
	}
	
	die;
}

//--------------------------------

add_action( 'wp_ajax_load_more_by_cat_admin', 'load_more_by_cat_admin_func' );

function load_more_by_cat_admin_func()
{
	
	ob_start();
	
	$per_page= $_REQUEST['per_page'];
	$last_video_id = $_REQUEST['last_video_id'];
	$temp_count = $_REQUEST['temp_count'];
	$cat_id = $_REQUEST['cat_id'];
	$video_id_arr = array();
	$counter=1;
	$record_size = 50;
	$end_all_records= false;
	while($counter<=$per_page)
	{	
			$video_arr=get_limited_videos_by_cat($cat_id,$record_size,$last_video_id);
		
		$array_size = count($video_arr);
		foreach ($video_arr as $video_thumb)
		{		
			$video_id = $video_thumb['VideoId'];
			$videoid = $video_id;
			if(!in_array($video_id,$video_id_arr ) && $counter<=$per_page && $video_id != $last_video_id)
			{
	if($counter%2==0)
	{
		$trclass="even";
	}
	else
	{
		$trclass="odd";
	}
	
	
	
	?>
	
	<tr class="vid_row_<?php echo $videoid." ".$trclass; ?>">
	<td><?php echo $videoid; ?></td>
	<td>
			<div class="video_thumb_small" id="krono_thumb_id_<?php echo $videoid ?>">
			<input type="hidden" value="<?php echo $videoid ?>" name="thumb_id" class="krono_thumb_id_<?php echo $videoid ?>">
				<?php $thumb_url = $video_thumb['Thumbnail'];
			if($thumb_url=="")
			{
				 $thumb_url = plugins_url('images/video_placeholder.jpg',dirname(__FILE__));
			}
			?>
			<img src="<?php echo $thumb_url; ?>" />
			<?php $magnifier_icon = plugins_url('images/magni-icon.png',dirname(__FILE__)); ?>
			<div class="thumb_overlay">
			<img src="<?php echo $magnifier_icon; ?>" class="magni_ico">
		</div>
			
			</div>
			</td>
	<td><?php echo $video_thumb['Title']; ?> </td>
			<td><?php echo $video_thumb['Description']; ?></td>
			<td><?php
			$cat_list= "";
			$cat_id_arr= get_categories_by_video_id($videoid);
			foreach ($cat_id_arr as $cat_id)
			{
			$cat_list.= get_category_name_by_cat_id($cat_id).", ";
			}
			$cat_list= substr($cat_list, 0,-2);
			echo $cat_list;
			?>
			</td>
			<td><?php echo get_date_by_timestamp($video_thumb['Date']); ?></td>
			<td>
			<a id="delete_video_link_<?php echo $videoid; ?>" class="delete_video" href="javascript:void(0)"><input type="button" class="button" value="Delete">
			<input type="hidden" name="delete_video_link" class="delete_video_link_<?php echo $videoid; ?>" value="<?php echo $videoid; ?>">
		          </a>
			<?php
			$edit_video_url = admin_url('admin.php?page=update_video&video_id='.$videoid);
			?>
			<a href="<?php echo $edit_video_url ?>" class="edit" target="_blank"><input type="button" class="button" value="Edit"></a>
			<?php
			//if($video_thumb['IsonYoutube']=="false")
			//{
			?>
			<a id="upload_yt_link_<?php echo $videoid; ?>" class="upload_yt" href="javascript:void(0)"><input type="button" class="button" value="Upload on Youtube">
			<input type="hidden" name="upload_yt" class="upload_yt_link_<?php echo $videoid; ?>" value="<?php echo $videoid; ?>">
		        </a>
			<?php
			//}
			//else
			//{
			//	echo '<div class="success_msg">Already uploaded on youtube.</div>';
			//}
			?> 
			  <div class="uploading_on_yt_wait">Please wait... </div>
			<input type="hidden" name="last_video_id_<?php echo $video_id; ?>" class="last_video_id" value="<?php echo $video_id; ?>">
			</td>
	</tr>
	
	<?php
	
	array_push($video_id_arr,$video_id);
				$counter++;
			}
			 $last_video_id = $video_id;
			
			if($counter>$per_page)
			{
				break;
			}
		}
		if($array_size<$record_size)
				{
					
					break;
				
				}
	
	}
	
	$response = ob_get_clean();
	if($response!="")
	{
		echo $response;
	}
	else
	{
		echo 0;
	}
	
	die;
}




add_action( 'wp_ajax_load_more_videos_editor', 'load_more_recent_videos_editor_func' );

function load_more_recent_videos_editor_func()
{
	
	ob_start();
	
	$per_page= $_REQUEST['per_page'];
	$last_video_id = $_REQUEST['last_video_id'];
	$video_id_arr = array();
	$counter=1;
	$record_size = 50;
	while($counter<=$per_page)
	{	
		$video_arr=get_limited_videos($record_size,$last_video_id);
		
		$array_size = count($video_arr);
		foreach ($video_arr as $video_thumb)
		{		
			$video_id = $video_thumb['VideoId'];
			$videoid = $video_id;
			if(!in_array($video_id,$video_id_arr ) && $counter<=$per_page && $video_id != $last_video_id)
			{
	if($counter%2==0)
	{
		$trclass="odd";
	}
	else
	{
		$trclass="even";
	}
	
	
	
	?>
	
	<tr class="vid_row_<?php echo $videoid." ".$trclass; ?>">
	<td><?php echo $videoid; ?></td>
	<td>
	<div class="video_thumb_in_editor" id="krono_thumb_id_<?php echo $videoid ?>">
	<input type="hidden" value="<?php echo $videoid ?>" name="thumb_id" class="krono_thumb_id_<?php echo $videoid ?>">
		<?php $thumb_url = $video_thumb['Thumbnail'];
	if($thumb_url=="")
	{
		 $thumb_url = plugins_url('images/video_placeholder.jpg',__FILE__);
	}
	?>
	<img src="<?php echo $thumb_url; ?>" />
	<?php $magnifier_icon = plugins_url('images/magni-icon.png',__FILE__); ?>
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
	
	array_push($video_id_arr,$video_id);
				$counter++;
			}
			 $last_video_id = $video_id;
			
			if($counter>$per_page)
			{
				break;
			}
		}
		if($array_size<$record_size)
				{
					
					break;
				
				}
	
	}
	
	$response = ob_get_clean();
	if($response!="")
	{
		echo $response;
	}
	else
	{
		echo 0;
	}
	
	die;
}




?>