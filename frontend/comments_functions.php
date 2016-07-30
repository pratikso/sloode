<?php
add_action( 'wp_ajax_add_kronopress_comment', 'add_kronopress_comment' );
add_action( 'wp_ajax_nopriv_add_kronopress_comment', 'add_kronopress_comment' );
function add_kronopress_comment()
{
    $videoid =   $_REQUEST['comment_video_id'];
    $comment_txt = $_REQUEST['comment_txt'];
    $comment_txt = str_replace(" ","&nbsp;",$comment_txt);
    $comment_user_name = $_REQUEST['comment_user_name'];
    $comment_user_email = $_REQUEST['comment_user_email'];
	$api_url = KRONO_API_LINK."AddComment/".API_KEY."/".API_SECRET."/".$videoid."/".$comment_txt."/".$comment_user_name."/".$comment_user_email;
	$api_response = wp_remote_retrieve_body( wp_remote_post($api_url, array('sslverify' => false,'method' => 'POST' )));
	//echo $api_response; die;
	$api_response_arr = json_decode($api_response,  true);
	//echo $api_response_arr;
	if($api_response_arr=="Success")
	{
	    echo '<div class="krono_single_comment">';
	    echo '<div class="comment_author_name">'.$comment_user_name.'</div>';
	    echo '<div class="comment_date"> Few minutes ago</div>';
	    echo '<div class="comment_text">'.$comment_txt.'</div>';
	    echo '</div>';
	    
	}
      die;
}

add_action( 'wp_ajax_load_older_comments', 'load_older_comments' );
add_action( 'wp_ajax_nopriv_load_older_comments', 'load_older_comments' );
function load_older_comments()
{
        $videoid =   $_REQUEST['comment_video_id'];
	$last_comment_id = $_REQUEST['last_comment_id'];
	$comments_per_page = $_REQUEST['comments_per_page']+1;
	$comments = get_limited_comments($videoid,$comments_per_page,$last_comment_id);
	$comments_rev = array_reverse($comments);
	$count = 0;
	foreach($comments_rev as $comment)
	{
	 if($comment['CommentId']!=$last_comment_id)
	 {
	    echo '<div class="krono_single_comment">';
	    echo '<input type="hidden" name="last_comment_id" class="last_comment_id" value="'.$comment['CommentId'].'">';
	    echo '<div class="comment_author_name">'.$comment['Name'].'</div>';
	    $comment_date = get_date_by_timestamp($comment['CommentDate']);
            $comment_time = get_time_by_timestamp($comment['CommentDate']);
           
	    echo '<div class="comment_date">'. $comment_date.' at '.$comment_time.'</div>';
	    echo '<div class="comment_text">'.$comment['Comment'].'</div>';
	    echo '</div>';
	    $count++;
	 }
	}
	if($count==0)
	{
	    echo 'null';
	}
	die;
    
}



function get_kronopress_comments($video_id)
{
   $api_url = KRONO_API_LINK."GetComments/".API_KEY."/".API_SECRET."/".$video_id;
   $api_response = file_get_contents($api_url);
   $video_comments_list= json_decode($api_response,  true);
   return $video_comments_list;
}
function get_limited_comments($video_id,$limit,$comment_id)
{
   $api_url = KRONO_API_LINK."GetCommentsByFilter/".API_KEY."/".API_SECRET."/".$video_id."/".$limit."/".$comment_id;
   $api_response = file_get_contents($api_url);
   $video_comments_list= json_decode($api_response,  true);
   return $video_comments_list;
}

?>