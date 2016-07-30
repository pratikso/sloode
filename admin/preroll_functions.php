<?php

function krono_preroll_list_arr()
{
 
   $api_url = KRONO_API_LINK."GetPrerollList/".API_KEY."/".API_SECRET;
   
      if ( false === ( get_transient( 'video_preroll_list' ) ) ) {
      $preroll_list_data = wp_remote_retrieve_body( wp_remote_get($api_url, array('sslverify' => false )));
      set_transient( 'video_preroll_list', $preroll_list_data, 100 );
      }
    
        $preroll_list_transient= get_transient('video_preroll_list');
    	$preroll_list_arr = json_decode( $preroll_list_transient,  true);
	return $preroll_list_arr;
}

add_action( 'wp_ajax_delete_kronopress_preroll', 'delete_kronopress_preroll' );
function delete_kronopress_preroll()
{	$preroll_id =   $_REQUEST['preroll_id'];
	
	$api_url = KRONO_API_LINK."DeletePreroll/".API_KEY."/".API_SECRET."/".$preroll_id;
	$api_response = wp_remote_retrieve_body( wp_remote_post($api_url, array('sslverify' => false,'method' => 'POST' )));
	echo $api_response;
	die;
	
}

function add_preroll($video_id, $cat_id, $start_date, $end_date)
{
   
 $api_url = KRONO_API_LINK."AddPreroll?APIKey=".API_KEY."&SecretKey=".API_SECRET."&VideoId=".$video_id."&CategoryId=".$cat_id."&StartDate=".$start_date."&EndDate=".$end_date;
 $api_response = wp_remote_retrieve_body( wp_remote_post($api_url, array('sslverify' => false,'method' => 'POST' )));
 $api_response_arr = json_decode($api_response,  true);
 return $api_response_arr;
}

function is_video_exist($video_id)
{
$api_url = KRONO_API_LINK."IsVideoExist/".API_KEY."/".API_SECRET."/".$video_id;
 $api_response = wp_remote_retrieve_body( wp_remote_get($api_url, array('sslverify' => false )));
 $api_response_arr = json_decode($api_response,  true);
 return $api_response_arr;
}

?>