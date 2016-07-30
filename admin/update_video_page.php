
<h2> Update Video</h2>
<br/>
<?php
  $update_video_url = get_add_video_url()."/".$_REQUEST['video_id'];
  
?>
<div class="add_video_div" style="width:100%; height: 100%;">
<iframe src="<?php echo $update_video_url; ?>" width="100%" height="850px"></iframe>
</div>