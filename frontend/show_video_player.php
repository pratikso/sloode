<?php
function show_video_player_func( $atts ) {
	ob_start();
    $a = shortcode_atts( array(
      /*  'width' => '80%',
	 'height' => 400,*/
       'width' => get_option('krono_player_width'),
	 'height' => get_option('krono_player_height'),
      
	 'video_id' =>"",
	 'hit_count' => "yes",
	  'show_cat' => 'yes',
	 'template' =>"style1",
	  'show_date'=> 'no'
         ), $atts );
    $width = $a['width'];
    $height = $a['height'];
    $show_hits = $a['hit_count'];
    $template = $a['template'];
    $show_date = $a['show_date'];
    $show_cat = $a['show_cat'];
   if($a['video_id']=="")
   {
    $video_id = $_REQUEST['video_id'];
   }
   else
   {
     $video_id = $a['video_id'];
   }
  
    $video_detail_arr = get_kronopress_video_by_videoid($video_id);
  
  $cat_list= "";
			$cat_id_arr= get_categories_by_video_id($video_id);
			foreach ($cat_id_arr as $cat_id)
			{
			$cat_list.= get_category_name_by_cat_id($cat_id).", ";
			}
			$cat_list= substr($cat_list, 0,-2);
			
	
	$embed_code= $video_detail_arr['EmbedURL'];
	 preg_match('/(src=["\'](.*?)["\'])/',  $embed_code, $match);  //find src="X" or src='X'
	   $split = preg_split('/["\']/', $match[0]); // split by quotes
	    $embed_url = $split[1];
	 	 
	  echo '<iframe src="'.$embed_url.'" width="'.$width.'" height="'.$height.'" webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen="true"></iframe>';
	
	if( $template =="style2")
	{
		 echo '<div class="krono_video_description template_2">';
		  echo "<span class='kp_title'><h2>".$video_detail_arr['Title']."</h2></span>";
		  echo "<br/>".$video_detail_arr['Description'];
		
		 
		 echo '<div class="info_footer">';
		 
		  if($show_cat=="yes" || $show_cat=="YES")
		 {
		  echo '<div class="info_cat"><span>Categoria:</span> '.$cat_list.'</div>';
		 }
		 
		 echo '<ul>';
		if($show_hits=="yes")
		{
		      $hit_count = $video_detail_arr['hits'];
		      if($hit_count=="" ||$hit_count=="null")
		      {
		      $hit_count = 0;
		      }
		      
		      echo "<li>".$hit_count." Visualizzazioni</li>";
		    if( $show_date=="yes" || $show_date=="YES" || $show_date=="Yes")
		    {
		      $timestamp = $video_detail_arr['Date'];
		  
				$timestamp = get_date_by_timestamp($timestamp);
				 echo "<li>".$timestamp."</li>";
		    } 
		      
		}
		echo '</ul>';
		 echo "</div><div class='clear'></div>";
		 echo "</div>";
   
	}
	else
	{
		 echo '<div class="krono_video_description">';
		  echo "<span class='kp_title'>".$video_detail_arr['Title']."</span>";
		//  echo "<br/><strong>Category: </strong>".$video_detail_arr['CategoryName'];
		  if( $show_date=="yes" || $show_date=="YES" || $show_date=="Yes")
		    {
		      $timestamp = $video_detail_arr['Date'];
				$timestamp = get_date_by_timestamp($timestamp);
				 echo "<br/><strong>Date: </strong>".$timestamp;
		    } 
		 if($show_cat=="yes" || $show_cat=="YES")
		 {
		  echo "<br/><strong>Categoria: </strong>".$cat_list;
		 }
		  echo "<br/><strong>Descrizione: </strong>".$video_detail_arr['Description'];
		if($show_hits=="yes")
		{
		      $hit_count = $video_detail_arr['hits'];
		      if($hit_count=="" ||$hit_count=="null")
		      {
		      $hit_count = 0;
		      }
		      
		      echo "<br/><strong>Visualizzazioni: </strong>".$hit_count;
		      
		}
		
		 echo "</div>";
		
	}
    return ob_get_clean();	
}
add_shortcode( 'show_video_player', 'show_video_player_func' );

?>