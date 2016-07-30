<h2>Series</h2>

<br/>
<?php
$add_series_url = admin_url('admin.php?page=add_series');
$add_episode_url = admin_url('admin.php?page=add_episode');
?>
<a href="<?php echo $add_series_url; ?>"><input type="button" class="button add_series" name="add_series" value="Add Series"></a>&nbsp;
<a href="<?php echo $add_episode_url; ?>"><input type="button" class="button add_episode" name="add_episode" value="Add Episode"></a>
<br/>
<?php
$series_list_arr = krono_series_list_arr();
//echo "<pre>";
//print_r($series_list_arr);
//echo "</pre>";
$series_id_arr = array();
?>

<table class="krono_series_list_tbl">
<tr>
<th class="id_col">ID</th>
<th>Thumbnail</th>
<th>Title</th>
<th>Description</th>
<th>Action</th>
</tr>

<?php
$i = 0;
foreach($series_list_arr as $series)
{
	$series_id = $series['SeriesId'];
	 $series_title = $series['Title'];
	 $vsd_id = $series['VSDId'];
	if($i%2==0)
	{
		$trclass="even";
	}
	else
	{
		$trclass="odd";
	}
	if(!in_array($series_id,$series_id_arr ))
	{
?>	
	<tr class="vid_row_<?php echo $series_id." ".$trclass; ?>">
		
		<td>
			<?php echo $series_id; ?>
		</td>
		<td>
			<?php $thumb_url = $series['Image'];
			if($thumb_url=="")
			{
				 $thumb_url = plugins_url('images/video_placeholder.jpg',dirname(__FILE__));
			}
			?>
			<div class="series_video_thumb">
			<img src="<?php echo $thumb_url; ?>">
			</div>
		</td>
		<td>
			<?php
			
			$episode_url = admin_url('admin.php?page=episodes&series_id='.$series_id.'&series_title='.$series_title);
			?>
		<a href="<?php echo $episode_url; ?>"><?php echo $series['Title']; ?></a>
		</td>
		<td>
			<?php echo $series['Description']; ?>
		</td>
		<td class="testtd-<?php echo $series_id; ?>">
		<?php
		$edit_series_url = admin_url('admin.php?page=update_series');
		
		?>
			<!--<a href="<?php //echo $edit_series_url; ?>" class="edit" target="_blank"><input type="button" name="series_edit" class="button" value="Edit"></a>-->
			
			<form action="<?php echo $edit_series_url; ?>" target="_blank" name="series_edit" method="post">
			<input type="hidden" name="series_vsdid" value="<?php echo $vsd_id; ?>">
			<input type="hidden" name="series_id" value="<?php echo $series_id; ?>">
			<input type="hidden" name="series_title" value="<?php echo $series['Title']; ?>">
			<input type="hidden" name="series_description" value="<?php echo $series['Description']; ?>">
			<input type="hidden" name="series_episode" value="<?php echo $series['Episode']; ?>">
			<input type="hidden" name="series_season" value="<?php echo $series['Season']; ?>">
			<input type="hidden" name="series_image" value="<?php echo $thumb_url; ?>">
			<input type="hidden" name="series_logo" value="<?php echo $series['Logo']; ?>">
			<input type="submit" value="Edit" name="edit_series" class="button">
			</form>
					
			<input type="button" name="series_delete" id="delete_series_link_<?php echo $series_id; ?>" class="button series_delete" value="Delete">
			<input type="hidden" name="delete_series_link" class="delete_series_link_<?php echo $series_id ?>" value="<?php echo $series_id; ?>">
			<input type="hidden" name="delete_series_vsd" class="delete_series_vsd" value="<?php echo $vsd_id; ?>">
		</td>
		
	</tr>
<?php
array_push($series_id_arr,$series_id);
$i++;
	}
}
?>
	
</table>
