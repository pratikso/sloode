<?php
/**
 * Adds Foo_Widget widget.
 */
class Most_viewed_videos extends WP_Widget {
	function __construct() {
		parent::__construct(
			'most_viewed_videos', 
			__( 'Sloode-Most viewed videos', 'text_domain' ), 
			array( 'description' => __( 'Show most viewed videos of selected category.', 'text_domain' ), ) 
		);
		 add_action( 'load-widgets.php', array(&$this, 'my_custom_load') );
	}
	 function my_custom_load() {    
        wp_enqueue_style( 'wp-color-picker' );        
        wp_enqueue_script( 'wp-color-picker' );    
    }
	public function widget( $args, $instance ) {
		$title_excerpt = !empty( $instance['title_excerpt'] ) ? $instance['title_excerpt'] : "10";
		$title_colour = !empty( $instance['title_colour'] ) ? $instance['title_colour'] : "";
		$date_colour = !empty( $instance['date_colour'] ) ? $instance['date_colour'] : "";
		$desc_colour = !empty( $instance['desc_colour'] ) ? $instance['desc_colour'] : "";
		$cat_colour = !empty( $instance['cat_colour'] ) ? $instance['cat_colour'] : "";
		
		$title_size = !empty( $instance['title_size'] ) ? $instance['title_size'] : "";
		$date_size = !empty( $instance['date_size'] ) ? $instance['date_size'] : "";
		$desc_size = !empty( $instance['desc_size'] ) ? $instance['desc_size'] : "";
		$cat_size = !empty( $instance['cat_size'] ) ? $instance['cat_size'] : "";
		
		$title_bold = ! empty( $instance['title_bold'] ) ? $instance['title_bold'] : "normal";
		$date_bold = ! empty( $instance['date_bold'] ) ? $instance['date_bold'] : "normal";
		$cat_bold = ! empty( $instance['cat_bold'] ) ? $instance['cat_bold'] : "normal";
		$desc_bold = ! empty( $instance['desc_bold'] ) ? $instance['desc_bold'] : "normal";
		
		$title_font = ! empty( $instance['title_font'] ) ? $instance['title_font'] : "";
		$date_font = ! empty( $instance['date_font'] ) ? $instance['date_font'] : "";
		$cat_font = ! empty( $instance['cat_font'] ) ? $instance['cat_font'] : "";
		$desc_font = ! empty( $instance['desc_font'] ) ? $instance['desc_font'] : "";
		
		
		$title_excerpt = !empty( $instance['title_excerpt'] ) ? $instance['title_excerpt'] : "10";
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		$current_time = current_time('d-M-Y');
		$timestamp = strtotime($current_time);
		//echo $timestamp;
		$day = 3600*24;
		$week = $day*7;
		$month = $day*30;
		$three_months= $month*3;
		$six_months = $month*6;
		//echo date('d/m/Y', $timestamp+$month);
		
		//die;
		$thumb_width =  $instance['thumb_width'];
		$thumb_height = $instance['thumb_height'];
		if($instance['from_date']=="month")
		{
			$date = date('m/d/Y', $timestamp-$month);
		}
		if($instance['from_date']=="week")
		{
			$date = date('m/d/Y', $timestamp-$week);
		}
		if($instance['from_date']=="three_month")
		{
			$date = date('m/d/Y', $timestamp-$three_months);
		}
		if($instance['from_date']=="six_month")
		{
			$date = date('m/d/Y', $timestamp-$six_months);
		}
		
		$cat_arr =get_kronopress_video_cat();
		$video_id_arr = array();
		$cat_esist = 0;
		echo "<ul class='video_list_widget recent_video_widget'>";
		foreach($cat_arr as $cat)
		{
		$cat_id = $cat['CategoryId'];
		$cat_name = $cat['CategoryName'];
		$numberposts= $instance['no_of_videos'.$cat_id];
		if($numberposts!="" && $numberposts!=0)
		{
			
			if($instance['reverce_order']=="on")
			{
			$order = 'asc';
			}
			else
			{
				$order = '';
			}
			$video_arr = krono_most_viewd_videos_arr($date,$cat_id,$order);
			$arr_count = count($video_arr);
			$count=1;
			foreach ($video_arr as $video)
			{
				$video_id = $video['VideoId'];
				if(!in_array($video_id,$video_id_arr )&&$count<=$numberposts)
				{
				echo "<li>";
				$video_id = $video['VideoId'];
				$thumb_url = $video['Thumbnail'];
				if($thumb_url=="")
				{
					 $thumb_url = plugins_url('images/video_placeholder.jpg',dirname(__FILE__));
				}
				
				$player_page_id = get_option('video_player_page_id');
				$video_page_link = add_query_arg( array('video_id' => $video_id ),  get_page_link($player_page_id) );
				?>
				
				
				<?php if($instance['show_thumb']=="on") {?>
				<div class="video_thumb_widget" style="width: <?php echo $thumb_width ; ?>px; height: <?php echo $thumb_height; ?>px;">
				<img src="<?php echo $thumb_url; ?>">
				</a>
				</div>
				<?php } ?>
				
				
				<?php if($instance['show_title']=="on") {
				?>
				<span class="video_title_widget">
				<?php
				echo '<a href="'.$video_page_link.'" style="color:'.$title_colour.';font-size:'.$title_size.';font-weight:'.$title_bold.';font-family:'.$title_font.'">';
				//echo $video['Title'];
				$title_limit = (int)$title_excerpt;
				echo wp_trim_words( $video['Title'], $title_limit, '..' );
				echo "</a>";
				?>
				</span>
				<br/>
				<?php } ?>
				
				<?php if($instance['show_category']=="on") {?>
				<span class="video_category_widget">
				<?php
				echo '<a href="'.$video_page_link.'" style="color:'.$cat_colour.';font-size:'.$cat_size.';font-weight:'.$cat_bold.';font-family:'.$cat_font.'">';
				echo $cat_name;
				echo "</a>";
				?>
				</span>
				<br/>
				<?php } ?>
				
				<?php if($instance['show_date']=="on") {?>
				<span class="video_date_widget" style="color:<?php echo $date_colour; ?>; font-size:<?php echo $date_size; ?>; font-weight:<?php echo $date_bold; ?>; font-family:<?php echo $date_font ?>">
				<?php
				$timestamp = $video['Date'];
				$timestamp = get_date_by_timestamp($timestamp);
				echo $timestamp;
				?>
				</span>
				<br/>
				<?php } ?>
				<?php if($instance['show_description']=="on") {
					$description = $video['Description'];
				if($description!="")
				{
				?>
				<span class="video_description_widget" style="color:<?php echo $desc_colour; ?>;font-size:<?php echo $desc_size; ?>; font-weight:<?php echo $desc_bold; ?>; font-family:<?php echo $desc_font ?>">
				<?php $description = $video['Description'];
				echo wp_trim_words( $description, 10, '..' );
				?>
				</span>
			<?php
			}
			} ?>
			
			<?php if($instance['show_hits']=="on") {?>
			<span class="video_hits_widget">
			<?php
			$video_detail = get_kronopress_video_by_videoid($video_id);
			$hits = $video_detail['hits'];
			echo "<strong>Visualizzazioni: </strong>".$hits;
			
			?>
			</span>
			
			<?php } ?>
			
			<div class="clear"></div>
			<?php
			echo "</li>";
			array_push($video_id_arr,$video_id);
				$count++;
				}
				if($count>$numberposts)
				{
					break;
				}
			}
			
		$cat_esist=1;	
		}
		
		
		}
		echo "</ul>";
		
		if(!$cat_esist)
		{
			echo "No category exist.";
		}
		echo $args['after_widget'];
	}

	
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Most Viewed videos', 'text_domain' );
		$title_excerpt = !empty( $instance['title_excerpt'] ) ? $instance['title_excerpt'] : "10";
		$thumb_width = ! empty( $instance['thumb_width'] ) ? $instance['thumb_width'] :"70" ;
		$thumb_height = ! empty( $instance['thumb_height'] ) ? $instance['thumb_height'] :"70" ;
		$show_thumb = ! empty( $instance['show_thumb'] ) ? $instance['show_thumb'] : "off";
		$show_date = ! empty( $instance['show_date'] ) ? $instance['show_date'] : "off";
		$show_title = ! empty( $instance['show_title'] ) ? $instance['show_title'] : "off";
		$show_category = ! empty( $instance['show_category'] ) ? $instance['show_category'] : "off";
		$show_description = ! empty( $instance['show_description'] ) ? $instance['show_description'] : "off";
		$show_hits = !empty( $instance['show_hits'] ) ? $instance['show_hits'] : "off";
		$reverce_order= !empty( $instance['reverce_order'] ) ? $instance['reverce_order'] : "off";
		$videos_from  = !empty( $instance['from_date'] ) ? $instance['from_date'] : "month";
		
