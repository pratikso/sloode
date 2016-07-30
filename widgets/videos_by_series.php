<?php
/**
 * Adds Foo_Widget widget.
 */
class videos_by_series_widget extends WP_Widget {

	
	function __construct() {
		parent::__construct(
			'videos_by_series', // Base ID
			__( 'Sloode-Videos by Series', 'text_domain' ), // Name
			array( 'description' => __( 'Show videos of selected series.', 'text_domain' ), ) // Args
		);
	}
	public function widget( $args, $instance ) {
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
				
		$thumb_width =  $instance['thumb_width'];
		$thumb_height = $instance['thumb_height'];
		$series_arr =krono_series_list_arr();
		$video_id_arr = array();
		
		echo "<ul class='video_list_widget recent_video_widget'>";
		foreach ($series_arr as $series)
		{
		$series_id = $series['SeriesId'];
		$numberposts= $instance['no_of_videos'.$series_id];
		if($numberposts!="" && $numberposts!=0)
		{
			//$video_arr = videos_by_cat_arr($cat);
			$video_arr = krono_episode_list_arr($series_id,400);
			$arr_count = count($video_arr);
			$count=1;
			foreach ($video_arr as $video)
			{
				$video_id = $video['VideoId'];
				if(!in_array($video_id,$video_id_arr )&&$count<=$numberposts)
				{
				echo "<li>";
				$video_id = $video['VideoId'];
				$video_detail = get_kronopress_video_by_videoid($video_id);
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
				echo "<a href='".$video_page_link."'>";
				echo $video_detail['Title'];
				echo "</a>";
				?>
				</span>
				<br/>
				<?php } ?>
				
				<?php if($instance['show_date']=="on") {?>
				<span class="video_date_widget">
				<?php
				$timestamp = $video_detail['Date'];
				$timestamp = get_date_by_timestamp($timestamp);
				echo $timestamp;
				
				?>
				</span>
				<br/>
				<?php } ?>
				
				
				
				<?php if($instance['show_description']=="on") {?>
				<span class="video_description_widget">
				<?php $description = $video_detail['Description'];
				echo wp_trim_words( $description, 10, '..' );
				?>
				</span>
			<?php
			
			} ?>
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
			
		}
		}
		echo "</ul>";
		
		echo $args['after_widget'];
	}
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Videos by series', 'text_domain' );
		$title_link = ! empty( $instance['title_link'] ) ? $instance['title_link'] : "";
		$thumb_width = ! empty( $instance['thumb_width'] ) ? $instance['thumb_width'] :"70" ;
		$thumb_height = ! empty( $instance['thumb_height'] ) ? $instance['thumb_height'] :"70" ;
		
		$show_thumb = ! empty( $instance['show_thumb'] ) ? $instance['show_thumb'] : "off";
		$show_thumb = ! empty( $instance['show_title'] ) ? $instance['show_title'] : "off";
		$show_date = ! empty( $instance['show_date'] ) ? $instance['show_date'] : "off";
		$show_description = ! empty( $instance['show_description'] ) ? $instance['show_description'] : "off";
		
		$series_arr =krono_series_list_arr();
		$no_of_videos = array();
		foreach ($series_arr as $series)
		{
		$no_of_videos[$series['SeriesId']] = ! empty( $instance['no_of_videos'.$series['SeriesId']] ) ? $instance['no_of_videos'.$series['SeriesId']] : 0;
		}
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
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_thumb' ); ?>"  name="<?php echo $this->get_field_name('show_thumb'); ?>" value="on" <?php checked($show_thumb,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_thumb' ); ?>"><?php _e( 'Show Thumbnails:' ); ?></label> 
		</p>
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_title' ); ?>"  name="<?php echo $this->get_field_name('show_title'); ?>" value="on" <?php checked($show_thumb,"on");  ?> > 
		<label for="<?php echo $this->get_field_id( 'show_title' ); ?>"><?php _e( 'Show Title:' ); ?></label> 
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
		<table>
		<tr>
		<th><?php _e( 'Series:' ); ?></th>	
		<th><?php _e( 'No. of videos:' ); ?></th>	
		</tr>
		<?php
		foreach ($series_arr as $series)
		{
		?>
		<tr>
		<td><?php echo $series['Title']; ?></td>  <td><input type="number" value="<?php echo $no_of_videos[$series['SeriesId']];?>" id="<?php echo $this->get_field_id( 'no_of_videos'.$series['SeriesId'] ); ?>" name="<?php echo $this->get_field_name( 'no_of_videos'.$series['SeriesId'] ); ?>"></td>
		</tr>
		
		<?php } ?>
		
		</table>
		
		</p>
		<?php 	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['title_link'] = ( ! empty( $new_instance['title_link'] ) ) ? strip_tags( $new_instance['title_link'] ) : '';
		$instance['show_thumb'] = $new_instance['show_thumb'];
		$instance['show_title'] = $new_instance['show_title'];
		$instance['show_date'] = $new_instance['show_date'];
		$instance['show_description'] = $new_instance['show_description'];
		$instance['thumb_height'] = $new_instance['thumb_height'];
		$instance['thumb_width'] = $new_instance['thumb_width'];
		$series_arr =krono_series_list_arr();
		foreach ($series_arr as $series)
		{
			$instance['no_of_videos'.$series['SeriesId']] = $new_instance['no_of_videos'.$series['SeriesId']];
		}
		
		return $instance;
	}

} 
function register_videos_by_series_widget() {
    register_widget( 'videos_by_series_widget' );
}
add_action( 'widgets_init', 'register_videos_by_series_widget' );

?>
