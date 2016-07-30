<h2>Sloode shortcode help</h2>

<table class="krono_tbl help_tbl">
    <tr>
        <th>Shortcode</th>
        <th>Description</th>
        <th>Example</th>
    </tr>
    
    <tr>
    <td> [show_latest_video]   </td>
    <td> This shortcode displayes latest videos thumbnail on the page. <br/>
    By default it shows 50 videos on page with load more button.<br/>
    When user click on "Load more" button next 50 records will be displayed.<br/>
    You can set the number of total records to display and number of records to display per page.<br/>
    You can use parameters "per_page" and "count";<br/>
    example : <strong>[show_latest_video per_page=10 count="50"]</strong><br/>
    Above example will display 10 records per page with "load more" button.<br/>
    Load more button will display next 10 records and so on.<br/>
    After loading 50 records "load more" will be disappeared.<br/>
    <strong>Other parameters:</strong><br/>
    <strong>height: </strong> Height of thumbnail. example height="200" (default value: 150) <br/>
    <strong>width:</strong> Width of thumbnail. example height="200" (default value: 150)<br/>
    <strong>show_loadmore:</strong>Use this parameter to hide "Load more" button. Example: show_loadmore="no" (default value: yes)<br/>
    <strong>show_hits:</strong>Use this parameter to hide view count. Example: show_hits="no" (default value: yes)<br/>
    <strong>show_title: </strong>Use this parameter to hide title from thumbnail. Example: show_title="no" (default value: yes)<br/>
    <strong>widget:</strong> use widget="yes" if you are using this shortcode in widget. (default value: no)<br/>
    <strong>play_button:</strong> use play_button="yes" if you want to display play button on thumbnail. (default value: no)<br/>
    <strong>Template:</strong> use this parameter for different layout example: Template="style2"(default value: style1)<br/>
    <br/> 
    </td>
     <td>  <strong>[show_latest_video]</strong>  <br/>(show all records  with 50 records per page.)<br/><br/>
   <strong> [show_latest_video per_page=10 count="50"]</strong><br/> (show 50 records with 10 records per page.)
    <br/><br/>
    <strong>[show_latest_video per_page=10]</strong> <br/>(show all records with 10 records per page.)
     <br/><br/>
    <strong>[show_latest_video count=100]</strong> <br/>(show 100 records with 50 records per page.)
    </td>
    </tr>
    
    <tr>
    <td> [show_videos_by_cat cat_id={category_id}]  </td>
    <td> Use this shortcode to display videos of any perticular category.<br/>
    By default it will display 50 records on page with load more button.<br/>
    When user click on "Load more" button next 50 records will be displayed.<br/>
    You can set the number of total records to display and number or records you want to display per page.<br/>
    You can use parameters "per_page" and "count";<br/>
    example : <strong>[show_videos_by_cat cat_id=5 per_page=10 count="50"]</strong><br/>
    Above example will display 10 records per page with "load more" button.<br/>
    Load more button will display next 10 records and so on.<br/>
    After loading 50 records "load more" will be disappeared.<br/>
    <strong>Other parameters:</strong><br/>
    height, width, show_loadmore, show_title, widget,play_button.<br/>( work same as in [show_latest_video])
    
    <br/> 
    
    </td>
    <td>  <strong>[show_videos_by_cat cat_id=5]</strong>  <br/>(show all records of category 5 with 50 records per page.)<br/><br/>
   <strong> [show_videos_by_cat cat_id=5 per_page=10 count="50"]</strong><br/> (show 50 records of category 5 with 10 records per page.)
    <br/><br/>
    <strong>[show_videos_by_cat cat_id=5 per_page=10]</strong> <br/>(show all records of category 5 with 10 records per page.)
     <br/><br/>
     <strong> [show_videos_by_cat cat_id=5 count="100"]</strong><br/> (show 100 records of category 5 with 50 records per page.)
     <br/><br/>
     <strong> [show_videos_by_cat cat_id="1,2,3" special_cat="7,8,9"]</strong><br/> (show records of from multiple category)
     
    </td>
    </tr>
    
    
    <tr>
    <td> [show_videos_by_series series_id={series_id}]  </td>
    <td> Use this shortcode to display videos of any perticular series.<br/>
   It works similar as "show_videos_by_cat" shortcode.
    <strong>Other parameters :</strong><br/>
    height, width, show_loadmore, show_title, widget.<br/>( work same as in "show_latest_video" and "show_videos_by_cat" )
    
    <br/> 
    
    </td>
    <td>  <strong>[show_videos_by_series series_id=5]</strong>  <br/>(show all records of series 5 with 50 records per page.)<br/><br/>
   <strong> [show_videos_by_series series_id=5 per_page=10 count="50"]</strong><br/> (show 50 records of series 5 with 10 records per page.)
    <br/><br/>
    <strong>[show_videos_by_series series_id=5]</strong> <br/>(show all records of series 5 with 10 records per page.)
     <br/><br/>
     <strong> [show_videos_by_series series_id=5 count="100"]</strong><br/> (show 100 records of series 5 with 50 records per page.)
    </td>
    </tr>
    
    
    
    <tr>
    <td>[break]</td>
    <td>Use this shortcode to insert line break in widget.</td>
    <td><strong>[break]</strong></td>    
    </tr>
    <tr>
    <td> [show_video_player] </td>
    <td> Put this shortcode on the page which you create to display video when you click on thumbnail.<br/>
    You must select that page on "Video player page" in setting option.<br/>
    You can also use this shortcode on any other to play any perticular video on that page by using "video_id" parameter.
    <br/>
    </td>
    <td><strong> [show_video_player]<br/>
    [show_video_player video_id=3615]</strong>
    <br/> <br/>
    <strong> [show_video_player hit_count="no"]
    </strong>
    <br/>(this shortcode will hide hit counter from page.)
        
        
    </td>
    </tr>
    
  
   <tr>
    <td> [show_related_videos] </td>
    <td> This shortcode will display thumbnails of related videos on video player page.<br/>
    Put this shortcode on the page which you create to display video when you click on thumbnail(Video player page).<br/>
    you can also set the number of thumbnails and title by passing "count" and "title" parameter in shortcode.
    <br/>
   
   
    </td>
    <td> <strong>[show_related_videos]</strong> <br/>(It will display 10 videos of current category)<br/><br/>
  <strong> [show_related_videos count=20]</strong> <br/>(Use "count" parameter to set number of videos to show.)<br/><br/>
  <strong> [show_related_videos title="Related Videos"]</strong> <br/>(Use "title" to set title. By default it will display "Related Videos" if you use the shortcode without title parameter.)<br/><br/>
    </td>
    </tr>
    
    
   <tr>
    <td> [embed_video id={video_id}] </td>
    <td> Use this shortcode to play video in any page or post.<br/>
    You can also insert this code in editor using "Insert Video" button.
    
   
   
    </td>
    <td>
      <strong> [embed_video id=1123]</strong> <br/>
        you can also set the height and width of player <br/>  <strong>[embed_video id=1123 height=200 width=200] <br/></strong>
    </td>
    </tr>
   
    <tr>
    <td> [show_video_player_by_cat cat_id={category_id}] </td>
    <td> show video player list by category id.<br/>
    <strong>parameters:</strong> <br/>
    <strong>count:</strong> No. of videos to display in list. Example: count=10  (default value: 10)<br/>
	<strong>cat_id</strong>(required) Category id, you can also insert multiple category ids separated by comma. Example: cat_id="2,3,4"<br/>
	<strong>height:</strong> Height of the player. Example: height=100 (default value: 200).<br/>
	<strong>width:</strong> width of the player. Example: width=100 (default value: 200).<br/>
	<strong>show_title:</strong>you can set if you want to show title or not. Example: show_title="yes" (default value: "no").<br/>
            
   
   
    </td>
    <td>
      <strong> [show_video_player_by_cat cat_id="3,4,2" count=10 height=200 width=200]</strong> <br/>
       
    </td>
    </tr>
   
   
    <tr>
    <td> [live_streaming channel_id={channel ID}] </td>
    <td> Show live streaming on page<br/>
    <strong>parameters:</strong> <br/>
    <strong>channel_id:</strong>(required) channel_id. Example channel_id=6<br/>
	<strong>width</strong> Width of iframe. Example: width=200 (defaul value: 80%;)<br/>
	<strong>height:</strong> Height of iframe.. Example: height=100 (default value: 400).<br/>
	    </td>
    <td>
      <strong> [live_streaming channel_id=6 height=200 width=200]</strong> <br/>
       
    </td>
    </tr>
    
     <tr>
    <td> [sloode_search_form] </td>
    <td> Display search form in widget or page.<br/>
   
	    </td>
    <td>
      <strong> [sloode_search_form]</strong> <br/>
       
    </td>
    </tr>
   
   <tr>
    <td> [sloode_search_result] </td>
    <td> Use this shortcode to display search result.<br/>
    This shortcode display combined search result from wordpress and webservice.<br/>
    <strong>parameters:</strong> <br/>
    <strong>per_page:</strong> You can set how many post you want to display per page. Example: per_page=10 (defaul value: 80%)<br/>
    <strong>word_limit:</strong> Word limit for description Example: word_limit=20 (defaul value: 55)
    <br/> 
    <strong>How to use:</strong> <br/>
    1-Create a wordpress page
    2- Put shortcode [sloode_search_result] on this page.
    3- Go to Sloode Video >> Settings. You will find a dropdown "Sloode Search page", Select the page that you just created.
    
    </td>
    <td>
      <strong> [live_streaming channel_id=6 height=200 width=200]</strong> <br/>
       
    </td>
    </tr>
   
   
    </table>
