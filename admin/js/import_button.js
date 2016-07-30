jQuery(document).ready(function(){
         
       import_btn();  
  function import_btn()
  {
         
   jQuery("#import_videos_btn").click(function(){
         var cat_list = new Array();
       var last_video_id = jQuery("#krono_video_id").val();
        jQuery(".import_responce ").html('<h4>Import process has been started...</h4>');
   
     jQuery("#import_videos_btn").hide();
     jQuery("#import_videos_btn").remove();
     
         jQuery(".loading_import_videos").show();
           
           jQuery("#krono_selected_cat input").each(function(){
                 var element_name = jQuery(this).attr('name');
                 var element_val = jQuery(this).attr('value');
                
                 cat_list[element_name] = element_val;
                 
            });
         var cat_data = "";
            for (var key in cat_list) {
            cat_data += key+"="+cat_list[key]+"&";
            }
     
                var ajax_url = admin_js_vars.ajax_url;
     
     data = cat_data + "action=import_videos"+"&last_video_id="+last_video_id;     
     
        jQuery.ajax({
                method: "POST",
                url: ajax_url,
                data: data,
                async:false
              }).done(function( response ){
               jQuery(".loading_import_videos").hide();
               
     jQuery(".import_responce ").html(response);
       import_btn();
       last_video_id = jQuery("#krono_video_id").val();
      
        });
         
       });
  }
   
   
});