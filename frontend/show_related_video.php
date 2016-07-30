<?php
function show_related_videos_func( $atts ) {
    ob_start();
    $a = shortcode_atts( array(
        'count' => 0,  'per_page'=>50, 'cat_id' => "",
	'special_cat' =>"", 'title' =>"Related Videos", 'exclude_cat_id'=>"",
	'exclude_special_cat' =>"", 'height' =>150, 'width'=>150, 'show_loadmore'=>'yes',
	'show_title'=>'yes', 'widget' => 'no', 	'play_button' => 'no',
	 'template' => 'style1',  'show_hits' => 'yes',
	 'show_cat' => 'yes',  'show_date'=> 'no',
         ), $atts );
    	$numberposts = $a['count'];
	$per_page = $a['per_page'];
	$height = $a['height'];
	$width = $a['width'];
	$show_loadmore = $a['show_loadmore'];
	$show_title = $a['show_title'];
	$widget = $a['widget'];
	$play_button = $a['play_button'];
	$template = $a['template'];
	$show_cat = $a['show_cat'];
	$special_cat = $a['special_cat'];
	$show_hits = $a['show_hits'];
	$show_date = $a['show_date'];
	$list_title = $a['title'];
	
	if($numberposts<=$per_page && $numberposts!=0)
	{
	$per_page = $numberposts;	
	}
	
	$video_id = $_REQUEST['video_id'];
	$current_page_video_id = $_REQUEST['video_id'];
	
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
	
	
	
	
	$cat_id_arr = $normal_cat_arr;
	$cat_id_special_arr = $special_cat_arr;
	
	$rel_cat_id=implode(",", $cat_id_arr);
	$rel_special_cat =implode(",", $cat_id_special_arr);
	
	if(empty($special_cat_arr))
	{
	$video_arr = get_latest_videos_by_multi_cat($cat_id_arr ,1);
	}
	else
	{
	    
	    $video_arr = get_latest_videos_by_multi_cat_sp($cat_id_arr ,$cat_id_special_arr,1);
	    
	}
	$video_id = $video_arr[0]['VideoId'];
	
	$video_id_arr = array($video_id);
	$counter=0;
	$record_size = 50;
	$end_all_records = false;
	?>
	
	<div class='krono_video_listing_wrap krono_related_videos_wrap'>
		
	<?php
	echo '<div class="kp_title related_videos_title">'.$list_title.'</div>';
	 ?>
	<input type="hidden" class="rel_numberposts" name="rel_numberposts" value="<?php echo $numberposts; ?>">
	<input type="hidden" class="rel_perpage" name="rel_perpage" value="<?php echo $per_page; ?>">
	<input type="hidden" class="rel_cat_id" name="rel_cat_id" value="<?php echo $rel_cat_id; ?>">
	<input type="hidden" class="rel_special_cat" name="rel_special_cat" value="<?php echo $rel_special_cat; ?>">
	<input type="hidden" class="thumb_height_rel" name="thumb_height_rel" value="<?php echo $height; ?>">
	<input type="hidden" class="thumb_width_rel" name="thumb_width_rel" value="<?php echo $width; ?>">
	<input type="hidden" class="show_title_rel" name="show_title_rel" value="<?php echo $show_title; ?>">
	<input type="hidden" class="template_rel" name="template_rel" value="<?php echo $template; ?>">
	<input type="hidden" class="show_cat_rel" name="show_cat_rel" value="<?php echo $show_cat; ?>">
	<input type="hidden" class="show_hits_rel" name="show_hits_rel" value="<?php echo $show_hits; ?>">
	<input type="hidden" class="play_button_rel" name="play_button_rel" value="<?php echo $play_button; ?>">
	<input type="hidden" class="show_date_rel" name="show_date_rel" value="<?php echo $show_date; ?>">
	<?php
	while($counter<$per_page)
	{
	   if($special_cat=="")
	   {
		$video_arr=get_limited_videos_by_multi_cat($cat_id_arr ,$record_size,$video_id);
	   }
	   else
	   {
	     $video_arr =  get_limited_videos_by_multi_cat_sp($cat_id_arr,$cat_id_special_arr,$record_size,$video_id);
	   }
		$array_size = count($video_arr);
		foreach ($video_arr as $video_thumb)
		{
			$video_id = $video_thumb['VideoId'];
			$videoid= $video_id;
			if(!in_array($video_id,$video_id_arr ) && $counter<$per_page)
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
				    <div class="video_cat"><?php
				  
				     $cat_list= "";
			$video_cat_id_arr= get_categories_by_video_id($video_id);
			foreach ($video_cat_id_arr as $video_cat_id)
			{			   
			    if(get_category_type_by_cat_id($video_cat_id)=="Normal")
			    {
				$cat_list.= get_category_name_by_cat_id($video_cat_id).", ";
			    }
			}
			$cat_list= substr($cat_list, 0,-2);
			echo $cat_list;
			 ?></div>
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
				//$video_detail = get_kronopress_video_by_videoid($video_id);
				//$hits = $video_detail['hits'];
				$hits = $video_thumb['hits'];
				if($hits==""){$hits=0;}
				echo $hits." Visualizzazioni";
				?></div>
				<?php } ?>
				<?php $magnifier_icon = plugins_url('images/magni-icon.png',dirname(__FILE__)); ?>
				<?php if($play_button=="no") { ?>
				<img src="<?php echo $magnifier_icon; ?>" class="magni_ico">
				<?php } ?>
				</div>
			 </a>
			 <input type="hidden" name="last_video_id_<?php echo $video_id; ?>" class="last_video_id" value="<?php echo $video_id; ?>">
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
			$video_cat_id_arr= get_categories_by_video_id($video_id);
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
				//$video_detail = get_kronopress_video_by_videoid($video_id);
			//$hits = $video_detail['hits'];
			$hits = $video_thumb['hits'];
			if($hits==""){$hits=0;}
			echo $hits." Visualizzazioni";
				?></div>
				<?php } ?>
				</div>
			
			 <input type="hidden" name="last_video_id_<?php echo $video_id; ?>" class="last_video_id" value="<?php echo $video_id; ?>">
		    
		    
		    
		</div>
	<?php } ?>
		<?php
			array_push($video_id_arr,$video_id);
				$counter++;
			}
			
			if($counter>=$per_page ) { break; }
			
			
		}
		
		if($array_size<$record_size) { break; }
				
		
	}
	?>	
	
	<?php if($widget=="no")
	{
	?>
	</div>  <!---krono_video_listing_wrap end--->
	<div class='clear clearfix'></div>
	
<?php } ?>	
	
	<?php
	if(($counter<$numberposts || $numberposts==0) && $show_loadmore=="yes" && $widget=="no" ){
		$loading_img_url = plugins_url('images/loading.gif',dirname(__FILE__));
		
		?>
		<center>
	<input type="button" class="related_video_load_more_button" value="Load More &raquo;">
	<br/>	
	<div class="loading_rel_videos"><img src="<?php echo $loading_img_url ?>" height="50px" width="50px"><br/>Loading...</div>
	<br/>	<br/>	</center>
	<?php } ?>
	
