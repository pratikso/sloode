<?php
/*add video button near add media*/
add_action('media_buttons', 'insert_video_button', 15);

function insert_video_button() {
    echo '<a href="#" id="insert_video_button" class="button">Add Video</a>';
}


?>
