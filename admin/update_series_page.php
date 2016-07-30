
<h2> Update Series</h2>
<br/>

<div class="add_series_div" style="">
  <?php
  if (isset($_REQUEST['series_update'])|| isset($_REQUEST['edit_series']))
  {
  $vsd_id = $_REQUEST['series_vsdid'];
  $series_id= $_REQUEST['series_id'];
  $series_episode = $_REQUEST['series_episode'];
  $series_title= $_REQUEST['series_title'];
  $series_description = $_REQUEST['series_description'];
  $series_season = $_REQUEST['series_season'];
  if(isset($_REQUEST['edit_series']))
  {
  $series_img_url = $_REQUEST['series_image'];
  $series_logo_url = $_REQUEST['series_logo'];
  }
  
  
  
  if(isset($_REQUEST['series_update']))
  {
  $series_image_old = $_REQUEST['series_image_old'];
  $series_logo_old = $_REQUEST['series_logo_old'];
  
  if(!file_exists($_FILES['series_img_new']['tmp_name']) || !is_uploaded_file($_FILES['series_img_new']['tmp_name']))
  {
    $series_img_url = $series_image_old;
   }
  else
  { 
    $series_img_id = insert_attachment('series_img_new',$post_id=0,$setthumb='false');
    $series_img_arr = wp_get_attachment_image_src( $series_img_id, 'full' );
    $series_img_url = $series_img_arr[0];
  }
 
   if(!file_exists($_FILES['series_logo_new']['tmp_name']) || !is_uploaded_file($_FILES['series_logo_new']['tmp_name']))
  {
    $series_logo_url = $series_logo_old;
  }
  else
  { 
  $series_logo_id = insert_attachment('series_logo_new',$post_id=0,$setthumb='false');
  $series_logo_arr = wp_get_attachment_image_src( $series_logo_id, 'full' );
  $series_logo_url = $series_logo_arr[0];
  }
 $api_url = KRONO_API_LINK."UpdateSeries?APIKey=".API_KEY."&SecretKey=".API_SECRET."&VSDId=".$vsd_id."&SeriesId=".$series_id."&Title=".$series_title."&Description=".$series_description."&Season=".$series_season."&Episode=".$series_episode."&ImgURL=".$series_img_url."&ImgLogo=".$series_logo_url;
 $api_response = wp_remote_retrieve_body( wp_remote_post($api_url, array('sslverify' => false,'method' => 'POST' )));
 $api_response_arr = json_decode($api_response,  true);
if ($api_response_arr=="Success")
{
   echo "<div class='success_msg'>Series Updated successfully!</div>";
}
else
{
  echo "<div class='error_msg'>Error !".$api_response_arr."</div>";
}
  }
  ?>
  
  
<form action="" method="post" name="add_series_form" class="add_series_form" id="add_series_form" enctype="multipart/form-data">
<table>
<tr>
  <td><strong>Series Id</strong></td><td>
  <input type="hidden" name="series_vsdid" value="<?php echo $vsd_id; ?>">
  <input type="hidden" name="series_id" value="<?php echo $series_id; ?>">
  <label><?php echo $series_id; ?></label>
  </tr><tr>
  <td><strong>Episode</strong></td><td><input type="text" class="small_input_box" name="series_episode" value="<?php echo $series_episode; ?>"></td>
  </tr><tr>
  <td><strong>Title</strong></td><td><input type="text" class="big_input_box" name="series_title" value="<?php echo $series_title; ?>"></td>
  </tr><tr>
  <td><strong>Description</strong></td><td><textarea class="big_input_box" name="series_description"><?php echo $series_description; ?></textarea></td>
  </tr><tr>
  <td><strong>Season</strong></td><td><input type="text" class="small_input_box" name="series_season" value="<?php echo $series_season; ?>"></td>
  
  </tr><tr>
  <td><strong>Image</strong></td><td>
  <input type="hidden" name="series_image_old" value="<?php echo $series_img_url; ?>">
  <img src="<?php echo $series_img_url; ?>" width="50px">
  <input type="file" name="series_img_new"></td>
  </tr><tr>
  <td><strong>Logo</strong></td><td>
    <input type="hidden" name="series_logo_old" value="<?php echo $series_logo_url; ?>">
  <img src="<?php echo $series_logo_url; ?>" width="50px">
  <input type="file" name="series_logo_new"></td>
  </tr>
<tr>

  <td><input type="submit" class="button" name="series_update" value="Update"></td><td></td>
  </tr>

</table>
</form>
<?php }else
{
  echo "Error!";
}

?>
</div>