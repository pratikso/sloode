<?php
function embed_video_func( $atts )
{
	if(!is_blog())
	{
		ob_start();
		$default_width = (get_option('krono_player_width')=="") ? '100%' : esc_attr(get_option('krono_player_width')); 
		$default_height = (get_option('krono_player_height')=="") ? '400' : esc_attr(get_option('krono_player_height'));
		    
	       $a = shortcode_atts( array(
		'width' => $default_width,
		 'height' => $default_height,
		 'id' =>""
		 ), $atts );
	    $width = $a['width'];
	    $height = $a['height'];
	   if($a['id']=="")
	   {
	    $video_id = $_REQUEST['video_id'];
	   }
	   else
	   {
	     $video_id = $a['id'];
	   }
	  
	    $video_detail_arr = get_kronopress_video_by_videoid($video_id);
	  
	   $embed_code= $video_detail_arr['EmbedURL'];
	   preg_match('/(src=["\'](.*?)["\'])/',  $embed_code, $match);  //find src="X" or src='X'
	     $split = preg_split('/["\']/', $match[0]); // split by quotes
	     $embed_url = $split[1];
	    echo '<iframe src="'.$embed_url.'" width="'.$width.'" height="'.$height.'"></iframe>';
	   return ob_get_clean();
	}
	else
	{
		return "";
	}
}
add_shortcode( 'embed_video', 'embed_video_func' );
?>