		$title_colour = !empty( $instance['title_colour'] ) ? $instance['title_colour'] : "";
		$date_colour = !empty( $instance['date_colour'] ) ? $instance['date_colour'] : "";
		$desc_colour = !empty( $instance['desc_colour'] ) ? $instance['desc_colour'] : "";
		$cat_colour = !empty( $instance['cat_colour'] ) ? $instance['cat_colour'] : "";
		
		$title_size = !empty( $instance['title_size'] ) ? $instance['title_size'] : "";
		$date_size = !empty( $instance['date_size'] ) ? $instance['date_size'] : "";
		$desc_size = !empty( $instance['desc_size'] ) ? $instance['desc_size'] : "";
		$cat_size = !empty( $instance['cat_size'] ) ? $instance['cat_size'] : "";
		
		$title_bold = !empty( $instance['title_bold'] ) ? $instance['title_bold'] : "normal";
		$date_bold = !empty( $instance['date_bold'] ) ? $instance['date_bold'] : "normal";
		$cat_bold = !empty( $instance['cat_bold'] ) ? $instance['cat_bold'] : "normal";
		$desc_bold = !empty( $instance['desc_bold'] ) ? $instance['desc_bold'] : "normal";
		
		
		$title_font = !empty( $instance['title_font'] ) ? $instance['title_font'] : "";
		$date_font = !empty( $instance['date_font'] ) ? $instance['date_font'] : "";
		$cat_font = !empty( $instance['cat_font'] ) ? $instance['cat_font'] : "";
		$desc_font = !empty( $instance['desc_font'] ) ? $instance['desc_font'] : "";
		
