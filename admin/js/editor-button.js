
function on_video_button_click()
{
     jQuery(".thumb_list_in_popup").html("");
         jQuery(".video_list_pop").fadeIn(500);
	 jQuery(".loading_img").fadeIn(100);
	 
	var ajax_url = admin_js_vars.ajax_url;
	data = {
        action: 'get_video_list_in_popup',
		};
	   jQuery.post( ajax_url, data, function(response) {
	   jQuery(".thumb_list_in_popup").html(response);
	   jQuery(".loading_img").fadeOut(100);
	  // jQuery('#filter_video').DataTable( {   "order": [[ 0, "desc" ]]  });
	   video_thumb_click();
	   insert_video_btn_click();
	   replace_placeholder();
	   close_btn2();
	  });

	 
}
function video_thumb_click() {

	   jQuery( document ).on( 'click', '.video_thumb_in_editor', function() {  
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


function close_btn2()
{
   jQuery( document ).on( 'click', '.vid_pop .close_btn', function() {  
           jQuery(".vid_pop").fadeOut(400);
	  jQuery(".vid_pop_inner").html("");
    });
} 
function insert_video_btn_click() {
    
    jQuery( document ).one( 'click', '.insert_video_btn', function() {
	var btn_id = jQuery(this).attr('id');
	var video_id=jQuery("."+btn_id).val();
	var height = jQuery(".video_height_"+video_id).val();
	 var width = jQuery(".video_width_"+video_id).val();
	 var shortcode;
	 if(height=="" && width!="") {
	   shortcode = "[embed_video id="+video_id+" width="+width+"]";
	 }
	 else if(height!="" && width=="") {
	  shortcode = "[embed_video id="+video_id+" height="+height+"]";
	 }
	 else if ((height=="" && width=="")) {
	    shortcode = "[embed_video id="+video_id+"]";
	 }
	 else
	 {	
	 shortcode = "[embed_video id="+video_id+" height="+height+" width="+width+"]";
	 }
	wp.media.editor.insert(shortcode);
	jQuery(".video_list_pop").fadeOut(500);
	
    
    });
}
function replace_placeholder() {
	jQuery(".video_thumb_in_editor img").each(function(){
	 var placeholder = admin_js_vars.video_placeholder;
	jQuery(this).attr("onerror","this.src='"+placeholder+"'");
	});
	
    }

jQuery(document).ready(function(){
    
    close_btn2();
    jQuery('#insert_video_button').click(function(){
    //jQuery( document ).on( 'click', '#insert_video_button', function() {
    on_video_button_click();
     
    });
   	jQuery( document ).on( 'click', '.video_list_pop .close_btn', function() {
	 jQuery(".vid_pop_inner").html("");
	 jQuery(".vid_pop").fadeOut(300);
         jQuery(".video_list_pop").fadeOut(500);
    });
  
    
    
    var KEYCODE_ESC = 27;
    jQuery(document).keyup(function(e) {
       if (e.keyCode == KEYCODE_ESC) {
	 jQuery(".vid_pop_inner").html("");
	  jQuery(".vid_pop").fadeOut(300);
	jQuery(".video_list_pop").fadeOut(500);
      
       } 
  });
    
    
    jQuery( document ).on( 'click', '.load_more_videos_button_editor', function() {
    var last_video_id = jQuery("#filter_video .last_video_id").last().val();
     jQuery(".loading_more_videos_editor").show();
    var per_page = jQuery(".recent_perpage").val();
    var no_of_box = jQuery('#filter_video .last_video_id').length;
      var ajax_url = admin_js_vars.ajax_url;
           data = {
           action: 'load_more_videos_editor',
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
                        jQuery("#filter_video").append("<tr><td class='no_more_videos' colspan=5>No more videos to load.</td></tr>");
                        setTimeout(function() {   
	                jQuery("#filter_video .no_more_videos").fadeOut(300);
                        }, 4000);
                        jQuery(".load_more_videos_button_admin").fadeOut(50);
                        
                     }
                     
                       var count_index= no_of_box;
                        jQuery("#filter_video tr:gt("+count_index+")").hide().fadeIn(1000);
                       jQuery(".loading_more_videos_editor").fadeOut(500);
                       no_of_box = jQuery('#filter_video .last_video_id').length;
                          
                    
                                         
                });
  
  
  
  
  });
    
    
});
