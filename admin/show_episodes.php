<h2>Episodes</h2>

<br/>



<?php

$series_id = $_REQUEST['series_id'];
$series_title = $_REQUEST['series_title'];

?>

<h3>Episode listing of Series <strong>"<?php echo $series_title; ?>" </strong></h3><br/>
<table class="krono_series_list_tbl">

<tr>
<th class="id_col">Episode</th>
<th>Thumbnail</th>
<th>Title</th>
<th>Description</th>
<th>Action</th>
</tr>
<?php
ob_start();
$per_page= 100;
$episode_list_arr = krono_episode_list_arr($series_id,1);
if(!empty($episode_list_arr))
{
$video_id = $episode_list_arr[0]['VideoId'];
$counter=1;
$record_size = 50;
$video_id_arr = array();

while($counter<=$per_page)
	{
$episode_list_arr = krono_limited_episode_list_arr($series_id,$record_size,$video_id);
$array_size = count($episode_list_arr);

foreach($episode_list_arr as $episode)
{
	$videoid = $episode['VideoId'];
	$video_id =$videoid;
	$episode_id =  $episode['VideoSeriedId'];
	$video_detail = get_kronopress_video_by_videoid($video_id);
	if(!in_array($videoid,$video_id_arr ) && $counter<=$per_page)
	{
	if($counter%2==0)
	{
		$trclass="even";
	}
	else
	{
		$trclass="odd";
	}
	
?>	
	<tr class="vid_row_<?php echo $episode_id." ".$trclass; ?>">
		
		<td>
			<?php echo $episode['Episode']; ?>
		</td>
		<td>
			<?php $thumb_url = $episode['Thumbnail'];
			if($thumb_url=="")
			{
				 $thumb_url = plugins_url('images/video_placeholder.jpg',dirname(__FILE__));
			}
			?>
			<!--<div class="series_video_thumb">-->
			<div class="video_thumb_small" id="krono_thumb_id_<?php echo $videoid ?>">
			<input type="hidden" value="<?php echo $videoid ?>" name="thumb_id" class="krono_thumb_id_<?php echo $videoid; ?>">
			<img src="<?php echo $thumb_url; ?>">
			<?php $magnifier_icon = plugins_url('images/magni-icon.png',dirname(__FILE__)); ?>
			<div class="thumb_overlay">
			<img src="<?php echo $magnifier_icon; ?>" class="magni_ico">
			</div>
			</div>
		</td>
		<td>
			<?php echo $video_detail['Title']; ?>
		</td>
		<td>
			<?php echo $video_detail['Description']; ?>
		</td>
		<td>
			<!--<input type="button" name="series_edit" class="button" value="Edit">-->
			<input type="button" name="episode_delete" id="delete_episode_link_<?php echo $episode_id; ?>" class="button episode_delete" value="Delete">
			<input type="hidden" name="delete_episode_link" class="delete_episode_link_<?php echo $episode_id ?>" value="<?php echo $episode_id; ?>">
		</td>
		
	</tr>
<?php
array_push($video_id_arr,$video_id);
$counter++;
	}
if($counter>$per_page)
			{
				break;
			}
}

if($array_size<$record_size)
				{
					
					break;
				
				}
	}
	
}
else
{
	
	echo "<tr><td colspan='5'>No records found.</td></tr>";
}
 echo ob_get_clean();
?>


	
</table>

<?php

    //function add_series_page_scripts()
    //{
    //    
    //}
    //add_action( 'admin_enqueue_scripts', 'add_series_page_scripts' );

?>