		$cat_arr =get_kronopress_video_cat();
		$no_of_videos = array();
		foreach ($cat_arr as $cat)
		{
		$no_of_videos[$cat['CategoryId']] = ! empty( $instance['no_of_videos'.$cat['CategoryId']] ) ? $instance['no_of_videos'.$cat['CategoryId']] : 0;
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'thumb_size' ); ?>"><?php _e( 'Thumb size (Width &times; Height)' ); ?><span style="font-size:10px;"></label> 
		<div class="clear"></div>
		<input class="widget-textbox-small" id="<?php echo $this->get_field_id( 'thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" type="text" value="<?php echo esc_attr( $thumb_width ); ?>">px
		&times;
		<input class="widget-textbox-small" id="<?php echo $this->get_field_id( 'thumb_height' ); ?>" name="<?php echo $this->get_field_name( 'thumb_height' ); ?>" type="text" value="<?php echo esc_attr( $thumb_height ); ?>">px
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'title_excerpt' ); ?>"><strong><?php _e( 'Title Word limit:' ); ?></strong></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'title_excerpt' ); ?>" type="number" value="<?php echo esc_attr( $title_excerpt ); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'from_date' ); ?>"><?php _e( 'Show videos from'); ?></label> 
		<div class="clear"></div>
		<input type="radio" id="<?php echo $this->get_field_id( 'from_date' ); ?>"  name="<?php echo $this->get_field_name('from_date'); ?>" value="week" <?php checked($videos_from,"week");?> > Posted in last 7 days
		<br><input type="radio" id="<?php echo $this->get_field_id( 'from_date' ); ?>"  name="<?php echo $this->get_field_name('from_date'); ?>" value="month" <?php checked($videos_from,"month");?>> Posted in last 30 days
		<br><input type="radio" id="<?php echo $this->get_field_id( 'from_date' ); ?>"  name="<?php echo $this->get_field_name('from_date'); ?>" value="three_month" <?php checked($videos_from,"three_month");?>> Posted in last 3 months
		<br><input type="radio" id="<?php echo $this->get_field_id( 'from_date' ); ?>"  name="<?php echo $this->get_field_name('from_date'); ?>" value="six_month" <?php checked($videos_from,"six_month");?>> Posted in last 6 months
		</p>
		
		
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'reverce_order' ); ?>"  name="<?php echo $this->get_field_name('reverce_order'); ?>" value="on" <?php checked($reverce_order,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'reverce_order' ); ?>"><?php _e( 'Reverce Order (less viewed first)' ); ?></label> 
		</p>
		
		
		
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_title' ); ?>"  name="<?php echo $this->get_field_name('show_title'); ?>" value="on" <?php checked($show_title,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_title' ); ?>"><?php _e( 'Show Title' ); ?></label> 
		</p>
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_category' ); ?>"  name="<?php echo $this->get_field_name('show_category'); ?>" value="on" <?php checked($show_category,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_category' ); ?>"><?php _e( 'Show Category' ); ?></label> 
		</p>
		
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_thumb' ); ?>"  name="<?php echo $this->get_field_name('show_thumb'); ?>" value="on" <?php checked($show_thumb,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_thumb' ); ?>"><?php _e( 'Show Thumbnails' ); ?></label> 
		</p>
		
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_date' ); ?>"  name="<?php echo $this->get_field_name('show_date'); ?>" value="on" <?php checked($show_date,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Show Date' ); ?></label> 
		</p>
		
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_description' ); ?>"  name="<?php echo $this->get_field_name('show_description'); ?>" value="on" <?php checked($show_description,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_description' ); ?>"><?php _e( 'Show Description' ); ?></label> 
		</p>
		
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_hits' ); ?>"  name="<?php echo $this->get_field_name('show_hits'); ?>" value="on" <?php checked($show_hits,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_hits' ); ?>"><?php _e( 'Show Hit Count' ); ?></label> 
		</p>
		<p>
		
		<!---------------------->
		<p><label><strong><?php _e( 'Font Setting:' ); ?></strong></label> <br/>
		<table>
		<tr>	
		<td>Title Font:</td>
		<td>
		<select id="<?php echo $this->get_field_id( 'title_font' ); ?>" name="<?php echo $this->get_field_name('title_font'); ?>">	
		<option value="" <?php selected($title_font,"");  ?>> -Default- </option>
		<option value="arial" <?php selected($title_font,"arial"); ?>> Ariel </option>
		<option value="sans-serif" <?php selected($title_font,"sans-serif");  ?>> Sans Serif </option>
		<option value="serif" <?php selected($title_font,"serif");  ?>> Serif </option>
		<option value="open sans" <?php selected($title_font,"open sans");  ?>> Open Sans </option>
		<option value="times new roman" <?php selected($title_font,"times new roman");  ?>> Times New Roman </option>
		<option value="helvetica" <?php selected($title_font,"helvetica");  ?>> Helvtica </option>
		<option value="verdana" <?php selected($title_font,"verdana");  ?>> Verdana </option>
		<option value="tahoma" <?php selected($title_font,"tahoma");  ?>> Tahoma </option>
		</select>
			
		</td>
		
		
		</tr>
		<tr>	
		<td>Category Font:</td> <td>
		<select id="<?php echo $this->get_field_id( 'cat_font' ); ?>" name="<?php echo $this->get_field_name('cat_font'); ?>">	
		<option value="" <?php selected($cat_font,"");  ?>> -Default- </option>
		<option value="arial" <?php selected($cat_font,"arial"); ?>> Ariel </option>
		<option value="sans-serif" <?php selected($cat_font,"sans-serif");  ?>> Sans Serif </option>
		<option value="serif" <?php selected($cat_font,"serif");  ?>> Serif </option>
		<option value="open sans" <?php selected($cat_font,"open sans");  ?>> Open Sans </option>
		<option value="times new roman" <?php selected($cat_font,"times new roman");  ?>> Times New Roman </option>
		<option value="helvetica" <?php selected($cat_font,"helvetica");  ?>> Helvtica </option>
		<option value="verdana" <?php selected($cat_font,"verdana");  ?>> Verdana </option>
		<option value="tahoma" <?php selected($cat_font,"tahoma");  ?>> Tahoma </option>
		</select>

		
		</td>
		
		</tr>
		<tr>	
		<td>Description size:</td> <td>
		<select id="<?php echo $this->get_field_id( 'desc_font' ); ?>" name="<?php echo $this->get_field_name('desc_font'); ?>">	
		<option value="" <?php selected($desc_font,"");  ?>> -Default- </option>
		<option value="arial" <?php selected($desc_font,"arial"); ?>> Ariel </option>
		<option value="sans-serif" <?php selected($desc_font,"sans-serif");  ?>> Sans Serif </option>
		<option value="serif" <?php selected($desc_font,"serif");  ?>> Serif </option>
		<option value="open sans" <?php selected($desc_font,"open sans");  ?>> Open Sans </option>
		<option value="times new roman" <?php selected($desc_font,"times new roman");  ?>> Times New Roman </option>
		<option value="helvetica" <?php selected($desc_font,"helvetica");  ?>> Helvtica </option>
		<option value="verdana" <?php selected($desc_font,"verdana");  ?>> Verdana </option>
		<option value="tahoma" <?php selected($desc_font,"tahoma");  ?>> Tahoma </option>
		</select>
		
		</td>
		
		
		</tr>
		<tr>	
		<td>Date size:</td> <td>
		<select id="<?php echo $this->get_field_id( 'date_font' ); ?>" name="<?php echo $this->get_field_name('date_font'); ?>">	
		<option value="" <?php selected($date_font,"");  ?>> -Default- </option>
		<option value="arial" <?php selected($date_font,"arial"); ?>> Ariel </option>
		<option value="sans-serif" <?php selected($date_font,"sans-serif");  ?>> Sans Serif </option>
		<option value="serif" <?php selected($date_font,"serif");  ?>> Serif </option>
		<option value="open sans" <?php selected($date_font,"open sans");  ?>> Open Sans </option>
		<option value="times new roman" <?php selected($date_font,"times new roman");  ?>> Times New Roman </option>
		<option value="helvetica" <?php selected($date_font,"helvetica");  ?>> Helvtica </option>
		<option value="verdana" <?php selected($date_font,"verdana");  ?>> Verdana </option>
		<option value="tahoma" <?php selected($date_font,"tahoma");  ?>> Tahoma </option>
		</select>
		
		</td>
		
		</tr>
		</table>
		</p>
		<!-------------------------->
		<p><label><strong><?php _e( 'Colour Setting:' ); ?></strong></label> <br/>
		<table>
		<tr>	
		<td>Title Colour:</td> <td><input type="text" class="my-color-picker" id="<?php echo $this->get_field_id( 'title_colour' ); ?>"  name="<?php echo $this->get_field_name('title_colour'); ?>" value="<?php echo $title_colour; ?>" /></td>	
		</tr>
		<tr>	
		<td>Category Colour:</td> <td><input type="text" class="my-color-picker" id="<?php echo $this->get_field_id( 'cat_colour' ); ?>"  name="<?php echo $this->get_field_name('cat_colour'); ?>" value="<?php echo $cat_colour; ?>" /></td>	
		</tr>
		<tr>	
		<td>Description Colour:</td> <td><input type="text" class="my-color-picker" id="<?php echo $this->get_field_id( 'desc_colour' ); ?>"  name="<?php echo $this->get_field_name('desc_colour'); ?>" value="<?php echo $desc_colour; ?>" /></td>
		</tr>
		<tr>	
		<td>Date Colour:</td> <td><input type="text" class="my-color-picker" id="<?php echo $this->get_field_id( 'date_colour' ); ?>"  name="<?php echo $this->get_field_name('date_colour'); ?>" value="<?php echo $date_colour; ?>" /></td>
		</tr>
		</table>
		</p>
		
		<p><label><strong><?php _e( 'Font Size Setting:' ); ?></strong></label> <br/>
		<table>
		<tr>	
		<td>Title size:</td>
		<td>
		<select id="<?php echo $this->get_field_id( 'title_size' ); ?>" name="<?php echo $this->get_field_name('title_size'); ?>">	
		<option value="" <?php selected($title_size,"");  ?>> -Select- </option>
		<option value="12px" <?php selected($title_size,"10px");  ?>> 10px </option>
		<option value="12px" <?php selected($title_size,"12px");  ?>> 12px </option>
		<option value="14px" <?php selected($title_size,"14px");  ?>> 14px </option>
		<option value="16px" <?php selected($title_size,"16px");  ?>> 16px </option>
		<option value="18px" <?php selected($title_size,"18px");  ?>> 18px </option>
		<option value="20px" <?php selected($title_size,"20px");  ?>> 20px </option>
		<option value="22px" <?php selected($title_size,"22px");  ?>> 22px </option>
		<option value="24px" <?php selected($title_size,"24px");  ?>> 24px </option>
		</select>
			
		</td>
		<td>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'title_bold' ); ?>"  name="<?php echo $this->get_field_name('title_bold'); ?>" value="bold" <?php checked($title_bold,"bold");  ?> > Bold
		</td>
		
		</tr>
		<tr>	
		<td>Category size:</td> <td>
		<select id="<?php echo $this->get_field_id( 'cat_size' ); ?>" name="<?php echo $this->get_field_name('cat_size'); ?>">	
		<option value="" <?php selected($cat_size,"");  ?>> -Select- </option>
		<option value="12px" <?php selected($cat_size,"10px");  ?>> 10px </option>
		<option value="12px" <?php selected($cat_size,"12px");  ?>> 12px </option>
		<option value="14px" <?php selected($cat_size,"14px");  ?>> 14px </option>
		<option value="16px" <?php selected($cat_size,"16px");  ?>> 16px </option>
		<option value="18px" <?php selected($cat_size,"18px");  ?>> 18px </option>
		<option value="20px" <?php selected($cat_size,"20px");  ?>> 20px </option>
		<option value="22px" <?php selected($cat_size,"22px");  ?>> 22px </option>
		<option value="24px" <?php selected($cat_size,"24px");  ?>> 24px </option>
		</select>

		
		</td>
		<td>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'cat_bold' ); ?>"  name="<?php echo $this->get_field_name('cat_bold'); ?>" value="bold" <?php checked($cat_bold,"bold");  ?> > Bold
		</td>
		</tr>
		<tr>	
		<td>Description size:</td> <td>
		<select id="<?php echo $this->get_field_id( 'desc_size' ); ?>" name="<?php echo $this->get_field_name('desc_size'); ?>">	
		<option value="" <?php selected($desc_size,"");  ?>> -Select- </option>
		<option value="12px" <?php selected($desc_size,"10px");  ?>> 10px </option>
		<option value="12px" <?php selected($desc_size,"12px");  ?>> 12px </option>
		<option value="14px" <?php selected($desc_size,"14px");  ?>> 14px </option>
		<option value="16px" <?php selected($desc_size,"16px");  ?>> 16px </option>
		<option value="18px" <?php selected($desc_size,"18px");  ?>> 18px </option>
		<option value="20px" <?php selected($desc_size,"20px");  ?>> 20px </option>
		<option value="22px" <?php selected($desc_size,"22px");  ?>> 22px </option>
		<option value="24px" <?php selected($desc_size,"24px");  ?>> 24px </option>
		</select>
		
		</td>
		<td>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'desc_bold' ); ?>"  name="<?php echo $this->get_field_name('desc_bold'); ?>" value="bold" <?php checked($desc_bold,"bold");  ?> > Bold
		</td>
		
		</tr>
		<tr>	
		<td>Date size:</td> <td>
		<select id="<?php echo $this->get_field_id( 'date_size' ); ?>" name="<?php echo $this->get_field_name('date_size'); ?>">	
		<option value="" <?php selected($date_size,"");  ?>> -Select- </option>
		<option value="12px" <?php selected($date_size,"10px");  ?>> 10px </option>
		<option value="12px" <?php selected($date_size,"12px");  ?>> 12px </option>
		<option value="14px" <?php selected($date_size,"14px");  ?>> 14px </option>
		<option value="16px" <?php selected($date_size,"16px");  ?>> 16px </option>
		<option value="18px" <?php selected($date_size,"18px");  ?>> 18px </option>
		<option value="20px" <?php selected($date_size,"20px");  ?>> 20px </option>
		<option value="22px" <?php selected($date_size,"22px");  ?>> 22px </option>
		<option value="24px" <?php selected($date_size,"24px");  ?>> 24px </option>
		</select>
		
		</td>
		<td>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'date_bold' ); ?>"  name="<?php echo $this->get_field_name('date_bold'); ?>" value="bold" <?php checked($date_bold,"bold");  ?> > Bold
		</td>
		
		</tr>
		</table>
		</p>
		
		
		<table>
		<tr>
		<th><?php _e( 'Category:' ); ?></th>	
		<th><?php _e( 'No. of videos:' ); ?></th>	
		</tr>
		<?php
		foreach ($cat_arr as $cat)
		{
		?>
		<tr>
		<td><?php echo $cat['CategoryName']; ?></td>  <td><input type="number" value="<?php echo $no_of_videos[$cat['CategoryId']];?>" id="<?php echo $this->get_field_id( 'no_of_videos'.$cat['CategoryId'] ); ?>" name="<?php echo $this->get_field_name( 'no_of_videos'.$cat['CategoryId'] ); ?>"></td>
		</tr>
		<?php } ?>
		</table>
		
		</p>
		<?php 
	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['title_excerpt'] = ( !empty( $new_instance['title_excerpt'] ) ) ? strip_tags( $new_instance['title_excerpt'] ) : '10';
		$instance['show_thumb'] = $new_instance['show_thumb'];
		$instance['show_title'] = $new_instance['show_title'];
		$instance['show_category'] = $new_instance['show_category'];
		$instance['show_date'] = $new_instance['show_date'];
		$instance['show_description'] = $new_instance['show_description'];
		$instance['show_hits'] = $new_instance['show_hits'];
		$instance['thumb_height'] = $new_instance['thumb_height'];
		$instance['thumb_width'] = $new_instance['thumb_width'];
		$instance['from_date'] = $new_instance['from_date'];
		$instance['reverce_order'] = $new_instance['reverce_order'];
		
		$instance['title_colour'] = $new_instance['title_colour'];
		$instance['date_colour'] = $new_instance['date_colour'];
		$instance['cat_colour'] = $new_instance['cat_colour'];
		$instance['desc_colour'] = $new_instance['desc_colour'];
		
		$instance['title_size'] = $new_instance['title_size'];
		$instance['date_size'] = $new_instance['date_size'];
		$instance['desc_size'] = $new_instance['desc_size'];
		$instance['cat_size'] = $new_instance['cat_size'];
		
		$instance['title_bold'] = $new_instance['title_bold'];
		$instance['desc_bold'] = $new_instance['desc_bold'];
		$instance['cat_bold'] = $new_instance['cat_bold'];
		$instance['date_bold'] = $new_instance['date_bold'];
		
		$instance['title_font'] = $new_instance['title_font'];
		$instance['desc_font'] = $new_instance['desc_font'];
		$instance['cat_font'] = $new_instance['cat_font'];
		$instance['date_font'] = $new_instance['date_font'];
		
		$cat_arr =get_kronopress_video_cat();
		foreach($cat_arr as $cat)
		{
			$instance['no_of_videos'.$cat['CategoryId']] = $new_instance['no_of_videos'.$cat['CategoryId']];
		}
		return $instance;
	}
} 
function register_most_viewed_video_widget() {
    register_widget( 'Most_viewed_videos' );
}
add_action( 'widgets_init', 'register_most_viewed_video_widget' );
?>
