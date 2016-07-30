<?php
function live_streaming_func( $atts ) {
	ob_start();
	  $a = shortcode_atts( array(
        'width' => '80%',
	 'height' => 400,
	 'channel_id' =>"",
	 'hit_count' => "yes"
         ), $atts );
    $width = $a['width'];
    $height = $a['height'];
	
	
	$live_streaming_arr =get_live_streaming_by_channel_id($a['channel_id']);
	
	foreach ($live_streaming_arr as $channel)
		{
		 $channel_id = $channel['ChannelId'];
		 if($channel_id == $a['channel_id'])
		 {
			$embed_code= $channel['EmbedURL'];
			preg_match('/(src=["\'](.*?)["\'])/',  $embed_code, $match);  //find src="X" or src='X'
			$split = preg_split('/["\']/', $match[0]); // split by quotes
			$embed_url = $split[1];
			
			
		echo '<iframe src="'.$embed_url.'" width="'.$width.'" height="'.$height.'"></iframe>';
		break;		
		 }
		 
		}
	
    return ob_get_clean();	
}
add_shortcode( 'live_streaming', 'live_streaming_func' );

?>