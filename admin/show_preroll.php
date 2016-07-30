<h2>Preroll</h2>
<br/>
<?php
$add_preroll_url = admin_url('admin.php?page=add_preroll');
?>
<a href="<?php echo $add_preroll_url; ?>"><input type="button" class="button add_preroll" name="add_preroll" value="Add preroll"></a>&nbsp;

<br/>

<?php
$preroll_list_arr = krono_preroll_list_arr();

//print_r($preroll_list_arr);

$preroll_id_arr = array();
?>

<table class="krono_series_list_tbl">
<tr>
<th class="id_col">ID</th>
<th>Thumbnail</th>
<th>Title</th>
<th>Category</th>
<th>Start date</th>
<th>End date</th>
<th>Action</th>
</tr>

<?php
$i = 0;
foreach($preroll_list_arr as $preroll)
{
	$preroll_id = $preroll['PrerollId'];
	$videoid = $preroll['VideoId'];
	$video_id =$videoid;
	$video_detail = get_kronopress_video_by_videoid($video_id);
	
	
	$preroll_title = $preroll['Title'];
	if($i%2==0)
	{
		$trclass="even";
	}
	else
	{
		$trclass="odd";
	}
	if(!in_array($preroll_id,$preroll_id_arr ))
	{
?>	
	<tr class="vid_row_<?php echo $preroll_id." ".$trclass; ?>">
		
		<td>
			<?php echo $preroll_id; ?>
		</td>
		<td>
			<?php $thumb_url = $video_detail['Thumbnail'];
			if($thumb_url=="")
			{
				 $thumb_url = plugins_url('images/video_placeholder.jpg',dirname(__FILE__));
			}
			?>
			<!--<div class="series_video_thumb">
			<img src="<?php //echo $thumb_url; ?>">
			</div>-->
			
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
			<?php
			
			$episode_url = admin_url('admin.php?page=episodes&series_id='.$preroll_id.'&series_title='.$preroll_title);
			?>
	<?php echo $preroll['Title']; ?>
		</td>
		
		<td> <?php echo $preroll['CategoryName']; ?></td>
		<td>
			<?php echo get_date_by_timestamp($preroll['StartDate']); ?>
		</td>
		
			<td>
			<?php echo get_date_by_timestamp($preroll['Enddate']); ?>
		</td>
		
		<td>
			
			<input type="button" name="preroll_delete" id="delete_preroll_link_<?php echo $preroll_id; ?>" class="button preroll_delete" value="Delete">
			<input type="hidden" name="delete_preroll_link" class="delete_preroll_link_<?php echo $preroll_id; ?>" value="<?php echo $preroll_id; ?>">
		</td>
		
	</tr>
<?php
array_push($preroll_id_arr,$preroll_id);
$i++;
	}
}
?>


	
</table>

<?php

?>