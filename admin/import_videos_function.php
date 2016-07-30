<?php
function import_videos_function()
{
	
		$video_id_arr = get_all_api_video_id();
		//$video_arr = krono_all_videos_arr();
		//$video_arr = array_slice($video_arr, 0, 50);
		$last_video_id = $_POST['last_video_id'];
		$video_id = $last_video_id;
		$record_count = 0;
		$bunch_size = 20;
		$record_size = 20;
		while($record_count<$bunch_size)
		{
			$video_arr = get_limited_videos($record_size,$video_id);
			foreach($video_arr as $video)
			{
				$video_id = $video['VideoId'];
				$video_cat_id = $video['CategoryId'];
				$cat_key="map_cat_".$video_cat_id;
				if(isset($_POST[$cat_key]))
				{
					if(!in_array($video_id, $video_id_arr) && $_POST[$cat_key]!="")
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
							if(isset($_POST[$cat_key2]) && $_POST[$cat_key2]!="" )
							{
							
								$wp_post_cat .=$_POST[$cat_key2].",";
							
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
				}
			
			if($record_count>=$bunch_size) { break; }
			
			}
			$array_size = count($video_arr);
				if($array_size<$record_size)
				{
					break;
				}
		} 
		
if($record_count==$bunch_size)
{
echo '<h3 class="success_msg">'.$record_count.' New records imported successfully.</h3>
<input type="hidden" name="last_video_id" id="krono_video_id" value="'.$video_id.'"><h4>More records available to import.</h4>
Press continue to import more records..<br/><br/>
<input type="button" value="Continue import.." name="import_videos" class="button button-primary" id="import_videos_btn">';	
}
else if($record_count<$bunch_size)
{
	echo '<h3 class="success_msg">'.$record_count.' New records imported successfully.</h3>
<br/><h4>No more records available to import.</h4>';	
}
else if($record_count==0)
{
	echo '<br/><h4>No records available to import.</h4>';
}
else
{
	echo "<br/><h4>Error!</h4>";
}
die;
}
add_action( 'wp_ajax_import_videos', 'import_videos_function' );



function attach_url_as_thumb($post_id, $thumb_url)
{
	// Add Featured Image to Post
$image_url  = $thumb_url; // Define the image URL here
$upload_dir = wp_upload_dir(); // Set upload folder
$image_data = file_get_contents($image_url); // Get image data
$filename   = basename($image_url); // Create image file name

// Check folder permission and define file location
if( wp_mkdir_p( $upload_dir['path'] ) ) {
    $file = $upload_dir['path'] . '/' . $filename;
} else {
    $file = $upload_dir['basedir'] . '/' . $filename;
}

// Create the image  file on the server
file_put_contents( $file, $image_data );

// Check image file type
$wp_filetype = wp_check_filetype( $filename, null );

// Set attachment data
$attachment = array(
    'post_mime_type' => $wp_filetype['type'],
    'post_title'     => sanitize_file_name( $filename ),
    'post_content'   => '',
    'post_status'    => 'inherit'
);

// Create the attachment
$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

// Include image.php
require_once(ABSPATH . 'wp-admin/includes/image.php');

// Define attachment metadata
$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

// Assign metadata to attachment
wp_update_attachment_metadata( $attach_id, $attach_data );

// And finally assign featured image to post
set_post_thumbnail( $post_id, $attach_id );
	
}

function get_all_api_video_id()
{
	 if ( false === (get_transient( 'imported_video_ids') ) )
	{
	global $wpdb;
	$querystr = "SELECT meta_value FROM $wpdb->postmeta
        WHERE $wpdb->postmeta.meta_key = 'api_video_id'";
	$pageposts = $wpdb->get_results($querystr);
	//$video_id_list = array_map(function($obj) { return $obj->meta_value; },$pageposts);
		$video_id_list = array();
		foreach($pageposts as $obj)
		{
		   array_push($video_id_list,$obj->meta_value);
		}
	$video_id_list = implode(", ", $video_id_list);
	set_transient( 'imported_video_ids', $video_id_list, WEEK_IN_SECONDS*2 );
	}
	$video_id_list = get_transient( 'imported_video_ids');
	$video_id_arr = explode(",",$video_id_list);
	return $video_id_arr;	
	

}


?>