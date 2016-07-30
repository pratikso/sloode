<?php
add_action( 'wp_ajax_get_kronopress_video_by_catid', 'get_kronopress_video_by_catid' );

function get_kronopress_video_by_catid()
{
	$catid= $_REQUEST['post_id'];
	$cat_id = $catid;
	$video_arr = get_latest_videos_by_cat($cat_id,1);
	$video_id = $video_arr[0]['VideoId'];
	$video_id_arr = array();
	$counter=0;
	$record_size = 50;
	$per_page= 20;
	$end_all_records = false;
	ob_start();
	?>
	
	<br/>
	<center><strong>Filter By Category: </strong></center>
	
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
        <input type="hidden" class="cat_perpage" name="cat_perpage" value="<?php echo $per_page; ?>">
	<input type="hidden" class="cat_id" name="cat_id" value="<?php echo $cat_id; ?>">
	<?php while($counter<$per_page)
	{
		
		
		$video_arr=get_limited_videos_by_cat($cat_id,$record_size,$video_id);
		$array_size = count($video_arr);

		foreach ($video_arr as $video_thumb)
		{
			$video_id = $video_thumb['VideoId'];
			$videoid = $video_id;
			if(!in_array($video_id,$video_id_arr ) && $counter<$per_page)
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
	<div class="video_thumb_small" id="krono_thumb_id_<?php echo $videoid ?>">
		<input type="hidden" value="<?php echo $videoid ?>" name="thumb_id" class="krono_thumb_id_<?php echo $videoid ?>">
		<?php $thumb_url = $video_thumb['Thumbnail'];
	//if($thumb_url=="" || !@getimagesize($thumb_url))
	if($thumb_url=="")
	{
		 $thumb_url = plugins_url('images/video_placeholder.jpg',dirname(__FILE__));
	}
	?>
	<img  src="<?php echo $thumb_url; ?>" />
	<?php $magnifier_icon = plugins_url('images/magni-icon.png',dirname(__FILE__)); ?>
			<div class="thumb_overlay">
			<img src="<?php echo $magnifier_icon; ?>" class="magni_ico">
		</div>
	
	</div>
	</td>
	<td><?php echo $video_thumb['Title']; ?> </td>
	<td><?php echo $video_thumb['Description']; ?></td>
	<td><?php //echo $videodetail['CategoryName']; ?>
	<?php
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
	
	<td><a id="delete_video_link_<?php echo $videoid; ?>" class="delete_video" href="javascript:void(0)"><input type="button" class="button" value="Delete">
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
			 <input type="hidden" name="last_video_id_<?php echo $video_id; ?>" class="last_video_id" value="<?php echo $video_id; ?>">
			</td>
	<div class="uploading_on_yt_wait"> </div>
	</tr>
	
<?php
array_push($video_id_arr,$video_id);
	    $counter++;
	}
	if($counter>=$per_page )
			{
				break;
			}
			
		}
		
		if($array_size<$record_size)
				{
					
					break;
				
				}
		
	}
	
	?>
        </tbody>
		</table>
		
		
		
	<?php
	
	if($counter>=$per_page)
	{
	$loading_img_url = plugins_url('images/loading.gif',dirname(__FILE__)); ?>
	 <center>
	<input type="button" class="load_more_by_cat_button_admin" value="Load More &raquo;">
		<br/>
	<div class="loading_more_videos_admin"><img src="<?php echo $loading_img_url ?>" height="50px" width="50px"><br/>Loading....</div>
	<br/>	<br/>	</center>
	 <?php } ?>
	<?php
	echo ob_get_clean();
	die;
       
}

 
add_action( 'wp_ajax_get_kronopress_video_by_date', 'get_kronopress_video_by_date' );
function get_kronopress_video_by_date()
{

	$startdate = $_REQUEST['startdate'];
	$enddate =   $_REQUEST['enddate'];
	$video_by_date = get_kronopress_video_by_date_arr($startdate,$enddate);
	?>
	<br/>
	<center><strong>Filter By Date</strong></center>
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
	$video_id_arr = array();
	$counter=1;
	foreach ($video_by_date as $video_thumb){
		$videoid =$video_thumb['VideoId'];
		$video_id = $videoid;
		$videodetail= get_kronopress_video_by_videoid($videoid);
		 if($counter%2==0)
	{
		$trclass="odd";
	}
	else
	{
		$trclass="even";
	}
		
		$videodetail= get_kronopress_video_by_videoid($videoid);
		if(!in_array($videoid,$video_id_arr ))
		{
		?>
		
		
	<tr class="vid_row_<?php echo $videoid." ".$trclass; ?>">
		<td><?php echo $videoid; ?></td>
		<td>
	<div class="video_thumb_small" id="krono_thumb_id_<?php echo $videoid ?>">
		<input type="hidden" value="<?php echo $videoid ?>" name="thumb_id" class="krono_thumb_id_<?php echo $videoid ?>">
		<?php $thumb_url = $videodetail['Thumbnail'];
	
	if($thumb_url=="")
	{
		 $thumb_url = plugins_url('images/video_placeholder.jpg',dirname(__FILE__));
	}
	?>
	<img  src="<?php echo $thumb_url; ?>" />
	<?php $magnifier_icon = plugins_url('images/magni-icon.png',dirname(__FILE__)); ?>
			<div class="thumb_overlay">
			<img src="<?php echo $magnifier_icon; ?>" class="magni_ico">
		</div>
	
	</div>
	</td>
	<td><?php echo $video_thumb['Title']; ?> </td>
	<td><?php echo $video_thumb['Description']; ?></td>
	<td><?php //echo $videodetail['CategoryName']; ?>
	<?php
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
	
	<td><a id="delete_video_link_<?php echo $videoid; ?>" class="delete_video" href="javascript:void(0)"><input type="button" class="button" value="Delete">
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
			 <input type="hidden" name="last_video_id_<?php echo $video_id; ?>" class="last_video_id" value="<?php echo $video_id; ?>">
			</td>
	<div class="uploading_on_yt_wait"> </div>
	</tr>
	<?php
	array_push($video_id_arr,$videoid);
		$counter++;
		}
	}
	?>
        </tbody>
		</table>
	
        <?php die;
     
}
?>