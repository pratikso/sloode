
<h2> Add Preroll</h2>
<br/>

<div class="add_preroll_div" style="">
  <?php
    if(isset($_REQUEST['preroll_submit']))
    {
      $video_id= $_REQUEST['video_id'];
      $cat_id= $_REQUEST['cat_id'];
      $start_date = $_REQUEST['start_date'];
      $end_date =   $_REQUEST['end_date'];
     $is_video_exist = is_video_exist($video_id);
     if($is_video_exist)
     {
      $response = add_preroll($video_id, $cat_id, $start_date, $end_date);
     }
     else
     {
      $response = "Video Id ".$video_id." does not exist!";
      
     }
     
      if ($response=="Success")
{
  echo "<div class='success_msg'>Preroll added successfully!</div>";
 
}
else
{
  echo "<div class='error_msg'>Error ! ".$response."</div>";
}
      
    }
  
  
  
$cat_arr = get_kronopress_video_cat();
  ?>
<form action="" method="post" name="add_preroll_form" class="add_preroll_form" id="add_preroll_form" enctype="multipart/form-data">
<table>
  
<tr>
  <td>Video ID</td>
  <td><input type="text" class="small_input_box" name="video_id"></td>
</tr>

<tr>
  <td>Category</td>
  <td><select id="cat_id" name="cat_id">
			<option value="">-Select Category-</option>
			<?php
			foreach($cat_arr as $cat)
				{?>
			<option value="<?php echo $cat['CategoryId']?>"><?php echo $cat['CategoryName']?></option>
			<?php }
			?>
			</select></td>
</tr>

<tr>
  <td>Start date</td>
  <td><input type="text" class="small_input_box datepicker" name="start_date"></td>
</tr>

<tr>
  <td>End date</td>
  <td><input type="text" class="small_input_box datepicker" name="end_date"></td>
</tr>


<tr>
  <td><input type="submit" class="button" name="preroll_submit" value="Submit"></td><td></td>
  </tr>

</table>
</form>
</div>