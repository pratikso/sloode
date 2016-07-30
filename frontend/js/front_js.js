jQuery(document).ready(function(){
jQuery(".video_thumb img").each(function(){
var placeholder = front_js_vars.video_placeholder;
jQuery(this).attr("onerror","this.src='"+placeholder+"'");
});
  
    jQuery(".recent_video_load_more_button").click(function(){
    var last_video_id = jQuery(".krono_recent_videos_wrap .last_video_id").last().val();
    jQuery(".loading_recent_videos").show();
    var per_page = jQuery(".recent_perpage").val();
    var numberposts = jQuery(".recent_numberposts").val();
    var height = jQuery(".recent_thumb_height").val();
    var width = jQuery(".recent_thumb_width").val();
    var show_title = jQuery(".recent_show_title").val();
    var show_hits = jQuery(".recent_show_hits").val();
    var show_cat = jQuery(".recent_show_cat").val();
    var template = jQuery(".recent_template").val();
    var play_button = jQuery(".recent_play_button").val();
    var show_date = jQuery(".recent_show_date").val();
    var no_of_box = jQuery('.krono_recent_videos_wrap > .video_thumb').length;
    var ajax_url = front_js_vars.ajax_url;
           data = {
           action: 'load_more_recent_videos',
           per_page: per_page,
           last_video_id: last_video_id,
           temp_count:no_of_box,
           numberposts:numberposts,
           height: height,
           width: width,
           show_title:show_title,
	   show_hits: show_hits,
	   show_date: show_date,
	   show_cat : show_cat,
	   template:template,
	   play_button : play_button
           };
   
         jQuery.ajax({
                method: "POST",
                url: ajax_url,
                data: data,
                async:false
              }).done(function( response ) {
                     if (response!=0) {
                         jQuery(".krono_recent_videos_wrap").append(response);   
                         }
                     else
                     {
                        jQuery(".krono_recent_videos_wrap").append("<div class='clear clearfix'></div><div class='no_more_videos'>No more videos to load.</div>");
                        setTimeout(function() {   
	                jQuery(".krono_recent_videos_wrap .no_more_videos").fadeOut(300);
                        }, 3000);
                        jQuery(".recent_video_load_more_button").fadeOut(50);
                        
                     }
                       var count_index= no_of_box-1;
                         jQuery(".krono_recent_videos_wrap .video_thumb:gt("+count_index+")").hide().fadeIn(1000);
                       jQuery(".loading_recent_videos").fadeOut(500);
                          no_of_box = jQuery('.krono_recent_videos_wrap > .video_thumb').length;
                           if ((no_of_box>=numberposts && numberposts!=0)) {
                          jQuery(".recent_video_load_more_button").fadeOut(500);
                           
                          }
                      });
    });
 
    jQuery(".category_video_load_more_button").click(function(){
    var last_video_id = jQuery(".krono_cat_videos_wrap .last_video_id").last().val();
    jQuery(".loading_cat_videos").show();
    var per_page = jQuery(".cat_perpage").val();
    var numberposts = jQuery(".cat_numberposts").val();
    var cat_id = jQuery(".cat_id").val();
     var special_cat = jQuery(".special_cat").val();
    var height = jQuery(".thumb_height_cat").val();
    var width = jQuery(".thumb_width_cat").val();
    var show_title = jQuery(".show_title_cat").val();
    var no_of_box = jQuery('.krono_cat_videos_wrap > .count_thumb').length;
    var show_hits = jQuery(".show_hits_cat").val();
    var show_cat = jQuery(".show_cat_cat").val();
    var template = jQuery(".template_cat").val();
    var play_button = jQuery(".play_button_cat").val();
    var show_date = jQuery(".show_date_cat").val();
     var ajax_url = front_js_vars.ajax_url;
           data = {
           action: 'load_more_cat_videos',
           per_page: per_page,
           last_video_id: last_video_id,
           cat_id: cat_id,
	   special_cat:special_cat,
           temp_count: no_of_box,
           numberposts:numberposts,
           height: height,
           width: width,
           show_title:show_title,
	   show_hits : show_hits,
	   show_cat: show_cat,
	   template:template,
	   play_button:play_button,
	   show_date:show_date
           };
    
         jQuery.ajax({
                method: "POST",
                url: ajax_url,
                data: data,
                async:false
              }).done(function( response ) {
                         if (response!=0) {
                         jQuery(".krono_cat_videos_wrap").append(response);   
                         }
                         else{
                           jQuery(".krono_cat_videos_wrap").append("<div class='clear clearfix'></div><div class='no_more_videos'>No more videos to load.</div>");
                           setTimeout(function() {   
	      
                    jQuery(".no_more_videos").fadeOut(300);
                 }, 3000);
                               jQuery(".category_video_load_more_button").fadeOut(50);
                         } 
                      
                         var count_index= no_of_box-1;
                         jQuery(".krono_cat_videos_wrap .video_thumb:gt("+count_index+")").hide().fadeIn(1000);
                       jQuery(".loading_cat_videos").fadeOut(500);
                          no_of_box = jQuery('.krono_cat_videos_wrap > .count_thumb').length;
                           if ((no_of_box>=numberposts && numberposts!=0)) {
                          jQuery(".category_video_load_more_button").fadeOut(500);
                           
                          }           
                });
    });
  
    
    jQuery(".related_video_load_more_button").click(function(){
    var last_video_id = jQuery(".krono_related_videos_wrap .last_video_id").last().val();
 
    jQuery(".loading_rel_videos").show();
    var per_page = jQuery(".rel_perpage").val();

    var numberposts = jQuery(".rel_numberposts").val();
    var cat_id = jQuery(".rel_cat_id").val();
     var special_cat = jQuery(".rel_cat_id").val();
    var height = jQuery(".thumb_height_rel").val();
    var width = jQuery(".thumb_width_rel").val();
    var show_title = jQuery(".show_title_rel").val();
    var no_of_box = jQuery('.krono_related_videos_wrap > .count_thumb').length;
    var show_hits = jQuery(".show_hits_rel").val();
    var show_cat = jQuery(".show_cat_rel").val();
    var template = jQuery(".template_rel").val();
    var play_button = jQuery(".play_button_rel").val();
    var show_date = jQuery(".show_date_rel").val();
     var ajax_url = front_js_vars.ajax_url;
           data = {
           action: 'load_more_rel_videos',
           per_page: per_page,
           last_video_id: last_video_id,
           cat_id: cat_id,
	   special_cat:special_cat,
          temp_count: no_of_box,
          numberposts:numberposts,
          height: height,
           width: width,
           show_title:show_title,
	   show_hits : show_hits,
	   show_cat: show_cat,
	   template:template,
	   play_button:play_button,
	   show_date:show_date
           };
        
         jQuery.ajax({
                method: "POST",
                url: ajax_url,
                data: data,
                async:false
              }).done(function( response ) {
                         if (response!=0) {
                         jQuery(".krono_related_videos_wrap").append(response);   
                         }
                         else{
                           jQuery(".krono_related_videos_wrap").append("<div class='clear clearfix'></div><div class='no_more_videos'>No more videos to load.</div>");
                           setTimeout(function() {   
	      
                    jQuery(".no_more_videos").fadeOut(300);
                 }, 3000);
                               jQuery(".related_video_load_more_button").fadeOut(50);
                         } 
                   
                         var count_index= no_of_box-1;
                         jQuery(".krono_related_videos_wrap .video_thumb:gt("+count_index+")").hide().fadeIn(1000);
                       jQuery(".loading_rel_videos").fadeOut(500);
                          no_of_box = jQuery('.krono_related_videos_wrap > .count_thumb').length;
                           if ((no_of_box>=numberposts && numberposts!=0)) {
                          jQuery(".related_video_load_more_button").fadeOut(500);
                           
                          }
                                          
                });
   
    });
  
    jQuery(".series_video_load_more_button").click(function(){
    var last_video_id = jQuery(".krono_series_videos_wrap .last_video_id").last().val();
 
   jQuery(".loading_series_videos").show();
    var per_page = jQuery(".series_perpage").val();
    var numberposts = jQuery(".series_numberposts").val();
    var series_id = jQuery(".series_id").val();
    var height = jQuery(".thumb_height_series").val();
    var width = jQuery(".thumb_width_series").val();
    var show_title = jQuery(".show_title_series").val();
    var no_of_box = jQuery('.krono_series_videos_wrap > .video_thumb').length;
     
     var ajax_url = front_js_vars.ajax_url;
           data = {
           action: 'load_more_series_videos',
           per_page: per_page,
           last_video_id: last_video_id,
           series_id: series_id,
          temp_count: no_of_box,
          numberposts:numberposts,
          height: height,
           width: width,
           show_title:show_title
           };
        
         jQuery.ajax({
                method: "POST",
                url: ajax_url,
                data: data,
                async:false
              }).done(function( response ) {
                         if (response!=0) {
                         jQuery(".krono_series_videos_wrap").append(response);   
                         }
                         else{
                           jQuery(".krono_series_videos_wrap").append("<div class='clear clearfix'></div><div class='no_more_videos'>No more videos to load.</div>");
                           setTimeout(function() {   
	      
                    jQuery(".no_more_videos").fadeOut(300);
                 }, 3000);
                               jQuery(".series_video_load_more_button").fadeOut(50);
                         } 
                       
                         var count_index= no_of_box-1;
                         jQuery(".krono_series_videos_wrap .video_thumb:gt("+count_index+")").hide().fadeIn(1000);
                       jQuery(".loading_series_videos").fadeOut(500);
                          no_of_box = jQuery('.krono_series_videos_wrap > .video_thumb').length;
                           if ((no_of_box>=numberposts && numberposts!=0)) {
                          jQuery(".series_video_load_more_button").fadeOut(500);
                           
                          }
                                                
                });
       
          
    });
    
        jQuery(".krono_comment_button").click(function(){
	     var comment_txt = jQuery("#krono_comment_box").val();
	    var comment_txt2 = jQuery("#krono_comment_box").val().replace(/ /g,'');
	    if (comment_txt2=="") {
		alert("there is no text in comment box.");
	    }
	    else
	    {
	    jQuery(".loading_post_comments").show();
	    var comment_video_id = jQuery(".krono_comment_video_id").val();
	     var comment_user_name = jQuery(".comment_user_name").val();
	     var comment_user_email = jQuery(".comment_user_email").val();
	     
	     $comment_user_check = jQuery(".comment_user_name").val().replace(/ /g,'');
	     $comment_user_email_check = jQuery(".comment_user_email").val().replace(/ /g,'');
	     if ($comment_user_check=="") {
		comment_user_name = "Guest";
	     }
	     if($comment_user_email_check=="")
	     {
		comment_user_email = "guest@noreply.com";
	     }
	    var ajax_url = front_js_vars.ajax_url;
            data = {
            action: 'add_kronopress_comment',
            comment_txt: comment_txt,
            comment_video_id: comment_video_id,
	    comment_user_name : comment_user_name,
	    comment_user_email : comment_user_email
           };
	   jQuery.ajax({
                method: "POST",
                url: ajax_url,
                data: data,
                async:false
              }).done(function( response ) {
	
	jQuery(".krono_comment_list").append(response);
	jQuery(".krono_comment_list .krono_single_comment").last().hide().fadeIn(1000);
	jQuery('html,body').animate({
        scrollTop: jQuery(".krono_single_comment").last().offset().top-10},'slow');
	jQuery(".loading_post_comments").fadeOut(200);
	     jQuery(".comment_user_name").val("");
	     jQuery(".comment_user_email").val("");
	     jQuery("#krono_comment_box").val("");
	});
	   
	    }
     });

  jQuery(".li_di_box").click(function(){
     var is_like = jQuery(this).find(".is_like").val();
     var video_id = jQuery(this).parent().find(".like_dislike_video_id").val();
     var user_vote = jQuery(this).parent().find(".user_vote").val();
     var can_vote = true;
     var reason = "";
       if (jQuery(this).hasClass("like_box") && user_vote=="like" ) {
	can_vote = false;
	reason = "You have already liked this video!";
     }
      if (jQuery(this).hasClass("dislike_box") && user_vote=="dislike" ) {
	can_vote = false;
	reason = "You have already disliked this video!";
     }
     
     
     if (can_vote) {
	 jQuery(this).parent().find(".like_message").text("...");
	if(jQuery(this).hasClass("like_box"))
	{
	    var btn="like";
	    var like_count = jQuery(this).find(".like_count").text();
	    like_count = parseInt(like_count);
	    jQuery(this).find(".like_count").text(like_count+1);
	    if (user_vote!="no_vote") {
		 var dislike_count = jQuery(this).parent().find(".dislike_count").text();
		  dislike_count = parseInt(dislike_count);
		  jQuery(this).parent().find(".dislike_count").text(dislike_count-1);
	    }
	}
	else
	{
	    var btn="dislike";
	     var dislike_count = jQuery(this).find(".dislike_count").text();
	    dislike_count = parseInt(dislike_count);
	    jQuery(this).find(".dislike_count").text(dislike_count+1);
	    if (user_vote!="no_vote") {
		 var like_count = jQuery(this).parent().find(".like_count").text();
		  like_count = parseInt(like_count);
		  jQuery(this).parent().find(".like_count").text(like_count-1);
	    }
	}
     
     var ajax_url = front_js_vars.ajax_url;
            data = {
            action: 'do_like_dislike',
            is_like: is_like,
	    video_id:video_id
	   
           };
	     jQuery.ajax({
                method: "POST",
                url: ajax_url,
                data: data,
                async:false
              }).done(function( response ) {
		if (response=="Success") {
		    if (btn =="like") {
			var message = "You liked this video.";
		     jQuery(".user_vote").val("like");
		    }
		    else
		    {
			var message = "You disliked this video.";
			jQuery(".user_vote").val("dislike");
			
		    }
		}
		else
		{
		    var message = "Error!";
		}
		 jQuery(".like_message").html(message);
		
	     });
     }
     else
     {
	jQuery(this).parent().find(".like_message").html(reason);
     }
     
  });

  jQuery(".load_more_comments").click(function(){
    
      jQuery(".loading_older_comments").show();
     var last_comment_id = jQuery(".krono_comment_list .last_comment_id").first().val();
    var comment_video_id = jQuery(".krono_comment_video_id").val();
   // var last_comment_id = jQuery(".last_comment_id").val();
    var comments_per_page = jQuery(".comments_per_page").val();
       var ajax_url = front_js_vars.ajax_url;
            data = {
            action: 'load_older_comments',
            comment_video_id: comment_video_id,
            last_comment_id: last_comment_id,
	    comments_per_page : comments_per_page
	   
           };
	   jQuery.ajax({
                method: "POST",
                url: ajax_url,
                data: data,
                async:false
              }).done(function( response ) {
	 if (response=="null") {
	   jQuery(".krono_comment_list").prepend("<div class='no_more_comments'>No more commentes to load.</div>");
	   jQuery(".load_more_comments").fadeOut(200);
	 }
	 else
	 {
	 count_index = comments_per_page-1;
	 jQuery(".krono_comment_list").prepend(response);
	 jQuery(".krono_comment_list .krono_single_comment:lt("+count_index+")").hide().fadeIn(1000);
	 }
	 
	 jQuery(".loading_older_comments").fadeOut(200);
	
	});
      
       });

 // autoimport_videos();
  
jQuery(".live_streaming_channel_name").click(function(){
var embed_url = jQuery(this).next(".live_embed_url").val();
video_height = jQuery(this).parent().find(".video_height").val();
video_width = jQuery(this).parent().find(".video_width").val();
jQuery(this).siblings(".live_streaming_channel_name").removeClass('selected');
jQuery(this).addClass('selected');
jQuery(this).parent().find(".live_streaming_player").html('<iframe height="'+video_height+'" width="'+video_width+'" src="'+embed_url+'">');
});


jQuery( document ).on( 'click', '.sloode_next', function() {
var page_no = jQuery(this).attr("data-page_no");
var per_page = jQuery("#sloode_search_per_page").val();
var search_key = jQuery("#sloode_search_key").val();
var search_type = jQuery("#sloode_search_type ").val();
var word_limit = jQuery("#sloode_word_limit").val();
var page_count = jQuery("#page_count").val();
var last_post_id = 0;
page_no = parseInt(page_no);
var next_page_no = page_no+1;
var prev_page_no = page_no-1;
var page_total = jQuery(".sloode_search_wrap").length;
if (page_total>=page_no) {
       jQuery(".sloode_search_wrap").hide();
       jQuery(".sloode_search_page_"+page_no).fadeIn(300);
      var search_page_top = jQuery(".sloode_search_page_container").offset();
      jQuery("html,body").animate({scrollTop: search_page_top.top-100},500);
      
}
else{
        jQuery(".sloode_search_loader").show();
        jQuery(".sloode_search_next_prev").hide();
       var search_mode="wp";
       var ws_post_count = jQuery(".ws_mode").length;
     if (ws_post_count>0) {
       search_mode = "ws";
       last_post_id = jQuery(".ws_mode").last().find(".ws_search_last_post_id").val();
      // alert(last_post_id);
     }
    
        var ajax_url = front_js_vars.ajax_url;
        data = {
            action: 'search_next',
            page_no: page_no,
            search_mode: search_mode,
            per_page:per_page,
            search_key:search_key,
            word_limit:word_limit,
            search_type:search_type,
            page_count : page_count,
            last_post_id:last_post_id
           };
          jQuery.ajax({
                method: "POST",
                url: ajax_url,
                data: data,
                async:false
              }).done(function( response ) {
	//alert(response);
         jQuery(".sloode_search_page_container").append(response);
          var search_page_top = jQuery(".sloode_search_page_container").offset();
          jQuery("html,body").animate({scrollTop: search_page_top.top-100},500);
         
         jQuery(".sloode_search_wrap").hide();
       jQuery(".sloode_search_page_"+page_no).fadeIn(300);
	 jQuery(".sloode_search_loader").hide();
          jQuery(".sloode_search_next_prev").fadeIn(300);
	});
          
}
if (page_no == page_count ) {
   jQuery(this).hide();    
       
}
       jQuery(".sloode_prev").show();
       jQuery(this).attr("data-page_no",next_page_no);
       jQuery(".sloode_prev").attr("data-page_no",prev_page_no);

});

jQuery(".sloode_prev").click(function(){
      
var page_no = jQuery(this).attr("data-page_no");

page_no = parseInt(page_no);
var next_page_no = page_no+1;
var prev_page_no = page_no-1;
       jQuery(".sloode_search_wrap").hide();
       jQuery(".sloode_search_page_"+page_no).fadeIn(500);
       
       jQuery(this).attr("data-page_no",prev_page_no);
       jQuery(".sloode_next").attr("data-page_no",next_page_no);
       if (page_no==1)
       {
              jQuery(this).hide();
       }
       var search_page_top = jQuery(".sloode_search_page_container").offset();
          jQuery("html,body").animate({scrollTop: search_page_top.top-100},500);
          jQuery(".sloode_next").show(); 

});

});

function autoimport_videos()
{
     setInterval(function(){
    
    import_kronopress_videos();
                
        }, 30000);
    
}
function import_kronopress_videos()
{
      var ajax_url = front_js_vars.ajax_url;
     jQuery.ajax({
                method: "POST",
                url: ajax_url,
                data: {
			    action : 'autoimport_videos'
		     },
                async:false
              }).done(function( response ){
         
        });
    
}