<?php
function show_related_videos_func($atts) {
	ob_start();
	$a = shortcode_atts( array(
        'count' => 10,
	'title' =>"Related Videos",
	'height' =>150,
	'width'=>150,
	'show_loadmore'=>'yes',
	'show_title'=>'yes',
	'play_button' => 'no',
	 'template' => 'style1',
	 'show_hits' => 'yes',
	 'show_cat' => 'yes',
	 'show_date'=> 'no'
	), $atts );
	$numberposts= $a['count'];
	$list_title = $a['title'];
	$height = $a['height'];
	$width = $a['width'];
	$show_title = $a['show_title'];
	$play_button = $a['play_button'];
	$template = $a['template'];
	$show_cat = $a['show_cat'];
	$show_hits = $a['show_hits'];
	$show_date = $a['show_date'];
	
	//if($numberposts=="")
	//{
	//	$numberposts = 10;
	//}
	$video_id = $_REQUEST['video_id'];
	$current_page_video_id = $_REQUEST['video_id'];
	
	//$video_detail_arr = get_kronopress_video_by_videoid($video_id);
	//$cat_id = $video_detail_arr['CategoryId'];
	$record_size = $numberposts *2;
	$video_cat_id_arr= get_categories_by_video_id($video_id);
	$normal_cat_arr= array();
	$special_cat_arr = array();
	
	foreach ($video_cat_id_arr as $video_cat_id)
	{
		$cat_type = get_category_type_by_cat_id($video_cat_id);
		 if($cat_type=="Normal")
		 {
			array_push($normal_cat_arr,$video_cat_id);
		 }
		if($cat_type=="Special")
		 {
			array_push($special_cat_arr,$video_cat_id);
		 }
		
	}
	if(empty($special_cat_arr))
	{
		$video_arr = get_latest_videos_by_multi_cat($normal_cat_arr ,$record_size);
	}
	else
	{
	  $video_arr = get_latest_videos_by_multi_cat_sp($normal_cat_arr ,$special_cat_arr,$record_size);	
	}
	
	
	//$video_arr = videos_by_cat_arr($cat_id,$numberposts);
	
	
	
	
	$count=1;
	$video_id_arr = array($video_id);
	//$video_arr_slice = array_slice($video_arr, 0, $count); 
	echo "<div class='krono_video_listing_wrap'>";
	echo '<div class="kp_title related_videos_title">'.$list_title.'</div>';
	echo '<div class="clear"></div>';
	foreach ($video_arr as $video_thumb)
	{
		$video_id = $video_thumb['VideoId'];
		
		if(!in_array($video_id,$video_id_arr )&&$count<=$numberposts)
		{
		
?>

	<?php if($template=="style1")
	{ ?>
			<div class="video_thumb count_thumb" style="height: <?php echo $height; ?>px; width: <?php echo $width; ?>px;">
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
				<?php if($play_button!="no") { ?>
				<div class="thumb_play_button">
				    <?php $thumb_play_icon = plugins_url('images/play_button.png',dirname(__FILE__)); ?>
			  <a href="<?php echo $video_page_link; ?>">
			   <img src="<?php echo $thumb_play_icon; ?>" class="play_ico">
			    </a>
			</div>
				<?php } ?>
				
				
				<a href="<?php echo $video_page_link; ?>">
				<div class="thumb_overlay">
					   <?php if($show_cat=="yes" ||$show_cat=="YES")
				    {
				    ?>
				    <div class="video_cat">
				    
				   <?php
				  
				     $cat_list= "";
			$video_cat_id_arr= get_categories_by_video_id($current_page_video_id);
			foreach ($video_cat_id_arr as $video_cat_id)
			{
			   
			    if(get_category_type_by_cat_id($video_cat_id)=="Normal")
			    {
				$cat_list.= get_category_name_by_cat_id($video_cat_id).", ";
			    }
			}
			$cat_list= substr($cat_list, 0,-2);
			echo $cat_list;
			
				    
				    
				    ?>
				    
				    </div>
				    <?php } ?>
					
					<?php if($show_title=="yes" || $show_title=="YES") {?>
					<div class="video_title"><?php echo $video_thumb['Title'] ?></div>
					<?php } ?>
					<?php if($show_date=="yes" || $show_date=="YES") {?>
					<div class="video_date"><?php 					
					$timestamp = $video_thumb['Date'];
				$timestamp = get_date_by_timestamp($timestamp);
				echo $timestamp;
					?></div>
					<?php } ?>
					
					<?php if($show_hits=="yes" ||$show_hits=="YES")
				    {
				    ?>
				<div class="video_view"><?php
				$video_detail = get_kronopress_video_by_videoid($video_id);
			$hits = $video_detail['hits'];
			echo $hits." Visualizzazioni";
				?></div>
				<?php } ?>
				<?php $magnifier_icon = plugins_url('images/magni-icon.png',dirname(__FILE__)); ?>
				<?php if($play_button=="no") { ?>
				<img src="<?php echo $magnifier_icon; ?>" class="magni_ico">
				<?php } ?>
				</div>
			 </a>
			</div>
	<?php } ?>
	
	<?php if($template=="style2")
	{ ?>
		<div class="video_thumb2 count_thumb" style="height: <?php echo $height-10; ?>px; width: <?php echo $width-10; ?>px;">
		    <?php
				$thumb_url = $video_thumb['Thumbnail'];
				if($thumb_url=="")
				{
					 $thumb_url = plugins_url('images/video_placeholder.jpg',dirname(__FILE__));
				}
				?>
				
				<?php $player_page_id = get_option('video_player_page_id');
				
				$video_page_link = add_query_arg( array('video_id' => $video_id ),  get_page_link($player_page_id) );
				
				?>
				<div class="thumb_img2">
				<a href="<?php echo $video_page_link; ?>">
				<img src="<?php echo $thumb_url; ?>">
				<?php if($play_button!="no") { ?>
				<div class="thumb_play_button">
				    <?php $thumb_play_icon = plugins_url('images/play_button.png',dirname(__FILE__)); ?>
				<img src="<?php echo $thumb_play_icon; ?>" class="play_ico">
				</div>
				<?php } ?>
			    </a>
				</div>
								
				<div class="thumb_detail">
				    <?php if($show_cat=="yes" ||$show_cat=="YES")
				    {
				    ?>
				    <div class="thumb_cat">
				    
				  <?php
				  
				     $cat_list= "";
			$video_cat_id_arr= get_categories_by_video_id($current_page_video_id);
			foreach ($video_cat_id_arr as $video_cat_id)
			{
			   
			    if(get_category_type_by_cat_id($video_cat_id)=="Normal")
			    {
				$cat_list.= get_category_name_by_cat_id($video_cat_id).", ";
			    }
			}
			$cat_list= substr($cat_list, 0,-2);
			echo $cat_list;
			
				    
				    
				    ?>
				    
				    </div>
				    <?php } ?>
					<?php if($show_title=="yes" || $show_title=="YES") {?>
					<div class="thumb_ttl"><?php echo $video_thumb['Title'];
						?></div>
					<?php } ?>
					
					<?php if($show_date=="yes" || $show_date=="YES") {?>
					<div class="thumb_date"><?php 					
					$timestamp = $video_thumb['Date'];
				$timestamp = get_date_by_timestamp($timestamp);
				echo $timestamp;
					?></div>
					<?php } ?>
				<?php if($show_hits=="yes" ||$show_hits=="YES")
				    {
				    ?>
				<div class="thubm_view"><?php
				$video_detail = get_kronopress_video_by_videoid($video_id);
			$hits = $video_detail['hits'];
			echo $hits." Visualizzazioni";
				?></div>
				<?php } ?>
				</div>
					    
		    
		</div>
	<?php } ?>
	
	
	
	
<?php
	array_push($video_id_arr,$video_id);
				$count++;
		}
		if($count>$numberposts)
				{
					break;
				}
	}
	
	
	
	
	
	echo "<div class='clear clearfix'></div>";
	
	
	
	
	
	echo "</div>"; //krono_video_listing_wrap end
	echo "<div class='clear clearfix'></div>";
return ob_get_clean();	
}
add_shortcode( 'show_related_videos', 'show_related_videos_func' );
?>