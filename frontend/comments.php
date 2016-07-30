<?php
function show_comments_func( $atts ) {
    ob_start();
    $a = shortcode_atts( array(
        'video_id' => '',
        'count' => 0,
        'per_page'=>10,
        'title' => 'Comments',
        'load_older_comments' =>'yes',
        'form_title' => 'Leave a Reply'
	   ), $atts );
    $video_id = $a['video_id'];
      $count = $a['count'];
      $per_page = $a['per_page'];
      $title = $a['title'];
      $form_title = $a['form_title'];
      $load_older_comments = $a['load_older_comments'];
    if($video_id=="")
    {
    $video_id = $_REQUEST['video_id'];
    }
    $loading_img_url = plugins_url('images/loading.gif',dirname(__FILE__));
    $comments = get_limited_comments($video_id,$per_page,0);
   $comments_rev = array_reverse($comments);
      // echo "<pre>";
      //print_r($comments);
      //echo "</pre>";
    ?>
    <div class="kp_title krono_comment_title"><?php echo $title; ?></div>
    <?php if(($count==0 || $count>$per_page)&& $load_older_comments=="yes")
    {
    ?>
    <div class="load_more_comments"><span>Load older comments</span></div>
     <div class="loading_older_comments"><img src="<?php echo $loading_img_url ?>" height="50px" width="50px"></div>
   
    <input type="hidden" name="comments_per_page" class="comments_per_page" value="<?php echo  $per_page; ?>">
    <?php } ?>
    <div class="krono_comment_list">
        <?php foreach($comments_rev as $comment)
    {
        ?>
      <div class="krono_single_comment">
         <input type="hidden" name="last_comment_id" class="last_comment_id" value="<?php echo $comment['CommentId']; ?>">
        <div class="comment_author_name"><?php echo $comment['Name']; ?></div>
         <div class="comment_date">
            <?php $comment_date = get_date_by_timestamp($comment['CommentDate']);
            $comment_time = get_time_by_timestamp($comment['CommentDate']);
            echo $comment_date." at ".$comment_time;
            ?>
            
         </div>
        <div class="comment_text"><?php echo $comment['Comment']; ?></div> 
        </div>
      <?php }  ?>
        
    </div>
   
    <div class="krono_comment_form">
    <div class="comment_form_title"><?php echo $form_title; ?></div>
    <input type="hidden" name="krono_comment_video_id" class="krono_comment_video_id" value="<?php echo $video_id; ?>">
    <label class="label_comment_user_name">Name</label> <br/><input type="text" name="comment_user_name" class="comment_user_name"><br/>
    <label class="label_comment_user_email">Email</label><br/> <input type="text" name="comment_user_email" class="comment_user_email"><br/>
    <label class="label_comment_user_comment">Comment</label><br/>
    <textarea name="krono_comment_box" id="krono_comment_box"></textarea><br/>
    <button class="krono_comment_button">Post comment</button>
    <br/>	
    <div class="loading_post_comments"><img src="<?php echo $loading_img_url ?>" height="50px" width="50px"></div>
    </div>
    <?php
    
 return ob_get_clean();	
}
add_shortcode( 'show_comments', 'show_comments_func' );
?>