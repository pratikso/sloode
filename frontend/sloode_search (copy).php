<?php
function sloode_search_form_func( $atts ) {	
    ob_start();
    $a = shortcode_atts( array(
        'per_page' => 0,
	'placeholder' => "Keyword"
	), $atts );
    	$per_page = $a['per_page'];
	$placeholder = $a['placeholder'];
	$sloode_search_page_id = get_option('sloode_search_page_id');
	$sloode_search_page_url=get_permalink($sloode_search_page_id);
	?>
	<div class="sloode_search_form">
	<form action="<?php echo $sloode_search_page_url; ?>" method="post">
	<select name="sloode_search_type" class="sloode_search_type">
	<option value="article">Article</option>
	<option value="video">Video</option>
	</select>
	<input type="text" name="sloode_search_key" value="" placeholder="<?php echo $placeholder; ?>">
	<input type="submit" name="sloode_search_submit" Value="Search">
	</form>
	</div>
	
	<?php
	return ob_get_clean();
}
add_shortcode( 'sloode_search_form', 'sloode_search_form_func' );

function sloode_search_result_func($atts)
{
    ob_start();
    $a = shortcode_atts( array(
        'per_page' => 10,
	 'word_limit' => 55
	 ), $atts );
    	$per_page = $a['per_page'];
	$word_limit = $a['word_limit'];
	$count = 0;
	 global $post;
  if(isset($_POST["sloode_search_key"]))
  {
   $date_format= get_option('date_format');
   $search_key = $_POST["sloode_search_key"];
   $search_type = $_POST["sloode_search_type"];
   
   
   if(trim($search_key)!="")
   {
    
    
    
    echo '<div class="sloode_search_result_title">Search Results for: '.$search_key.'</div>';
   
  ?>
<div class="sloode_search_page_container">
      
    <input type="hidden" name="sloode_search_key" id="sloode_search_key" value="<?php echo $search_key; ?>">
    <input type="hidden" name="sloode_search_per_page" id="sloode_search_per_page" value="<?php echo $per_page; ?>">
    <input type="hidden" name="sloode_word_limit" id="sloode_word_limit" value="<?php echo $per_page; ?>">
    <?php
    
    ?>
	<div class="sloode_search_wrap sloode_search_page_1">
	<?php
  $args=array(
  's' => $search_key,
  'posts_per_page'=> $per_page
  );
  
 query_posts($args); 
  if (have_posts()) : while (have_posts()) : the_post();
 ?>
	<div class="sloode_search_post">
	<div class="sloode_search_post_left">
	    <?php
	   
	    $thumb_url = "";
	if ( has_post_thumbnail() ) {
	    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
	    $thumb_url = $thumb['0'];
	
	}	
		
				if($thumb_url=="")
				{
				  $thumb_url = plugins_url('images/placeholder.png',dirname(__FILE__));
				}
				?>
				<img src="<?php echo $thumb_url; ?>">
	    
	</div>
	
	    <div class="sloode_search_post_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
	    <div class="sloode_search_post_date"><span class="posted_on">Posted on: </span><?php echo get_the_date($date_format); ?></div>
	    <div class="sloode_search_post_content"><?php
	    $sloode_search_excerpt="";
	    $sloode_search_content = get_the_content();
	    $sloode_search_excerpt = wp_trim_words($sloode_search_content,$word_limit);
	    echo $sloode_search_excerpt;
	    if($sloode_search_excerpt!="")
	    { echo "..."; }
	    
	    echo '<a href="'.get_the_permalink().'" class="sloode_read_more">Read more</a>';
	    $count++;
	    ?>
	    </div>
	
	</div>
  <?php
  endwhile;
  endif;
  wp_reset_query();
   if($count<$per_page)
   {
    // $mode="ws";
    $new_per_page = $per_page - $count;
    //echo $new_per_page."show from webservice";
    $video_arr = get_videos_by_keyword($search_key,$new_per_page);
   // print_r($video_arr);
 
    foreach($video_arr as $video)
    {
	$video_id = $video['VideoId'];
	$player_page_id = get_option('video_player_page_id');
	$video_page_link = add_query_arg( array('video_id' => $video_id ),  get_page_link($player_page_id) );
	?>
	<div class="sloode_search_post ws_mode">
	   <div class="sloode_search_post_left">
	    <?php
	    $thumb_url = $video['Thumbnail'];
				if($thumb_url=="")
				{
					 $thumb_url = plugins_url('images/video_placeholder.jpg',dirname(__FILE__));
				}
				?>
	<img src="<?php echo $thumb_url; ?>">
	    
	</div>
	
	<div class="sloode_search_post_title"><a href="<?php echo $video_page_link; ?>"><?php echo $video['Title']; ?></a></div>
	 <div class="sloode_search_post_date"><span class="posted_on">Posted on: </span><?php
	$timestamp = $video['Date'];
	$date = get_date_by_timestamp($timestamp);
	echo $date;
	 
	 ?></div>
	<div class="sloode_search_post_content"><?php
	$sloode_search_excerpt="";
	$sloode_search_content = $video['Description'];
	$sloode_search_excerpt = wp_trim_words($sloode_search_content,$word_limit);
	echo $sloode_search_excerpt;
	if($sloode_search_excerpt!="")
	{ echo "..."; }
	
	echo '<a href="'.$video_page_link.'" class="sloode_read_more">Read more</a>';
	$count++;
	?>
	</div>
		<input type="hidden" name="ws_search_last_post_id" class="ws_search_last_post_id" value="<?php echo $video_id; ?>">
	</div>
	
	<?php
    }
    //echo $count;
    if($count==0)
    {
	echo "<br/>No result found. Please try another keyword.<br/>";
	 echo do_shortcode("[sloode_search_form]");
    }
    
   }
     
     ?>
 
 <div class="sloode_search_page_no">Page: 1</div>
  </div>

</div>
 <?php
   
   
   }
   else
   {
	echo "<br/>No result found. Insert one word at the least.<br/>";
	 echo do_shortcode("[sloode_search_form]");
   }
 
  }
  else
  {
    echo "<br/>Please submit your search key below:<br/> ";
    echo do_shortcode("[sloode_search_form]");
  }
  
  ?>	
	
	
	<?php if($count==$per_page){ ?>
	<div class="sloode_search_next_prev">
	     <div class = "sloode_prev" data-page_no="0">Prev</div>
	    <div class = "sloode_next" data-page_no="2">Next</div>
	  
	    <div class="clear clearfix"></div>
	</div>
	 <div class="sloode_search_loader">
	     <?php
	    $loading_img_url = plugins_url('images/loading.gif',dirname(__FILE__));
	    ?>
	    <img src="<?php echo $loading_img_url; ?>">
	   </div>
	<?php } ?>
	
	<!---sloode search wrap---->
   <?php 
    return ob_get_clean();
}

