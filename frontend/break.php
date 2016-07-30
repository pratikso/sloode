<?php
function break_func( $atts )
{ ob_start();
	echo "<div class='clear'></div>";
	return ob_get_clean();
}
add_shortcode( 'break', 'break_func' );
?>