<?php
function autoimport_videos_function()
{
if(get_option('enable_autoimport')=="off") { die; }
$cat_arr = get_kronopress_video_cat();
$check_mapping = "";
foreach($cat_arr as $cat)
{
	$map_with = get_option('map_cat_'.$cat['CategoryId']);
         if($map_with!="")
	 {
         $check_mapping .= $map_with; 
	 }
}
	
		if($check_mapping=="") 	{ die; }
		
		$video_id_arr = get_all_api_video_id();
		$video_arr_tmp = krono_latest_videos_arr(1);
		$last_video_id  = $video_arr_tmp[0]['VideoId'];
		$video_id = $last_video_id;
	        	
		$record_count = 0;
		$bunch_size = 5;
		while($record_count<$bunch_size)
		{
			$video_arr = get_limited_videos($bunch_size,$video_id);
			foreach($video_arr as $video)
			{
				$video_id = $video['VideoId'];
				$video_cat_id = $video['CategoryId'];
				$cat_key="map_cat_".$video_cat_id;
				if(!in_array($video_id, $video_id_arr) && get_option($cat_key)!="" && get_option($cat_key)!=false)
					{ 
						$description = "<center>[embed_video id=".$video_id."]</center>";
						$description.="<br/>".$video['Description'];
						$excerpt =  wp_trim_words( $description, 25, '..' );
						$thumb_url = $video['Thumbnail'];
						$timestamp = $video['Date'];
						$timestamp = substr($timestamp,6,-2);
						$timestamp = explode("+", $timestamp);
						$timestamp = floor($timestamp[0]/1000);
						$date = date('Y-m-d H:i:s', $timestamp);
						$wp_post_cat ="";
						$cat_arr= get_categories_by_video_id($video_id);
						foreach($cat_arr as $cat)
						{
							$cat_key2= "map_cat_".$cat;
							if(get_option($cat_key2)!="" && get_option($cat_key2)!=false)
							{						
								$wp_post_cat .=get_option($cat_key2).",";
							}
						}
						$wp_post_cat_arr= explode(",",$wp_post_cat);
						$args = array(
							'post_content'   => $description,
							'post_name'      => $video['Title'],
							'post_title'     =>  $video['Title'],
							'post_status'    => 'publish', 
							'post_type'      => 'post',
							'post_excerpt'   => $excerpt,
							'post_date' => 	$date,
							'post_category'  => $wp_post_cat_arr
							 );
						$post_id = wp_insert_post( $args );
						//if(@getimagesize($thumb_url))
						if($thumb_url!="")
						{
						if(get_http_response_code($thumb_url) == "200")
							{
							  attach_url_as_thumb($post_id, $thumb_url);
							}
						}
						
						update_post_meta($post_id, 'api_video_id', $video_id);
						array_push($video_id_arr,$video_id);
												
						$old_id_list = get_transient('imported_video_ids');
						$new_id_list = $old_id_list.$video_id.",";
						
						set_transient( 'imported_video_ids', $new_id_list, WEEK_IN_SECONDS*2 );
						set_post_format( $post_id , 'video');
						$record_count++;
					}				
			
			if($record_count>=$bunch_size) { break; }
				}
			$array_size = count($video_arr);
				if($array_size<$bunch_size)
				{
					break;
				}
		}		
	echo $record_count;  die;
}
add_action( 'wp_ajax_autoimport_videos', 'autoimport_videos_function' );
add_action( 'wp_ajax_nopriv_autoimport_videos', 'autoimport_videos_function' );

function autoimport_js_func()
{
if(get_option('enable_autoimport')!="off")
	{
	?>
	<script type='text/javascript'>
		jQuery(document).ready(function(){
		autoimport_videos();
		});
	</script>
	<?php
	}	
}

add_action("wp_footer","autoimport_js_func");


?>