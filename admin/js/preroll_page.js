jQuery(document).ready(function(){
     
         
	jQuery( document ).on( 'click', '.preroll_delete', function() {
               
	var btn_id = jQuery(this).attr('id') ;
	var preroll_id = jQuery("."+btn_id).attr('value');
	var confirm_msg = confirm("preroll will be deleted.");
	if (confirm_msg==true) {
	       jQuery.ajax({
		    url : admin_js_vars.ajax_url,
		    type : 'post',
		    data : {
			    action : 'delete_kronopress_preroll',
			    preroll_id : preroll_id
			    },
		    success : function( response ) {
		// alert(response);
			if (response=='"Success"'||response=='"success"')
			{
			 
		     jQuery("tr.vid_row_"+preroll_id).fadeOut(500,function()
		    { 
		    jQuery("tr.vid_row_"+preroll_id).remove();
			 alert("Preroll deleted successfully!");
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
     
     
     
     jQuery("#add_preroll_form").validate({
			rules: {
				
				video_id: {
					required: true,
					number: true
					},
				cat_id: {
					required: true,
					number: true			
				},
				start_date: {
					required: true,
					},
				end_date: {
					required: true
			    		},
				},
			messages: {
				video_id: {
					required: "Please enter Video Id.",
					number: "You can insert only number in this field"
				},
				cat_id: {
					required: "Please select category.",
					number: "You can insert only number in this field"
				},
				start_date: {
					required: "Please enter Start date.",
					
				},
				end_date: {
					required: "Please enter End date.",
					
				},
				
			}
		});
     
     
                
});