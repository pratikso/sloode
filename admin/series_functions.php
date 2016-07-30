<?php

function krono_series_list_arr()
{
 // $api_url = "http://api.kronopress.com/WCFServices/Kronopress.svc/VideoSeriesList/c9PXsY2BGqKYCzMeq6Oe3Q==/074a6034-aadb-4b82-a690-85aec7e2566e";
   $api_url = KRONO_API_LINK."VideoSeriesList/".API_KEY."/".API_SECRET;
    
      if ( false === ( get_transient( 'video_series_list' ) ) ) {
      $series_list_data = wp_remote_retrieve_body( wp_remote_get($api_url, array('sslverify' => false )));
      set_transient( 'video_series_list', $series_list_data, 100 );
      }
    
        $recent_video_transient= get_transient('video_series_list');
    	$series_list_arr = json_decode( $recent_video_transient,  true);
	return $series_list_arr;
}

function krono_episode_list_arr($series_id,$numberpost="all")
{
   //$api_url = "http://api.kronopress.com/WCFServices/Kronopress.svc/VideoSeries/c9PXsY2BGqKYCzMeq6Oe3Q==/074a6034-aadb-4b82-a690-85aec7e2566e/".$numberpost."/".$series_id;
   $api_url = KRONO_API_LINK."VideoSeries/".API_KEY."/".API_SECRET."/".$numberpost."/".$series_id;

      if ( false === ( get_transient( 'video_episode_list'.$series_id."_".$numberpost ) ) ) {
      $episode_list_data = wp_remote_retrieve_body( wp_remote_get($api_url, array('sslverify' => false )));
      set_transient( 'video_episode_list'.$series_id."_".$numberpost , $episode_list_data, 100 );
      }
    
        $recent_video_transient= get_transient('video_episode_list'.$series_id."_".$numberpost);
    	$series_list_arr = json_decode( $recent_video_transient,  true);
	return $series_list_arr;

}
function krono_limited_episode_list_arr($series_id,$numberpost,$video_id)
{
   
   $api_url = KRONO_API_LINK."VideoSeriesByFilter/".API_KEY."/".API_SECRET."/".$numberpost."/".$series_id."/".$video_id;
   $episode_list_data = wp_remote_retrieve_body( wp_remote_get($api_url, array('sslverify' => false )));
   $series_list_arr = json_decode($episode_list_data,  true);
   return $series_list_arr;

}
function insert_attachment($file_handler,$post_id=0,$setthumb='false') {
    // check to make sure its a successful upload
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();
    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');
 
  $attach_id = media_handle_upload( $file_handler, $post_id );
  if ($setthumb)
   {
   update_post_meta($post_id,'_thumbnail_id',$attach_id);
   }
   return $attach_id;
}

function add_episode($series_id, $video_id)
{
$api_url = KRONO_API_LINK."AddVideoSeriesEpisode/".API_KEY."/".API_SECRET."/".$video_id."/".$series_id;	
 $api_response = wp_remote_retrieve_body( wp_remote_post($api_url, array('sslverify' => false,'method' => 'POST' )));
 $api_response_arr = json_decode($api_response,  true);
 return $api_response_arr;
}


add_action( 'wp_ajax_delete_kronopress_episode', 'delete_kronopress_episode' );
function delete_kronopress_episode()
{	$episode_id =   $_REQUEST['episode_id'];
	$api_url = KRONO_API_LINK."DeleteVideoSeriesEpisode/".API_KEY."/".API_SECRET."/".$episode_id;
	$api_response = wp_remote_retrieve_body( wp_remote_post($api_url, array('sslverify' => false,'method' => 'POST' )));
	echo $api_response;
	die;
}
add_action( 'wp_ajax_delete_kronopress_series', 'delete_kronopress_series' );
function delete_kronopress_series()
{	$series_id =   $_REQUEST['series_id'];
	$vsd_id = $_REQUEST['vsd_id'];
	$api_url = KRONO_API_LINK."DeleteSeries/".API_KEY."/".API_SECRET."/".$vsd_id."/".$series_id;
	$api_response = wp_remote_retrieve_body( wp_remote_post($api_url, array('sslverify' => false,'method' => 'POST' )));
	echo $api_response;
	die;
}

?>