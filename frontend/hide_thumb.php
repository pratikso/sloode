<?php
add_filter( 'post_thumbnail_html', 'my_post_image_html', 10, 5 );
function my_post_image_html( $html, $post_id, $post_thumbnail_id, $size, $attr )
{
    if(is_single())
    {
	$hide_thumb = get_option('hide_thumb');
	if($hide_thumb=="all")
	{  return "";  	}
	elseif($hide_thumb=="video" && get_post_format()=="video")
	{ return ""; }
	elseif($hide_thumb=="imported" )
	{
	    $pid = get_the_ID();
	    $video_id =  get_post_meta($pid,'api_video_id',true);
	    if($video_id!="" || $video_id!=NULL)
	    { return "";
	    }
	    else 
	    {  return $html;
	    }
	}
	else
	{ return $html;
	}    
    }
    else
    {	return $html;
    }   
} ?>