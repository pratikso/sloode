<?php
/**
 * Adds Foo_Widget widget.
 */
class videos_by_multi_cat_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'videos_by_multi_cat', // Base ID
			__( 'Sloode-Videos by Multiple Category', 'text_domain' ), // Name
			array( 'description' => __( 'Show videos of selected category.', 'text_domain' ), ) // Args
		);
		 add_action( 'load-widgets.php', array(&$this, 'my_custom_load') );
	}

	 function my_custom_load() {    
        wp_enqueue_style( 'wp-color-picker' );        
        wp_enqueue_script( 'wp-color-picker' );    
    }
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	
	public function widget( $args, $instance ) {
		
		$thumb_width =  $instance['thumb_width'];
		$thumb_height = $instance['thumb_height'];
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
		$no_of_total_videos = ! empty( $instance['no_of_total_videos'] ) ? $instance['no_of_total_videos'] : "10";
		$numberposts = $no_of_total_videos;
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			
			if(! empty( $instance['title_link'] ))
			{
			echo $args['before_title'] ."<a href='".$instance['title_link']."'>". apply_filters( 'widget_title', $instance['title'] )."</a>". $args['after_title'];
			}
			else
			{
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
				
			}
			
		}
				
		
		$cat_arr =get_kronopress_video_cat();
		$cat_id_arr = array();
		$cat_name_arr = array();
		foreach($cat_arr as $cat)
		{
			$cat_id = $cat['CategoryId'];
			if($instance['no_of_videos'.$cat_id]=="on")
			{
			array_push($cat_id_arr,$cat['CategoryId']);
			array_push($cat_name_arr,$cat['CategoryName']);
			}
			
		}
		if(!empty($cat_id_arr))
		{
		$video_id_arr = array();
		$cat_esist = 0;
		$video_arr = get_latest_videos_by_multi_cat($cat_id_arr,40);
		$arr_count = count($video_arr);
			$count=1;
			if($arr_count==0)
			{
				echo "No video found!";
			}
			else
			{

				echo "<ul class='video_list_widget recent_video_widget'>";
			
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
				<a href="<?php echo $video_page_link; ?>">
				<img src="<?php echo $thumb_url; ?>">
				</a>
				</div>
				<?php } ?>
				
				
				<?php if($instance['show_title']=="on") {?>
				<span class="video_title_widget">
				<?php
				//echo "<a href='".$video_page_link."' style='color:".$title_colour.";font-size:".$title_size.">";
				echo '<a href="'.$video_page_link.'" style="color:'.$title_colour.';font-size:'.$title_size.';font-weight:'.$title_bold.';font-family:'.$title_font.'">';
				echo $video['Title'];
				echo "</a>";
				?>
				</span>
				<br/>
				<?php } ?>
				
				<?php if($instance['show_category']=="on") {?>
				<span class="video_category_widget">
				<?php
				//echo "<a href='".$video_page_link."' style='color:".$cat_colour."'>";
				echo '<a href="'.$video_page_link.'" style="color:'.$cat_colour.';font-size:'.$cat_size.';font-weight:'.$cat_bold.';font-family:'.$cat_font.'">';
				//echo $cat_name;
				echo implode(", ",$cat_name_arr);
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
				if($count>$no_of_total_videos)
				{
					break;
				}
			}
			
		
		echo "</ul>";
		}	
		}
		else
		{
			echo "No category selected.";
		}
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Videos by category', 'text_domain' );
		$title_link = ! empty( $instance['title_link'] ) ? $instance['title_link'] : "";
		$thumb_width = ! empty( $instance['thumb_width'] ) ? $instance['thumb_width'] :"70" ;
		$thumb_height = ! empty( $instance['thumb_height'] ) ? $instance['thumb_height'] :"70" ;
		$show_thumb = ! empty( $instance['show_thumb'] ) ? $instance['show_thumb'] : "off";
		$show_date = ! empty( $instance['show_date'] ) ? $instance['show_date'] : "off";
		$show_title = ! empty( $instance['show_title'] ) ? $instance['show_title'] : "off";
		$show_category = ! empty( $instance['show_category'] ) ? $instance['show_category'] : "off";
		$show_description = ! empty( $instance['show_description'] ) ? $instance['show_description'] : "off";
		$show_hits = !empty( $instance['show_hits'] ) ? $instance['show_hits'] : "off";
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
		
		
		$cat_arr =get_kronopress_video_cat();
		$no_of_videos = array();
		foreach ($cat_arr as $cat)
		{
		$no_of_videos[$cat['CategoryId']] = ! empty( $instance['no_of_videos'.$cat['CategoryId']] ) ? $instance['no_of_videos'.$cat['CategoryId']] : "off";
		//here $no_of_videos variable used to check that whether to show video from this category or not.
		// dont confused with its name
		}
		$no_of_total_videos = ! empty( $instance['no_of_total_videos'] ) ? $instance['no_of_total_videos'] : "10";
		
		?>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><strong><?php _e( 'Title:' ); ?></strong></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'title_link' ); ?>"><strong><?php _e( 'Title Link:' ); ?></strong></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title_link' ); ?>" name="<?php echo $this->get_field_name( 'title_link' ); ?>" type="text" value="<?php echo esc_attr( $title_link ); ?>">
		</p>
		
		
		<p>
		<label for="<?php echo $this->get_field_id( 'thumb_size' ); ?>"><strong><?php _e( 'Thumb size (Width &times; Height)' ); ?></strong><span style="font-size:10px;"></label> 
		<div class="clear"></div>
		<input class="widget-textbox-small" id="<?php echo $this->get_field_id( 'thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" type="text" value="<?php echo esc_attr( $thumb_width ); ?>">px
		&times;
		<input class="widget-textbox-small" id="<?php echo $this->get_field_id( 'thumb_height' ); ?>" name="<?php echo $this->get_field_name( 'thumb_height' ); ?>" type="text" value="<?php echo esc_attr( $thumb_height ); ?>">px
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'no_of_total_videos' ); ?>"><strong><?php _e( 'No of total videos:' ); ?></strong></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'no_of_total_videos' ); ?>" name="<?php echo $this->get_field_name( 'no_of_total_videos' ); ?>" type="text" value="<?php echo $no_of_total_videos; ?>">
		</p>
		
		
		
		<div class="widget_chbx" >
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_title' ); ?>"  name="<?php echo $this->get_field_name('show_title'); ?>" value="on" <?php checked($show_title,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_title' ); ?>"><?php _e( 'Show Title' ); ?></label> 
		</div>
		<div class="widget_chbx right_chbx">
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_category' ); ?>"  name="<?php echo $this->get_field_name('show_category'); ?>" value="on" <?php checked($show_category,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_category' ); ?>"><?php _e( 'Show Category' ); ?></label> 
		</div>
		
		<div class="widget_chbx" >
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_thumb' ); ?>"  name="<?php echo $this->get_field_name('show_thumb'); ?>" value="on" <?php checked($show_thumb,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_thumb' ); ?>"><?php _e( 'Show Thumbnails' ); ?></label> 
		</div>
		
		<div class="widget_chbx right_chbx">
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_date' ); ?>"  name="<?php echo $this->get_field_name('show_date'); ?>" value="on" <?php checked($show_date,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Show Date' ); ?></label> 
		</div>
		
		<div class="widget_chbx" >
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_description' ); ?>"  name="<?php echo $this->get_field_name('show_description'); ?>" value="on" <?php checked($show_description,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_description' ); ?>"><?php _e( 'Show Description' ); ?></label> 
		</div>
		
		<div class="widget_chbx right_chbx">
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_hits' ); ?>"  name="<?php echo $this->get_field_name('show_hits'); ?>" value="on" <?php checked($show_hits,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_hits' ); ?>"><?php _e( 'Show Hit Count' ); ?></label> 
		</div>
		<div class="clear"></div>
		
		
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
		
		
		<p>
		<div class="wid_cat_list">
		<table>
		<tr>
		<th><?php _e( 'Category:' ); ?></th>	
		<th><?php //_e( 'No. of videos:' ); ?></th>	
		</tr>
		<?php
		foreach ($cat_arr as $cat)
		{
		?>
		<tr>
		<td><?php echo $cat['CategoryName'];
		//echo $no_of_videos[$cat['CategoryId']];
		?></td>  <td><input type="checkbox" value="on" id="<?php echo $this->get_field_id( 'no_of_videos'.$cat['CategoryId'] ); ?>" name="<?php echo $this->get_field_name( 'no_of_videos'.$cat['CategoryId'] ); ?>" <?php checked($no_of_videos[$cat['CategoryId']],"on");  ?>></td>
		</tr>
		
		<?php } ?>
		
		</table>
		</div>
		</p>
		 <script type='text/javascript'>
            jQuery(document).ready(function($) {
                $('.my-color-picker').wpColorPicker();
            });
        </script>
		
		
		
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 **/
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['title_link'] = ( ! empty( $new_instance['title_link'] ) ) ? strip_tags( $new_instance['title_link'] ) : '';
		$instance['show_thumb'] = $new_instance['show_thumb'];
		$instance['show_title'] = $new_instance['show_title'];
		$instance['show_category'] = $new_instance['show_category'];
		$instance['show_date'] = $new_instance['show_date'];
		$instance['show_description'] = $new_instance['show_description'];
		$instance['show_hits'] = $new_instance['show_hits'];
		$instance['thumb_height'] = $new_instance['thumb_height'];
		$instance['thumb_width'] = $new_instance['thumb_width'];
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
		
		$instance['no_of_total_videos'] = $new_instance['no_of_total_videos'];
		
		return $instance;
	}

} 

// register Recent_videos_widget
function register_videos_by_multi_cat_widget() {
    register_widget( 'videos_by_multi_cat_widget' );
}
add_action( 'widgets_init', 'register_videos_by_multi_cat_widget' );

?>
