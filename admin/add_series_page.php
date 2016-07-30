
<h2> Add Series</h2>
<br/>

<div class="add_series_div" style="">
  <?php
  if(isset($_REQUEST['series_id']))
  {
  $series_id= $_REQUEST['series_id'];
  $series_episode = $_REQUEST['series_episode'];
  $series_title= $_REQUEST['series_title'];
  $series_description = $_REQUEST['series_description'];
  $series_season = $_REQUEST['series_season'];
    
  $series_img_id = insert_attachment('series_img',$post_id=0,$setthumb='false');
  $series_img_arr = wp_get_attachment_image_src( $series_img_id, 'full' );
  $series_img_url = $series_img_arr[0];
  
  $series_logo_id = insert_attachment('series_logo',$post_id=0,$setthumb='false');
  $series_logo_arr = wp_get_attachment_image_src( $series_logo_id, 'full' );
  $series_logo_url = $series_logo_arr[0];
 
 //api.kronopress.com/WCFServices/Kronopress.svc/AddSeries?APIKey={APIKey}&SecretKey={SecretKey}&SeriesId={SeriesId}&Title={Title}&Description={Description}&Season={Season}&Episode={Episode}&ImgURL={ImgURL}&ImgLogo={ImgLogo}
 
 $api_url = KRONO_API_LINK."AddSeries?APIKey=".API_KEY."&SecretKey=".API_SECRET."&SeriesId=".$series_id."&Title=".$series_title."&Description=".$series_description."&Season=".$series_season."&Episode=".$series_episode."&ImgURL=".$series_img_url."&ImgLogo=".$series_logo_url;
 $api_response = wp_remote_retrieve_body( wp_remote_post($api_url, array('sslverify' => false,'method' => 'POST' )));
 $api_response_arr = json_decode($api_response,  true);
if ($api_response_arr!=0)
{
  echo "<div class='success_msg'>Series added successfully!</div>";
 
}
else
{
  echo "<div class='error_msg'>Error ! Series Id ".$series_id." already exist!</div>";
}
  }
  ?>
  
  
<form action="" method="post" name="add_series_form" class="add_series_form" id="add_series_form" enctype="multipart/form-data">
<table>
<tr>
  <td><strong>Series Id</strong></td><td><input type="text" class="small_input_box" name="series_id"></td>
  </tr><tr>
  <td><strong>Episode</strong></td><td><input type="text" class="small_input_box" name="series_episode"></td>
  </tr><tr>
  <td><strong>Title</strong></td><td><input type="text" class="big_input_box" name="series_title"></td>
  </tr><tr>
  <td><strong>Description</strong></td><td><textarea class="big_input_box" name="series_description"></textarea></td>
  </tr><tr>
  <td><strong>Season</strong></td><td><input type="text" class="small_input_box" name="series_season"></td>
  
  </tr><tr>
  <td><strong>Image</strong></td><td><input type="file" name="series_img"></td>
  </tr><tr>
  <td><strong>Logo</strong></td><td><input type="file" name="series_logo"></td>
  </tr>
<tr>

  <td><input type="submit" class="button" name="series_submit" value="Submit"></td><td></td>
  </tr>

</table>
</form>
</div>