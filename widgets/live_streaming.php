<?php
/**
 * Adds Foo_Widget widget.
 */
class live_streming_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'live_streaming', // Base ID
			__( 'Sloode-Live Streaming', 'text_domain' ), // Name
			array( 'description' => __( 'Show live streaming of selected channel.', 'text_domain' ), ) // Args
		);
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
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		
		$live_streaming_arr =get_live_streaming_list();
		foreach ($live_streaming_arr as $channel)
		{
			$channel_id = $channel['ChannelId'];
		 if($channel_id == $instance['channel_id'] )
		 {
			$embed_code= $channel['EmbedURL'];
			preg_match('/(src=["\'](.*?)["\'])/',  $embed_code, $match);  //find src="X" or src='X'
			$split = preg_split('/["\']/', $match[0]); // split by quotes
			$embed_url = $split[1];	
				
		 }
		 
		}
		$width = $instance['width'];
		$height = $instance['height'];
		$show_logo =$instance['show_logo'];
		$show_title =$instance['show_title'];
		$live_streaming_arr =get_live_streaming_list();
		echo '<div class="live_streaming_widget">';
		$button_counter = 1;
		$channel_esist=0;
		foreach ($live_streaming_arr as $channel)
		{
		  if($instance['channel_id'.$channel['ChannelId']]=="on")
		  {
			$channel_name = $channel['ChannelName'];
			$channel_logo_url = $channel['Logo'];
		if($show_title=="on" || $show_logo=="on" )
		{
			if($button_counter==1)
			{
			echo '<a href="javascript:void(0);" class="live_streaming_channel_name selected">';
				if($show_logo=="on")
					{
					echo '<img src="'.$channel_logo_url.'" class="channel_logo">';
					}
				if($show_title=="on")
					{
					echo $channel_name;
					}
			echo '</a>';
			}
			else
			{
			echo '<a href="javascript:void(0);" class="live_streaming_channel_name">';
				if($show_logo=="on")
					{
					echo '<img src="'.$channel_logo_url.'" class="channel_logo">';
					}
				if($show_title=="on")
					{
					echo $channel_name;
					}
			echo '</a>';
			}
		}
		$embed_code= $channel['EmbedURL'];
		preg_match('/(src=["\'](.*?)["\'])/',  $embed_code, $match);  
		$split = preg_split('/["\']/', $match[0]); 
		$embed_url = $split[1];
		if($button_counter==1)
		{
		 $first_embed_url = $embed_url;
		}
				echo '<input type="hidden" name="live_embed_url" class="live_embed_url" value="'.$embed_url.'">';
				echo '<input type="hidden" name="video_height" class="video_height" value="'.$height.'">';
				echo '<input type="hidden" name="video_width" class="video_width" value="'.$width.'">';
		 $button_counter++;
		  $channel_esist=1;
		  }
		 		
		  
		}
		
		echo '<div class="clear"></div>';
		if($channel_esist)
		{
		echo '<div class="live_streaming_player">';
		echo '<iframe src="'.$first_embed_url.'" width="'.$width.'" height="'.$height.'" webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen="true"></iframe>';
		echo '</div>';
		}
		else{
			echo "No channel selected.";
		}
		echo '</div>';
		
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
		
		//$channel_id = !empty( $instance['channel_id'] ) ? $instance['channel_id'] : "";
		$title = !empty( $instance['title'] ) ? $instance['title'] : "";
		$height = !empty( $instance['height'] ) ? $instance['height'] : "100%";
		$width = !empty( $instance['width'] ) ? $instance['width'] : "100%";
		$show_title = !empty( $instance['show_title'] ) ? $instance['show_title'] : "off";
		$show_logo = !empty( $instance['show_logo'] ) ? $instance['show_logo'] : "off";
		
		$live_streaming_arr =get_live_streaming_list();
		$channel_id = array();
		foreach ($live_streaming_arr as $channel)
		{
		$channel_id[$channel['ChannelId']] = !empty($instance['channel_id'.$channel['ChannelId']]) ? $instance['channel_id'.$channel['ChannelId']] : "off";
		}
		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		
		</p>
		
		<p>
		<label><?php _e( 'Select Channels:' ); ?></label> 
		<br/>
		<?php 
		foreach ($live_streaming_arr as $channel)
		{
		?>
		
		<input type="checkbox" id="<?php echo $this->get_field_id( 'channel_id'.$channel['ChannelId'] ); ?>"  name="<?php echo $this->get_field_name('channel_id'.$channel['ChannelId']); ?>" value="on" <?php checked($channel_id[$channel['ChannelId']],"on");  ?> > <?php echo $channel['ChannelName']; ?>&nbsp; &nbsp; &nbsp; 
		
		<?php } ?>	
		
		</p>
		<p>
		<label><?php _e( 'Show Title and Logo in Tab' ); ?></label> <br/>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_title' ); ?>"  name="<?php echo $this->get_field_name('show_title'); ?>" value="on" <?php checked($show_title,"on");  ?> >  Show Title
		<input type="checkbox" id="<?php echo $this->get_field_id( 'show_logo' ); ?>"  name="<?php echo $this->get_field_name('show_logo'); ?>" value="on" <?php checked($show_logo,"on");  ?> >  Show Logo
			
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo esc_attr( $height ); ?>">
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo esc_attr( $width ); ?>">
		</p>
		
		
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
		$instance['channel_id'] = ( ! empty( $new_instance['channel_id'] ) ) ?  $new_instance['channel_id']  : '';
		$instance['height'] = ( ! empty( $new_instance['height'] ) ) ?  $new_instance['height']  : '100%';
		$instance['width'] = ( ! empty( $new_instance['width'] ) ) ?  $new_instance['width']  : '100%';
		$instance['show_title'] = $new_instance['show_title'];
		$instance['show_logo'] = $new_instance['show_logo'];
		$live_streaming_arr =get_live_streaming_list();
		foreach ($live_streaming_arr as $channel)
		{
			$instance['channel_id'.$channel['ChannelId']] = $new_instance['channel_id'.$channel['ChannelId']];
		}
		
		
		return $instance;
	}

} 

// register Recent_videos_widget
function register_live_streaming_widget() {
    register_widget( 'live_streming_widget' );
}
add_action( 'widgets_init', 'register_live_streaming_widget' );

?>
