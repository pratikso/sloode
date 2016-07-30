
<h2> Add Episode</h2>
<br/>

<div class="add_series_div" style="">
  <?php
    if(isset($_REQUEST['episode_submit']))
    {
      $series_id= $_REQUEST['series_id'];
      $video_ids = $_REQUEST['video_id'];
      $video_id_arr = explode(",",$video_ids);
      $not_exist_ids = "";
      $success_ids="";
      $failed_ids = "";
      $success_count = 0;
      $failed_count =0;
      $not_exist_count = 0;
      foreach ($video_id_arr as $video_id)
      {
        if(is_video_exist($video_id))
        {
          $add_episode_response = add_episode($series_id,$video_id).",";
         
          if($add_episode_response=="Success,")
          {
            $success_ids.=",".$video_id;
            $success_count++;
          }
          else
          {
            $failed_ids .=",".$video_id;
            $failed_count++;
          }
        }
        else
        {
          $not_exist_ids .= ",".$video_id;
          $not_exist_count++;
        }
      }
      
      if($success_ids!="")
      {
        if($success_count>1)
        {
          $ids = "ids";
        }
        else
        {
          $ids = "id";
        }
        
        echo "<br/><div class='success_msg'>Video ".$ids." ".$success_ids." added successfully!</div>";
      }
      if($failed_ids!="")
      {
        
        if($failed_count>1)
        {
          $ids = "ids";
        }
        else
        {
          $ids = "id";
        }
        
        echo "<br/><div class='error_msg'>Video ".$ids." ".$failed_ids." falied!</div>";
      }
      if($not_exist_ids!="")
      {
          if($not_exist_count>1)
        {
          $ids = "ids";
        }
        else
        {
          $ids = "id";
        }
        
        echo "<br/><div class='error_msg'>Video ".$ids." ".$not_exist_ids." does not exist!</div>";
      }
      
      
    }
  
  
  
 $series_arr = krono_series_list_arr();
  ?>
<form action="" method="post" name="add_episode_form" class="add_episode_form" id="add_episode_form" enctype="multipart/form-data">
<table>
<tr>
  <td><strong>Series Id</strong></td><td>
  <select name="series_id">
  <option value="">-Select Series-</option>
  <?php foreach($series_arr as $series) { ?>
  <option value="<?php echo $series['SeriesId']; ?>"> <?php echo $series['SeriesId']."-".$series['Title'] ?> </option>
  <?php } ?>
  </select>
  </td>
  </tr>
<tr>
  <td><strong>Video Id</strong></td><td><input type="text" class="big_input_box" name="video_id"><br/>(You can insert comma separated video id's to add multiple episodes)</td>
  </tr>
<tr>
  <td><input type="submit" class="button" name="episode_submit" value="Submit"></td><td></td>
  </tr>

</table>
</form>
</div>