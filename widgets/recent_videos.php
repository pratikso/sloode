<?php
/**
 * Adds Foo_Widget widget.
 */
class Recent_videos_widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'recent_videos', 
			__( 'Sloode-Recent Videos', 'text_domain' ), 
			array( 'description' => __( 'Show Recent Videos', 'text_domain' ), ) 
		);
	}
	public function widget( $args, $instance ) {
		$title_excerpt = !empty( $instance['title_excerpt'] ) ? $instance['title_excerpt'] : "10";
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
		if ( ! empty( $instance['no_of_videos'] ) ) {
		$numberposts = $instance['no_of_videos'] ;
		}
		else
		{
		$numberposts = 5;	
		}
		$thumb_width =  $instance['thumb_width'];
		$thumb_height = $instance['thumb_height'];
	$video_arr = krono_latest_videos_arr(400);
    	$video_id_arr = array();
	$count=1;
	echo "<ul class='video_list_widget recent_video_widget'>";
	foreach ($video_arr as $video)
	{
		$video_id = $video['VideoId'];
		if(!in_array($video_id,$video_id_arr ) && $count<=$numberposts)
			{
			echo "<li>";
			
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
				
			<span class="video_title_widget">
			<?php
			echo "<a href='".$video_page_link."'>";
			//echo $video['Title'];
			$title_limit = (int)$title_excerpt;
			echo wp_trim_words( $video['Title'], $title_limit, '..' );
			echo "</a>";
			?>
			</span>
			<br/>
			
			
			<?php if($instance['show_date']=="on") {?>
			<span class="video_date_widget">
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
			<span class="video_description_widget">
			<?php 
			echo wp_trim_words( $description, 10, '..' );
			?>
			</span>
			
			<br/>
			
			
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
	echo "</ul>";
		
		echo $args['after_widget'];
	}

	
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Videos', 'text_domain' );
		$title_link = ! empty( $instance['title_link'] ) ? $instance['title_link'] : "";
		$title_excerpt = !empty( $instance['title_excerpt'] ) ? $instance['title_excerpt'] : "10";
		$thumb_width = ! empty( $instance['thumb_width'] ) ? $instance['thumb_width'] :"70" ;
		$thumb_height = ! empty( $instance['thumb_height'] ) ? $instance['thumb_height'] :"70" ;
		$no_of_videos = ! empty( $instance['no_of_videos'] ) ? $instance['no_of_videos'] : 5;
		$show_thumb = ! empty( $instance['show_thumb'] ) ? $instance['show_thumb'] : "off";
		$show_date = ! empty( $instance['show_date'] ) ? $instance['show_date'] : "off";
		$show_description = !empty( $instance['show_description'] ) ? $instance['show_description'] : "off";
		$show_hits = !empty( $instance['show_hits'] ) ? $instance['show_hits'] : "off";
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'title_link' ); ?>"><strong><?php _e( 'Title Link:' ); ?></strong></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title_link' ); ?>" name="<?php echo $this->get_field_name( 'title_link' ); ?>" type="text" value="<?php echo esc_attr( $title_link ); ?>">
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
		<label for="<?php echo $this->get_field_id( 'no_of_videos' ); ?>"><?php _e( 'No. of videos:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'no_of_videos' ); ?>" name="<?php echo $this->get_field_name( 'no_of_videos' ); ?>" type="number" value="<?php echo esc_attr( $no_of_videos); ?>">
		</p>
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_thumb' ); ?>"  name="<?php echo $this->get_field_name('show_thumb'); ?>" value="on" <?php checked($show_thumb,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_thumb' ); ?>"><?php _e( 'Show Thumbnails:' ); ?></label> 
		</p>
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_date' ); ?>"  name="<?php echo $this->get_field_name('show_date'); ?>" value="on" <?php checked($show_date,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Show Date:' ); ?></label> 
		</p>
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_description' ); ?>"  name="<?php echo $this->get_field_name('show_description'); ?>" value="on" <?php checked($show_description,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_description' ); ?>"><?php _e( 'Show Description:' ); ?></label> 
		</p>
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_hits' ); ?>"  name="<?php echo $this->get_field_name('show_hits'); ?>" value="on" <?php checked($show_hits,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_hits' ); ?>"><?php _e( 'Show Hit Count:' ); ?></label> 
		</p>
		<?php 	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['title_link'] = ( ! empty( $new_instance['title_link'] ) ) ? strip_tags( $new_instance['title_link'] ) : '';
		$instance['title_excerpt'] = ( !empty( $new_instance['title_excerpt'] ) ) ? strip_tags( $new_instance['title_excerpt'] ) : '10';
		$instance['no_of_videos'] = ( ! empty( $new_instance['no_of_videos'] ) ) ? strip_tags( $new_instance['no_of_videos'] ) : '';
		$instance['show_thumb'] = $new_instance['show_thumb'];
		$instance['show_date'] = $new_instance['show_date'];
		$instance['show_description'] = $new_instance['show_description'];
		$instance['show_hits'] = $new_instance['show_hits'];
		$instance['thumb_height'] = $new_instance['thumb_height'];
		$instance['thumb_width'] = $new_instance['thumb_width'];
		return $instance;
	}

} 
function register_recent_video_widget() {
    register_widget( 'Recent_videos_widget' );
}
add_action( 'widgets_init', 'register_recent_video_widget' );

?>
