<?php
function load_video_list()
{
	if(isset($_REQUEST['load_video_list']))
	{
		
	   // $api_url = KRONO_API_LINK."GetVideoList/".API_KEY."/".API_SECRET."/50";
	   
	   $api_url= ALL_VIDEO_API_URL;
            $video_data = wp_remote_retrieve_body( wp_remote_get($api_url, array('sslverify' => false )));
	    set_transient( 'krono_all_videos_data',  $video_data, 5*WEEK_IN_SECONDS );	
		
	}
	
}
add_action( 'admin_init', 'load_video_list' );


function refresh_video_list()
{
	if(isset($_REQUEST['refresh_video_list']))
	{
		
	   $api_url = KRONO_API_LINK."GetVideoList/".API_KEY."/".API_SECRET."/50";
	   $all_video_api_url= ALL_VIDEO_API_URL;
	 	if ( false === ( get_transient( 'krono_all_videos_data' ) ) )
		{
		$all_video_data = wp_remote_retrieve_body( wp_remote_get( $all_video_api_url, array('sslverify' => false )));
		set_transient( 'krono_all_videos_data', $all_video_data, 5 * WEEK_IN_SECONDS );
		}
	        
	  
            $recent_videos_jason = wp_remote_retrieve_body( wp_remote_get($api_url, array('sslverify' => false )));
	    $all_videos_jason = get_transient('krono_all_videos_data' );
	    $recent_videos_arr = json_decode( $recent_videos_jason,  true);
	    $all_videos_arr = json_decode(  $all_videos_jason,  true);	    
	    $all_videos_arr_slice = array_slice($all_videos_arr,0,50);
	    $new_videos_arr = array();
	    $is_new=1;
	     foreach ($recent_videos_arr as $recent_video)
	     {
		
			 foreach ($all_videos_arr_slice as $all_video)
			{
			   if($recent_video['VideoId'] == $all_video['VideoId'])
			   {
				 $is_new=0;
			   }
			   
			}
			if($is_new)
			{
			array_push($new_videos_arr,$recent_video);	
			}
			$is_new=1;
		
		
		
	     }
	   
	    $return_arr =array_merge($new_videos_arr,$all_videos_arr);
	    $return_jason = json_encode($return_arr);
	    set_transient( 'krono_all_videos_data',  $return_jason, 5 * WEEK_IN_SECONDS );	
		
	}
	
}
add_action( 'admin_init', 'refresh_video_list' );



?>