<?php $logo = plugins_url('images/sloode.png', dirname(__FILE__)); ?>
<a href="http://www.sloode.com/SiteRegistration?" target="_blank"><img src="<?php echo $logo; ?>" class="sloode_logo"></a>

<h2>Sloode Settings</h2>

<form method="post" action="options.php" class="krono-setting-form">
    <?php
    settings_fields('krono-settings-group');
    do_settings_sections('krono-settings-group');
    if (current_user_can('manage_options')) {
        $krono_player_width = (get_option('krono_player_width') == "") ? '100%' : esc_attr(get_option('krono_player_width'));
        $krono_player_height = (get_option('krono_player_height') == "") ? '400' : esc_attr(get_option('krono_player_height'));
        $enable_autoimport = (get_option('enable_autoimport') == "") ? 'on' : get_option('enable_autoimport');
        $krono_api_url = (get_option('krono_api_url') == "") ? 'http://api.kronopress.com' : get_option('krono_api_url');
        ?>	
        <table>
            <tr>
                <th>Sloode API URL</th>
                <td>
                    <input type="text" name="krono_api_url" class="big_input_box" value="<?php echo $krono_api_url; ?>" /><br/>
                    (Default value is <strong>"http://api.kronopress.com"</strong>. Leave this field to default or blank if you are not sure.<br/>
                    Dont add slash "/" after the url.)<br/>&nbsp;
                </td>
            </tr>
            <tr>
                <th>Sloode API Key</th>
                <td>
                    <input type="text" name="krono_api_key" class="big_input_box" value="<?php echo esc_attr(get_option('krono_api_key')); ?>" /><br/>
                    <?php
                    if (IS_VALID_API == 0) {
                        echo '<div class="error_msg">Please insert valid api key.</div>';
                        echo '<div><i>(If you don\'t have API key, Please register on <a href="http://www.sloode.com/SiteRegistration?" target="_blank">http://www.sloode.com/SiteRegistration?</a> to get the API key.)</i></div>';
                    }
                    ?>
                    <br/>
                </td>
            </tr>
            <tr>
                <th>Sloode Secret Key</th>
                <td>
                    <input type="text" name="krono_api_secret" class="big_input_box" value="<?php echo esc_attr(get_option('krono_api_secret')); ?>" /><br/>
                    <?php
                    if (IS_VALID_SECRET == 0) {
                        echo '<div class="error_msg">Please insert secret key.</div>';
                    }
                    ?>
                    <br/>
                </td>
            </tr>
            <tr>
                <th>Video player page</th>
                <td>
                    <select name="video_player_page_id">
                        <option value="">-Select Page-</option>
                        <?php
                        $player_page_id = get_option('video_player_page_id');
                        $pages = get_pages();
                        foreach ($pages as $page) {
                            $option = '<option value="' . $page->ID . '"' . selected($page->ID, $player_page_id, false) . '>';
                            $option .= $page->post_title;
                            $option .= '</option>';
                            echo $option;
                        }
                        ?>
                    </select><br/>
                    <?php
                    if ($player_page_id == "") {
                        echo '<div class="error_msg">Please Select Video player page.</div>';
                        echo "<br/> Create a page, put shortcode <strong>[show_video_player]</strong> in page editor, Then select that page from above list.";
                    }
                    ?>
                    <br/>
                </td>
            </tr>
            <tr>
                <th>Sloode Search page</th>
                <td>
                    <select name="sloode_search_page_id">
                        <option value="">-Select Page-</option>
                        <?php
                        $sloode_search_page_id = get_option('sloode_search_page_id');
                        $pages = get_pages();
                        foreach ($pages as $page) {
                            $option = '<option value="' . $page->ID . '"' . selected($page->ID, $sloode_search_page_id, false) . '>';
                            $option .= $page->post_title;
                            $option .= '</option>';
                            echo $option;
                        }
                        ?>
                    </select><br/>
                    <?php
                    if ($sloode_search_page_id == "") {
                        echo '<div class="error_msg">Please Select Sloode Search page.</div>';
                        echo "<br/> Create a page, put shortcode <strong>[sloode_search_result]</strong> in page editor, Then select that page from above list.";
                    }
                    ?>
                    <br/>
                </td>
            </tr>
            <tr>
                <th>Sloode Category List Page</th>
                <td>
                    <select name="sloode_category_page_id">
                        <option value="">-Select Page-</option>
                        <?php
                        $sloode_category_page_id = get_option('sloode_category_page_id');
                        $pages = get_pages();
                        foreach ($pages as $page) {
                            $option = '<option value="' . $page->ID . '"' . selected($page->ID, $sloode_category_page_id, false) . '>';
                            $option .= $page->post_title;
                            $option .= '</option>';
                            echo $option;
                        }
                        ?>
                    </select><br/>
                    <?php
                    if ($sloode_category_page_id == "") {
                        echo '<div class="error_msg">Please Select Sloode Category list page.</div>';
                        echo "<br/> Create a page, put shortcode <strong>[sloode_category_list]</strong> in page editor, Then select that page from above list.";
                    }
                    ?>
                    <br/>
                </td>
            </tr>
            <tr>
                <th>Sloode Category Video Page</th>
                <td>
                    <select name="sloode_category_video_page_id">
                        <option value="">-Select Page-</option>
                        <?php
                        $sloode_category_video_page_id = get_option('sloode_category_video_page_id');
                        $pages = get_pages();
                        foreach ($pages as $page) {
                            $option = '<option value="' . $page->ID . '"' . selected($page->ID, $sloode_category_video_page_id, false) . '>';
                            $option .= $page->post_title;
                            $option .= '</option>';
                            echo $option;
                        }
                        ?>
                    </select><br/>
                    <?php
                    if ($sloode_category_video_page_id == "") {
                        echo '<div class="error_msg">Please Select Sloode Category video page.</div>';
                        echo "<br/> Create a page, put shortcode <strong>[sloode_category_video]</strong> in page editor, Then select that page from above list.";
                    }
                    ?>
                    <br/>
                </td>
            </tr>

            <tr>
                <th>Display "Video" tag after title</th>
                <td>

                    <input type="checkbox" name="display_video_tag" value="on" <?php checked(get_option('display_video_tag'), "on"); ?>><br/>

                    <br/>	

                </td>

            </tr>

            <tr>
                <th>Hide video thumb on detail page of:</th>
                <td>
                    <?php $hide_thumb = get_option('hide_thumb'); ?>
                <!--<input type="checkbox" name="hide_thumb" value="on" <?php //checked(get_option('hide_thumb'),"on");       ?>><br/>-->

                    <select name="hide_thumb">
                        <option value="">--Select--</option>
                        <option value="all" <?php selected("all", $hide_thumb, true) ?>>All posts</option>
                        <option value="video" <?php selected("video", $hide_thumb, true) ?>>Videos only</option>
                        <option value="imported" <?php selected("imported", $hide_thumb, true) ?>>Imported videos only</option>
                        <br/>
                    </select>

                    <br/>	

                </td>

            </tr>

            <tr>
                <th>Enable auto import
                    <?php
                    ?>
                </th>
                <td>


                    <input type="radio" name="enable_autoimport" id="on" value="on"     <?php checked($enable_autoimport, "on"); ?> /> ON &nbsp; &nbsp;
                    <input type="radio" name="enable_autoimport" id="off" value="off"     <?php checked($enable_autoimport, "off"); ?> />OFF
                    <br/> Records will be automatically imported after every 100 second if this option is "ON".
                    <br/>		

                </td>

            </tr>


            <tr>
                <th>Default size of video player</th>
                <td>

                    <input type="text" name="krono_player_width" value="<?php echo $krono_player_width; ?>" placeholder="Width" /> &times; <input type="text" name="krono_player_height" value="<?php echo $krono_player_height; ?>" placeholder="Height" /><br/>


                    <br/>
                </td>

            </tr>

            <tr>
                <th>Show city</th>
                <td>
                    <?php
                    if (!taxonomy_exists('city')) {
                        $disabled = "disabled";
                    } else {
                        $disabled = "";
                    }
                    ?>

                    <input type="checkbox" name="display_city" value="on" <?php checked(get_option('display_city'), "on"); ?> <?php echo $disabled; ?> >
                    <br/>
                    (To use this option <b>"Sloode city addon"</b> plugin must be activated.")
                </td>	
            </tr>

        </table>
        <?php
        submit_button();
    } else {
        echo "<br/><br/><div class='error_msg'>You have not sufficient permission to access this page!</div>";
    }
    ?>	
</form>