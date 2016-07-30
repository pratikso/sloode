<?php
function show_likes_func( $atts )
{
	ob_start();
    $a = shortcode_atts( array(
        
	 'video_id' =>"",
	 'show_count' => "yes",
	 ), $atts );
    $show_count = $a['show_count'];
   
   if($a['video_id']=="")
   {
    $video_id = $_REQUEST['video_id'];
   }
   else
   {
     $video_id = $a['video_id'];
   }
  $like_img_url = $loading_img_url = plugins_url('images/thumb_up.png',dirname(__FILE__));
  $dislike_img_url = $loading_img_url = plugins_url('images/thumb_down.png',dirname(__FILE__));
  $like_dislike_count_arr = get_like_dislike_count($video_id);
  $user_name = $_COOKIE['temp_user_name'];
$user_email = $user_name."@noreply.com";
 $user_vote_arr= get_like_dislike($video_id,$user_name,$user_email);
 if(empty($user_vote_arr))
 {
   $user_vote = "no_vote";	
 }
 else if($user_vote_arr['IsLike']==1)
 {
	$user_vote ="like";
 }
 else
 {
	$user_vote ="dislike";
 }
 
?>
 <div class="like_dislike_wrap">
	<div class="like_message">
		<?php
		if($user_vote=="like")
		{
			echo "You liked this video.";
		}
		else if($user_vote=="dislike")
		{
			echo "You unliked this video.";
		}
		?>
	</div>
	<input type="hidden" name="like_dislike_video_id" class="like_dislike_video_id" value="<?php echo $video_id; ?>">
	<input type="hidden" name="user_vote" class="user_vote" value="<?php echo $user_vote; ?>">
	<div class="like_box li_di_box">
		<span class="like_img li_di_img">
			<img src="<?php echo $like_img_url; ?>">
		</span>
		<span class="like_count li_di_count">
			<?php echo $like_dislike_count_arr['LikeCount']; ?>
		</span>
		<input type="hidden" class="is_like" name="is_like_true" value="yes">
	</div>


	<div class="dislike_box li_di_box ">
		<span class="dislike_img li_di_img">
		<img src="<?php echo $dislike_img_url; ?>">
		</span>
		<span class="dislike_count li_di_count">
			<?php echo $like_dislike_count_arr['DisLikeCount']; ?>
		</span>
		<input type="hidden" class="is_like" name="is_like_false" value="no">
	</div>
	<div class="clear"></div>
	
</div>
 
 
 
 <?php 
    return ob_get_clean();	
}
add_shortcode( 'show_likes', 'show_likes_func' );
function do_like_dislike_func()
{
$is_like = $_REQUEST['is_like'];
$video_id = $_REQUEST['video_id'];
$user_name = $_COOKIE['temp_user_name'];
$user_email = $user_name."@noreply.com";
//echo $user_name;
if($is_like=="yes")
{
	$result = do_like($video_id, $user_name, $user_email);
}
else if($is_like=="no")
{
	$result = do_dislike($video_id, $user_name, $user_email);
}
else
{
	$result = "failed";
}
echo $result;	
	die;
}
add_action( 'wp_ajax_do_like_dislike', 'do_like_dislike_func' );
add_action( 'wp_ajax_nopriv_do_like_dislike', 'do_like_dislike_func' );

?>