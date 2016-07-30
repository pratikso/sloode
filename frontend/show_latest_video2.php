<?php
function show_latest_video_func2( $atts ) {
	
	ob_start();
    $a = shortcode_atts( array(
        'count' => 0,
	'per_page'=>50
         ), $atts );
    	$numberposts = $a['count'];
	$per_page = $a['per_page'];
	if($numberposts<=$per_page && $numberposts!=0)
	{
	$per_page = $numberposts;	
	}
	$video_arr = krono_latest_videos_arr(10);
	$video_id = $video_arr[0]['VideoId'];
	$video_id_arr = array();
	$counter=0;
	$record_size = 75;
	?>
	<div class='krono_video_listing_wrap krono_recent_videos_wrap'>
	<input type="hidden" class="recent_numberposts" name="recent_numberposts" value="<?php echo $numberposts; ?>">
	<input type="hidden" class="recent_perpage" name="recent_perpage" value="<?php echo $per_page; ?>">
	<?php
	
	while($counter<$per_page)
	{
		
		
		$video_arr=get_limited_videos($record_size,$video_id);
		$array_size = count($video_arr);
		
		foreach ($video_arr as $video_thumb)
		{
			
			$video_id = $video_thumb['VideoId'];
			
			if(!in_array($video_id,$video_id_arr ) && $counter<=$per_page)
			{
				
	?>
	
	<div class="video_thumb">
		<?php
		
		$thumb_url = $video_thumb['Thumbnail'];
		if($thumb_url=="")
		{
			 $thumb_url = plugins_url('images/video_placeholder.jpg',dirname(__FILE__));
		}
		?>
		<img src="<?php echo $thumb_url; ?>">
		<?php $player_page_id = get_option('video_player_page_id');
		
		$video_page_link = add_query_arg( array('video_id' => $video_id ),  get_page_link($player_page_id) );
		
		?>
		<a href="<?php echo $video_page_link; ?>">
	     	<div class="thumb_overlay"><div class="video_title"><?php echo $video_thumb['Title'] ?></div>
		<?php $magnifier_icon = plugins_url('images/magni-icon.png',dirname(__FILE__)); ?>
		<img src="<?php echo $magnifier_icon; ?>" class="magni_ico">
		</div>
	 </a>
	 <input type="hidden" name="last_video_id_<?php echo $video_id; ?>" class="last_video_id" value="<?php echo $video_id; ?>">
	</div>
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
	
	</div>  <!---krono_video_listing_wrap end--->
	
	<div class='clear clearfix'></div>
	<?php if($counter<$numberposts || $numberposts==0){
		$loading_img_url = plugins_url('images/loading.gif',dirname(__FILE__));
		
		?>
	 <center>
	<input type="button" class="recent_video_load_more_button" value="Load More &raquo;">
		<br/>
	<div class="loading_recent_videos"><img src="<?php echo $loading_img_url ?>" height="50px" width="50px"><br/>Loading....</div>
	<br/>	<br/>	</center>
	<?php } ?>
	
<?php	
	return ob_get_clean();	
}
add_shortcode( 'show_latest_video2', 'show_latest_video_func2' );


function load_more_recent_videos_func()
{
	
	$per_page= $_REQUEST['per_page'];
	$last_video_id = $_REQUEST['last_video_id'];
	$temp_count = $_REQUEST['temp_count'];
	$numberposts = $_REQUEST['numberposts'];
	ob_start();
	$video_id_arr = array();
	$counter=1;
	$record_size = 75;
	while($counter<=$per_page)
	{	
		$video_arr=get_limited_videos($record_size,$last_video_id);
		$array_size = count($video_arr);
		foreach ($video_arr as $video_thumb)
		{		
			$video_id = $video_thumb['VideoId'];
		
			if(!in_array($video_id,$video_id_arr ) && $counter<=$per_page && $video_id != $last_video_id)
			{
	?>
	
	<div class="video_thumb">
		<?php
		
		$thumb_url = $video_thumb['Thumbnail'];
		if($thumb_url=="")
		{
			 $thumb_url = plugins_url('images/video_placeholder.jpg',dirname(__FILE__));
		}
		?>
		<img src="<?php echo $thumb_url; ?>">
		<?php $player_page_id = get_option('video_player_page_id');
		
		$video_page_link = add_query_arg( array('video_id' => $video_id ),  get_page_link($player_page_id) );
		
		?>
		<a href="<?php echo $video_page_link; ?>">
	      	<div class="thumb_overlay"><div class="video_title"><?php echo $video_thumb['Title'] ?></div>
		<?php $magnifier_icon = plugins_url('images/magni-icon.png',dirname(__FILE__)); ?>
		<img src="<?php echo $magnifier_icon; ?>" class="magni_ico">
		</div>
	 </a>
	  <input type="hidden" name="last_video_id_<?php echo $video_id; ?>" class="last_video_id" value="<?php echo $video_id; ?>">
	 
	</div>
		<?php
			array_push($video_id_arr,$video_id);
				$counter++;
				$temp_count++;
			}
			 $last_video_id = $video_id;
			
			if(($counter>$per_page || $temp_count>=$numberposts) && $numberposts!=0 )
			{
				break;
			}
		
		
		
		
		}
		if($array_size<$record_size || $temp_count>=$numberposts)
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
add_action( 'wp_ajax_load_more_recent_videos', 'load_more_recent_videos_func' );
add_action( 'wp_ajax_nopriv_load_more_recent_videos', 'load_more_recent_videos_func' );
?>
