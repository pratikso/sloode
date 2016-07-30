jQuery(document).ready(function(){
  
    setTimeout(function() { 
   jQuery(".category_add_success").fadeOut(300);
     }, 5000);
  
    function replace_placeholder() {
	jQuery(".video_thumb_small img").each(function(){
	    var placeholder = admin_js_vars.video_placeholder;
	jQuery(this).attr("onerror","this.src='"+placeholder+"'");
	});
    }
        function replace_placeholder_series() {
	jQuery(".series_video_thumb img").each(function(){
	    var placeholder = admin_js_vars.video_placeholder;
	jQuery(this).attr("onerror","this.src='"+placeholder+"'");
	});
    }

    
replace_placeholder();
replace_placeholder_series();

jQuery(".datepicker" ).datepicker({
      dateFormat: "mm-dd-yy"
      });
		
//jQuery('#filter_video').DataTable(  {   "order": [[ 0, "desc" ]]  } );
 

jQuery( document ).on( 'click', '.cat_filter', function() {
    jQuery('#cat_data').html("&nbsp;");
    jQuery('.loading_img_in_video_listing').fadeIn(400);
	var cat_id = jQuery( "#cat option:selected" ).val(); 
	jQuery.ajax({
		url : admin_js_vars.ajax_url,
		type : 'post',
		data : {
		action : 'get_kronopress_video_by_catid',
		post_id : cat_id
		},
		success : function( response ) {
		jQuery('.loading_img_in_video_listing').hide();
		jQuery('#cat_data').html( response );
		//jQuery('#filter_video').DataTable(  {   "order": [[ 0, "desc" ]]  } );
		 video_thumb_click2();
		replace_placeholder();
		}
	});
	 	return false;
      
	});
	
	
	jQuery( document ).on( 'click', '.date_filter', function() {
	jQuery('#cat_data').html("&nbsp;");
        jQuery('.loading_img_in_video_listing').fadeIn(400);
	var start_date = jQuery( "#date_start" ).val(); 
	var end_date = jQuery( "#date_end" ).val();  
	jQuery.ajax({
		url : admin_js_vars.ajax_url,
		type : 'post',
		data : {
		action : 'get_kronopress_video_by_date',
		startdate : start_date,
		enddate : end_date
		},
		success : function( response ) {
		jQuery('.loading_img_in_video_listing').hide();
		jQuery('#cat_data').html( response );
		//jQuery('#filter_video').DataTable(  {   "order": [[ 0, "desc" ]]  } );
		video_thumb_click2();
		replace_placeholder();
		}
	});
	 
	return false;
	});
	

jQuery( document ).on( 'click', '.delete_video', function() { 
	var btn_id = jQuery(this).attr('id') ;
	var videoid = jQuery("."+btn_id).attr('value');
	var confirm_msg = confirm("Video will be deleted.");
	if (confirm_msg==true) {
	       jQuery.ajax({
		    url : admin_js_vars.ajax_url,
		    type : 'post',
		    data : {
			    action : 'delete_kronopress_video',
			    video_id : videoid
			    },
		    success : function( response ) {
		 
			if (response=='"Success"'||response=='"success"')
			{
			 
		     jQuery("tr.vid_row_"+videoid).fadeOut(500,function()
		    { 
		    jQuery("tr.vid_row_"+videoid).remove();
			
		    });
		      alert("Record deleted successfully!");
			}
			if (response=='"Error"') {
			    alert("Error!");
			}
		 //  jQuery('#filter_video').DataTable(  {   "order": [[ 0, "desc" ]]  } );
		    }
	    });
	}
	return false;
	});

	

	
jQuery( document ).on( 'click', '.upload_yt', function() { 
	var btn_id = jQuery(this).attr('id') ;
	var videoid = jQuery("."+btn_id).attr('value');
	jQuery(this).closest( "td" ).find(".uploading_on_yt_wait").css('color','green');
	jQuery(this).closest( "td" ).find(".uploading_on_yt_wait").html('Please wait...');
	jQuery(this).closest( "td" ).find(".uploading_on_yt_wait").fadeIn(500);
	
	       jQuery.ajax({
		    url : admin_js_vars.ajax_url,
		    type : 'post',
		    data : {
			    action : 'upload_on_youtube',
			    video_id : videoid
			    },
		    success : function( response ) {
		//jQuery(".upload_yt").closest( "td" ).find(".uploading_on_yt_wait").html(response);
		  var response_crop = response.substring(0, 5);
		    	if (response=='Success'||response=='')
			{
			jQuery("#"+btn_id).closest( "td" ).find(".uploading_on_yt_wait").html('Video uploaded successfully.');
			jQuery("#"+btn_id).closest( "td" ).find(".uploading_on_yt_wait").css('color','green');
			jQuery("#"+btn_id).fadeOut(300);
			}
			else
			{
			jQuery("#"+btn_id).closest( "td" ).find(".uploading_on_yt_wait").html("Error!");
			jQuery("#"+btn_id).closest( "td" ).find(".uploading_on_yt_wait").css('color','red');
			}
			 setTimeout(function() {
			    jQuery(".upload_yt").closest( "td" ).find(".uploading_on_yt_wait").fadeOut(300);
		 
				}, 8000);
			   
			   
		 
		    }	
			
		  
	    });
	
	return false;
	});
	
	
	
	
	

video_thumb_click2();


jQuery( document ).on( 'click', '.add_new_krono_cat', function()
{
    var cat_name = jQuery(".krono_new_cat").attr('value');
    if (cat_name != "") {
	jQuery(".wait").fadeIn(300);
        var ajax_url = admin_js_vars.ajax_url;
	data = {
        action: 'add_krono_category',
	cat_name: cat_name
      	};
	  jQuery.post( ajax_url, data, function(response) {
	    jQuery(".wait").fadeOut(300);
	   if (response!='Error') 
	    {
	    jQuery(".category_add_success").fadeIn(300);
	    setTimeout(function() {   //calls click event after a certain time
	       jQuery(".krono_cat_list_tbl").html(response);
	       jQuery(".category_add_success").fadeOut(300);
	    }, 5000);
	    jQuery(".krono_new_cat").val('');
	     }
	  });
	  
	    
    }
  
});

jQuery( document ).on( 'click', '.delete_cat_btn', function() { 
	var btn_id = jQuery(this).attr('id') ;
	var cat_id = jQuery("."+btn_id).attr('value');
	var confirm_msg = confirm("Category will be deleted.");
	if (confirm_msg==true) {
	       jQuery.ajax({
		    url : admin_js_vars.ajax_url,
		    type : 'post',
		    data : {
			    action : 'delete_krono_category',
			    cat_id : cat_id
			    },
		    success : function( response ) {
		    //alert(response);
			if (response=='"Success"'||response=='"success"')
			{
			 
		     jQuery("tr.cat_row_"+cat_id).fadeOut(500,function()
		    { 
		    jQuery("tr.cat_row_"+cat_id).remove();
			
		    });
		      alert("Category deleted successfully!");
			}
			else{
			    alert("Error!");
			}
		       }
	    });
	}
	return false;
	});





  jQuery( document ).on( 'click', '.load_more_videos_button_admin', function() {
    var last_video_id = jQuery("#filter_video .last_video_id").last().val();
     jQuery(".loading_more_videos_admin").show();
    var per_page = jQuery(".recent_perpage").val();
    var no_of_box = jQuery('#filter_video .last_video_id').length;
      var ajax_url = admin_js_vars.ajax_url;
           data = {
           action: 'load_more_videos_admin',
           per_page: per_page,
           last_video_id: last_video_id,
           temp_count:no_of_box,
        };
                 
         jQuery.ajax({
                method: "POST",
                url: ajax_url,
                data: data,
                async:false
              }).done(function( response ) {
                     if (response!=0) {
                         jQuery("#filter_video").append(response);
		     video_thumb_click2();
		     replace_placeholder();
                         }
                     else
                     {
                        jQuery("#filter_video").append("<tr><td class='no_more_videos' colspan=7>No more videos to load.</td></tr>");
                        setTimeout(function() {   
	                jQuery("#filter_video .no_more_videos").fadeOut(300);
                        }, 4000);
                        jQuery(".load_more_videos_button_admin").fadeOut(50);
                        
                     }
                     
                       var count_index= no_of_box;
                        jQuery("#filter_video tr:gt("+count_index+")").hide().fadeIn(1000);
                       jQuery(".loading_more_videos_admin").fadeOut(500);
                       no_of_box = jQuery('#filter_video .last_video_id').length;
                          
                    
                                         
                });
  
  
  
  
  });








jQuery( document ).on( 'click', '.load_more_by_cat_button_admin', function() {
    var last_video_id = jQuery("#filter_video .last_video_id").last().val();
     jQuery(".loading_more_videos_admin").show();
    var per_page = jQuery(".cat_perpage").val();
     var cat_id = jQuery(".cat_id").val();
    var no_of_box = jQuery('#filter_video .last_video_id').length;
      var ajax_url = admin_js_vars.ajax_url;
           data = {
           action: 'load_more_by_cat_admin',
           per_page: per_page,
           last_video_id: last_video_id,
           temp_count:no_of_box,
	   cat_id: cat_id
        };
                 
         jQuery.ajax({
                method: "POST",
                url: ajax_url,
                data: data,
                async:false
              }).done(function( response ) {
                     if (response!=0) {
                         jQuery("#filter_video").append(response);
		     video_thumb_click2();
		     replace_placeholder();
                         }
                     else
                     {
                        jQuery("#filter_video").append("<tr><td class='no_more_videos' colspan=7>No more videos to load.</td></tr>");
                        setTimeout(function() {   
	                jQuery("#filter_video .no_more_videos").fadeOut(300);
                        }, 4000);
                        jQuery(".load_more_by_cat_button_admin").fadeOut(50);
                        
                     }
                     
                       var count_index= no_of_box;
                        jQuery("#filter_video tr:gt("+count_index+")").hide().fadeIn(1000);
                       jQuery(".loading_more_videos_admin").fadeOut(500);
                       no_of_box = jQuery('#filter_video .last_video_id').length;
                          
                    
                                         
                });
  
  });
});

//thumb click on video listing page
function video_thumb_click2() {
	jQuery( document ).on( 'click', '.video_thumb_small', function() {
	var btn_id = jQuery(this).attr('id');
		var video_id=jQuery("."+btn_id).val();
		jQuery(".vid_pop").fadeIn(400);
		jQuery(".loading_img2").fadeIn(100);
		
	var ajax_url = admin_js_vars.ajax_url;
	data = {
        action: 'get_video_in_popup',
	vid_id: video_id
      	};
	  jQuery.post( ajax_url, data, function(response) {
	  jQuery(".vid_pop_inner").html(response);
	   jQuery(".loading_img2").fadeOut(50);
	  });
		
		
	    }); 
}