<?php	
	return ob_get_clean();	
}
add_shortcode( 'show_related_videos', 'show_related_videos_func' );


function load_more_rel_videos_func()
{
	$per_page= $_REQUEST['per_page'];
	$last_video_id = $_REQUEST['last_video_id'];
	$temp_count = $_REQUEST['temp_count'];
	$cat_id = $_REQUEST['cat_id'];
	$special_cat = $_REQUEST['special_cat'];
	$numberposts = $_REQUEST['numberposts'];
	$height = $_REQUEST['height'];
	$width = $_REQUEST['width'];
	$show_title = $_REQUEST['show_title'];
	$template = $_REQUEST['template'];
	$show_cat = $_REQUEST['show_cat'];
	$show_hits = $_REQUEST['show_hits'];
	$play_button = $_REQUEST['play_button'];
	$show_date = $_REQUEST['show_date'];
	ob_start();
	$video_id_arr = array();
	$counter=1;
	$record_size = 50;
	$end_all_records= false;
	$cat_id_special_arr = explode(",", $special_cat);
	$cat_id_arr = explode(",",$cat_id);
		while($counter<=$per_page)
	{

	   if($special_cat=="")
	   {
		$video_arr=get_limited_videos_by_multi_cat($cat_id_arr ,$record_size,$last_video_id);
	   }
	   else
	   {
	     $video_arr =  get_limited_videos_by_multi_cat_sp($cat_id_arr,$cat_id_special_arr,$record_size,$last_video_id);
	   }
		
		$array_size = count($video_arr);
		
		foreach ($video_arr as $video_thumb)
		{		
			$video_id = $video_thumb['VideoId'];
			if(!in_array($video_id,$video_id_arr ) && $counter<=$per_page && $video_id != $last_video_id)
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
			$video_cat_id_arr= get_categories_by_video_id($video_id);
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
				//$video_detail = get_kronopress_video_by_videoid($video_id);
			//$hits = $video_detail['hits'];
			$hits = $video_thumb['hits'];
			if($hits==""){$hits=0;}
			echo $hits." Visualizzazioni";
				?></div>
				<?php } ?>
				<?php $magnifier_icon = plugins_url('images/magni-icon.png',dirname(__FILE__)); ?>
				<?php if($play_button=="no") { ?>
				<img src="<?php echo $magnifier_icon; ?>" class="magni_ico">
				<?php } ?>
				</div>
			 </a>
			 <input type="hidden" name="last_video_id_<?php echo $video_id; ?>" class="last_video_id" value="<?php echo $video_id; ?>">
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
			$video_cat_id_arr= get_categories_by_video_id($video_id);
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
					<div class="thumb_ttl"><?php echo $video_thumb['Title'] ?></div>
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
				//$video_detail = get_kronopress_video_by_videoid($video_id);
			//$hits = $video_detail['hits'];
			$hits = $video_thumb['hits'];
			if($hits==""){$hits=0;}
			echo $hits." Visualizzazioni";
				?></div>
				<?php } ?>
				</div>
			
			 <input type="hidden" name="last_video_id_<?php echo $video_id; ?>" class="last_video_id" value="<?php echo $video_id; ?>">
		   
		</div>
	<?php } ?>
		<?php
			array_push($video_id_arr,$video_id);
				$counter++;
				$temp_count++;
			}
			 $last_video_id = $video_id;
			
			if(($counter>$per_page || $temp_count>=$numberposts) && $numberposts!=0 ) { break; }
							
		}
		if($array_size<$record_size || $temp_count>=$numberposts) { break; }
				
		
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
add_action( 'wp_ajax_load_more_rel_videos', 'load_more_rel_videos_func' );
add_action( 'wp_ajax_nopriv_load_more_rel_videos', 'load_more_rel_videos_func' );
?>
