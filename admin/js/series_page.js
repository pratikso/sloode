jQuery(document).ready(function(){
		jQuery("#add_series_form").validate({
			rules: {
				
				series_id: {
					required: true,
					number: true
					},
				series_episode: {
					required: true,
					number: true			
				},
				series_season: {
					required: true,
					number: true			
				},
				series_title: {
					required: true
			    		},
				},
			messages: {
				series_id: {
					required: "Please enter Series Id.",
					number: "You can insert only number in this field"
				},
				series_episode: {
					required: "Please enter Series Episode.",
					number: "You can insert only number in this field"
				},
				series_season: {
					required: "Please enter Season.",
					number: "You can insert only number in this field"
				},
				series_title: {
					required: "Please enter a title.",
					
				},
				
			}
		});
	    
	    
	    
	    jQuery("#add_episode_form").validate({
			rules: {
			    series_id:
			    {
				required: true,
			    },
			    video_id:
			    {
				required: true,
			    }
			},
			messages:
			{
			    
			}
			});
	    
	    
	jQuery( document ).on( 'click', '.episode_delete', function() { 
	var btn_id = jQuery(this).attr('id') ;
	var episode_id = jQuery("."+btn_id).attr('value');
	var confirm_msg = confirm("Episode will be deleted.");
	if (confirm_msg==true) {
	       jQuery.ajax({
		    url : admin_js_vars.ajax_url,
		    type : 'post',
		    data : {
			    action : 'delete_kronopress_episode',
			    episode_id : episode_id
			    },
		    success : function( response ) {
		// alert(response);
			if (response=='"Success"'||response=='"success"')
			{
			 
		     jQuery("tr.vid_row_"+episode_id).fadeOut(500,function()
		    { 
		    jQuery("tr.vid_row_"+episode_id).remove();
			 alert("Record deleted successfully!");
		    });
		     
			}
			if (response=='"Error"') {
			    alert("Error!");
			}
		
		    }
	    });
	}
	return false;
	});
	    
	
	jQuery( document ).on( 'click', '.series_delete', function() {
	   var btn_id = jQuery(this).attr('id') ;
	   var series_id = jQuery("."+btn_id).attr('value');
	   var vsd_id= jQuery(".testtd-"+series_id+" .delete_series_vsd").attr('value');
	   var confirm_msg = confirm("Series will be deleted.");
	if (confirm_msg==true) {
	       jQuery.ajax({
		    url : admin_js_vars.ajax_url,
		    type : 'post',
		    data : {
			    action : 'delete_kronopress_series',
			    series_id : series_id,
			    vsd_id: vsd_id
			    },
		    success : function( response ) {
		//alert(response);
			if (response=='"Success"'||response=='"success"')
			{
			 
		     jQuery("tr.vid_row_"+series_id).fadeOut(500,function()
		    { 
		    jQuery("tr.vid_row_"+series_id).remove();
			 alert("Record deleted successfully!");
		    });
		     
			}
			if (response=='"Error"') {
			    alert("Error!");
			}
		
		    }
	    });
	}
	return false;

	});
	
	    }); 
