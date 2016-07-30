<?php

function show_video_player_by_cat_func( $atts ) {
      ob_start();
      $a = shortcode_atts( array(
        'count' => 10,
	'cat_id' => 1,
	'height' =>200,
	'width'=>200,
	'show_title'=>'no',
	 ), $atts );
    
    $post_per_page = $a['count'];
    $height = $a['height'];
    $width = $a['width'];
    $cat_ids = $a['cat_id'];
    $show_title = $a['show_title'];
    $counter=0;
    $record_size = 50;
   
    
    $video_id_arr = array();
    $cat_id_arr = explode(",",$cat_ids);
    $cat_id_count = count($cat_id_arr);
    $per_cat = (int)($post_per_page/$cat_id_count);
    $reminder = $post_per_page%$cat_id_count;
    $per_cat_last= $per_cat+$reminder;
    ?>
    <div class='krono_video_listing_wrap krono_player_by_cat_wrap'>
	<?php
	
$cat_counter = 1;
 foreach($cat_id_arr as $cat_id)
 {
     $video_arr = get_latest_videos_by_cat($cat_id,1);
    $video_id = $video_arr[0]['VideoId'];
    if($cat_counter==$cat_id_count)
    {
	$per_page = $per_cat_last;
    }
    else
    {
	$per_page = $per_cat;
    }
  
  	while($counter < $per_page)
	{
	    
	    $video_arr=get_limited_videos_by_cat($cat_id,$record_size,$video_id);
	    
	    $array_size = count($video_arr);
		foreach ($video_arr as $video_thumb)
		{
			
			$video_id = $video_thumb['VideoId'];
			
			if(!in_array($video_id,$video_id_arr ) && $counter<$per_page)
			{
			?>
			<div class="video_player_box" style="height: auto; width: <?php echo $width; ?>px;">
			<div class="video_player_box_inner" style="height: <?php echo $height; ?>px; width: 100%">
			  
			<?php
		
			$thumb_url = $video_thumb['Thumbnail'];
			$video_detail_arr = get_kronopress_video_by_videoid($video_id);
			  $embed_code= $video_detail_arr['EmbedURL'];
			  $embed_url = get_url_by_embed_code($embed_code);
			if($embed_code!="")
			{
			  echo '<iframe webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen="true" src="'.$embed_url.'" width="'.$width.'" height="'.$height.'"></iframe>';
			}
			else
			{
			
			if($thumb_url=="")
			{
				 $thumb_url = plugins_url('images/video_placeholder.jpg',dirname(__FILE__));
			}
			?>
			<img src="<?php echo $thumb_url; ?>">		
                         <?php
			}
			 ?> 
			</div>
    
			<?php if($show_title=="yes" || $show_title=="YES") {?>
			<div class="video_title"><?php echo $video_thumb['Title'] ?></div>
			<?php } ?>
    
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
	$counter = 0;
 $cat_counter++;
 }
	//----
	
	?>
    
    </div>
    
    <?php
return ob_get_clean();	
}
add_shortcode( 'show_video_player_by_cat', 'show_video_player_by_cat_func' );

?>