add_shortcode( 'sloode_search_result', 'sloode_search_result_func' );

function search_next_func()
{
    $search_mode = $_POST['search_mode'];
    $page_no = $_POST['page_no'];
    $per_page = $_POST['per_page'];
    $search_key = $_POST['search_key'];
    $offset = $per_page *($page_no-1);
    $date_format= get_option('date_format');
    $word_limit = $_POST['word_limit'];
    $last_post_id = $_POST['last_post_id'];
 ?>
    <div class="sloode_search_wrap sloode_search_page_<?php echo $page_no; ?>" style="display: none;">
	<?php
	//echo $per_page."-".$search_key."-".$offset;
	//echo "page_no = ".$page_no;
	//echo "<br>mode = ".$search_mode;
	 $count = 0;
	if($search_mode=="wp")
	{
	        $args=array(
		's'      => $search_key,
		'posts_per_page'=> $per_page,
		'offset' => $offset
		);
  global $post;
 query_posts($args); 
  if (have_posts()) : while (have_posts()) : the_post();
	//  echo get_the_ID();
	?>
	<div class="sloode_search_post">
	<div class="sloode_search_post_left">
	    <?php
	   
	    $thumb_url = "";
	if ( has_post_thumbnail() ) {
	    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
	    $thumb_url = $thumb['0'];
	
	}	
		
				if($thumb_url=="")
				{
				  $thumb_url = plugins_url('images/placeholder.png',dirname(__FILE__));
				}
				?>
				<img src="<?php echo $thumb_url; ?>">
	    
	</div>
	
	    <div class="sloode_search_post_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
	    <div class="sloode_search_post_date"><span class="posted_on">Posted on: </span><?php echo get_the_date($date_format); ?></div>
	    <div class="sloode_search_post_content"><?php
	    $sloode_search_excerpt="";
	    $sloode_search_content = get_the_content();
	    $sloode_search_excerpt = wp_trim_words($sloode_search_content,$word_limit);
	    echo $sloode_search_excerpt;
	    if($sloode_search_excerpt!="")
	    { echo "..."; }
	    
	    echo '<a href="'.get_the_permalink().'" class="sloode_read_more">Read more</a>';
	    $count++;
	    ?>
	    </div>
	
	</div>
	
	<?php endwhile;
		endif;
		}
		  wp_reset_query();
   if($count<$per_page)
   {
   // $mode="ws";
    $new_per_page = $per_page - $count;
    //echo $new_per_page."show from webservice";
   
  if($search_mode=="wp")
  {
    $video_arr = get_videos_by_keyword($search_key,$new_per_page);
  }
  else
  {
    $video_arr = get_limited_videos_by_keyword($search_key,$new_per_page+1,$last_post_id);
  // echo get_test_url($search_key,$new_per_page+1,$last_post_id);
   
  }
  
    $wscount=1;
   //print_r($video_arr);
    foreach($video_arr as $video) {
	if(($wscount>1 && $search_mode=="ws")||($search_mode=="wp"))
	{
	$video_id = $video['VideoId'];
	$player_page_id = get_option('video_player_page_id');
	$video_page_link = add_query_arg( array('video_id' => $video_id ),  get_page_link($player_page_id) );  ?>
	<div class="sloode_search_post ws_mode">
	    <div class="sloode_search_post_left">
	    <?php
	    $thumb_url = $video['Thumbnail'];
				if($thumb_url=="")
				{
					 $thumb_url = plugins_url('images/video_placeholder.jpg',dirname(__FILE__));
				}
				?>
	<img src="<?php echo $thumb_url; ?>">
	    
	</div>
	
	<div class="sloode_search_post_title"><a href="<?php echo $video_page_link; ?>"><?php echo $video['Title']; ?></a></div>
	 <div class="sloode_search_post_date"><span class="posted_on">Posted on: </span><?php
	$timestamp = $video['Date'];
	$date = get_date_by_timestamp($timestamp);
	echo $date;
	 
	 ?></div>
	<div class="sloode_search_post_content"><?php
	$sloode_search_excerpt="";
	$sloode_search_content = $video['Description'];
	$sloode_search_excerpt = wp_trim_words($sloode_search_content,$word_limit);
	echo $sloode_search_excerpt;
	if($sloode_search_excerpt!="")
	{ echo "..."; }
	
	echo '<a href="'.$video_page_link.'" class="sloode_read_more">Read more</a>';
	$count++;
	?>
	</div>
	<input type="hidden" name="ws_search_last_post_id" class="ws_search_last_post_id" value="<?php echo $video_id; ?>">	
	</div>
	
	<?php
	}
    $wscount++;
    }
    //echo $count;
    if($count==0)
    {
	echo "<br/>No result on this page.<br/>";
	/// echo do_shortcode("[sloode_search_form]");
    }    
   }
    ?>
	<div class="sloode_search_page_no"><?php echo "Page: ".$page_no; ?></div>
	</div>
<?php
die;
}

add_action( 'wp_ajax_search_next', 'search_next_func' );
add_action( 'wp_ajax_nopriv_search_next', 'search_next_func' );
?>