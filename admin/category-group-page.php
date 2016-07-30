<style type="text/css">
    .category_group_page_container{padding-top: 50px; padding-bottom: 30px;}
    .dropdown {margin: 0;}
    .dropdown dd,.dropdown dt {margin: 0px;padding: 0px;}
    .dropdown ul {margin: -1px 0 0 0;}
    .dropdown dd {position: relative;}
    .dropdown a,.dropdown a:visited {color: #fff;text-decoration: none;outline: none;font-size: 12px;}
    .dropdown dt a {background-color: #4F6877;display: block;padding: 8px 20px 5px 10px;min-height: 25px;line-height: 24px;overflow: hidden;border: 0;width: 500px;max-width: 100%;box-sizing: border-box;}
    .dropdown dt a span,.multiSel span {cursor: pointer;display: inline-block;padding: 0 3px 2px 0;}
    .multiSel {margin: 0;}
    .dropdown dd ul {background-color: #4F6877;border: 0;color: #fff;display: none;left: 0px;padding: 2px 15px 2px 5px;position: absolute;top: 2px;width: 500px;list-style: none;height: 200px;overflow: auto;box-sizing: border-box;}
    .dropdown span.value {display: none;}
    .dropdown dd ul li a {padding: 5px;display: block;}
    .dropdown dd ul li a:hover {background-color: #fff;}
    .cat_group_row{display: block; width: 100%; border-bottom: 1px solid #000000; padding: 10px;}
    .cat_group_row:after{clear: both;float: none;content: '';height: 1px; max-height: 1px; width: 100%; display: block;}
    .cat_group_name{display: block; float: left; width: 30%; min-width: 200px;}
    .cat_list{display: block; float: left; width: 55%; min-width: 400px;}
    .cat_action{display: block; float: left; width:15%;}
    .category_group_page_container * {box-sizing: border-box;}
    .mutliSelect label {display: block;}
</style>
<script type="text/javascript">

</script>
<div class="category_group_page_container">
    <div class="cat_group_container"></div>
    <button class="button" id="add_group_btn">Add Group</button>
    <button class="button" id="save_group_btn">Save Groups</button>
    <div class="feedback"></div>
</div>
<div id="row_sample" style="display: none;">
    <div class="cat_group_row">
        <div class="cat_group_name">
            <input type="text" class="group_name" value="">
        </div>
        <div class="cat_list">
            <dl class="dropdown"> 
                <dt>
                    <a href="#">
                        <span class="hida">Select</span>
                        <p class="multiSel"></p>
                    </a>
                </dt>
                <dd>
                    <div class="mutliSelect">
                        <ul>
                            <?php
                            $cat_arr = get_kronopress_video_cat();
                            //var_dump($cat_arr);
                            foreach ($cat_arr as $cat) {
                                $name = $cat['CategoryName'];
                                $cat_id = $cat['CategoryId'];
                                ?>
                                <li><label><input data-value="<?= $cat_id ?>" class="cat_checkbox" type="checkbox" value="<?= $name ?>" /><?= $name ?></label></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </dd>
            </dl>
        </div>
        <div class="cat_action">
            <button type="button" class="button del_row">Delete Group</button>
        </div>
    </div>
</div>
<script type="text/javascript">
    var all_cat_data = <?php echo json_encode($cat_arr); ?>;
    var cat_group_data = <?php echo json_encode(get_option('category_groups')); ?>;
    function in_array(value, array) {
        var tamp = jQuery.inArray(value, array);
        if (tamp === -1) {
            return false;
        } else {
            return true;
        }
    }
    jQuery(function () {
        jQuery('#add_group_btn').click(function () {
            var row_html = jQuery('#row_sample').html();
            jQuery('.cat_group_container').append(row_html);
        });
        jQuery('#save_group_btn').click(function () {
            var group_all_data = [];
            jQuery('.cat_group_container .cat_group_row').each(function (index) {
                var group_name = jQuery(this).find('input.group_name').val();
                var group_cate_checkbox = jQuery(this).find('input.cat_checkbox:checked');
                var group_cate = [];
                group_cate_checkbox.each(function () {
                    group_cate.push(jQuery(this).data('value'));
                });
                var single_group_data = {
                    group_name: group_name,
                    group_cate: group_cate
                };
                group_all_data.push(single_group_data);
            });
            var data = {
                action: 'save_category_group',
                category_groups: group_all_data
            };
            jQuery.post(ajaxurl, data, function (response) {
                var feed_text = '';
                if(response === 'true'){
                    feed_text = 'Groups Updated';
                } else{
                    feed_text = 'Groups Not Updated there was an error or there is no chenges in groups';
                }
                jQuery('.feedback').html(feed_text);
            });
        });
    });
    jQuery(document).on('click', '.del_row', function () {
        jQuery(this).parents('.cat_group_row').remove();
    });
    jQuery(document).on('click', ".dropdown dt a", function (e) {
        e.preventDefault();
        jQuery(this).parent().next('dd').find('ul').slideToggle('fast');
    });
    jQuery(document).on('click', ".dropdown dd ul li a", function (e) {
        e.preventDefault();
        jQuery(".dropdown dd ul").hide();
    });
    function getSelectedValue(id) {
        return jQuery("#" + id).find("dt a span.value").html();
    }

    jQuery(document).bind('click', function (e) {
        var $clicked = jQuery(e.target);
        if (!$clicked.parents().hasClass("dropdown")) {
            jQuery(".dropdown dd ul").hide();
        }
    });
    jQuery(document).on('change', '.mutliSelect input[type="checkbox"]', function () {
        var title = jQuery(this).closest('.mutliSelect').find('input[type="checkbox"]').val(),
                title = jQuery(this).val() + ",";
        var select_container = jQuery(this).parents('dd').prev('dt');
        if (jQuery(this).is(':checked')) {
            var html = '<span title="' + title + '">' + title + '</span>';
            select_container.find('.multiSel').append(html);
            select_container.find(".hida").hide();
        } else {
            select_container.find('.multiSel span[title="' + title + '"]').remove();
            var tamp_var = select_container.find('.multiSel').html();
            if (tamp_var === '') {
                select_container.find('a .hida').show();
            }
        }
    });

    jQuery(function () {
        var input_html = '';
        if (cat_group_data.length > 0) {
            jQuery.each(cat_group_data, function (index, value) {
                var save_cat_array = value.group_cate;
                input_html += '<div class="cat_group_row">';
                input_html += '<div class="cat_group_name"><input type="text" class="group_name" value="' + value.group_name + '"></div>';
                input_html += '<div class="cat_list"><dl class="dropdown">';
                input_html += '<dt><a href="#"><p class="multiSel">';
                jQuery.each(all_cat_data, function (index, value) {
                    var ntostring = value.CategoryId;
                    ntostring = ntostring.toString();
                    if (in_array(ntostring, save_cat_array)) {
                        input_html += '<span title="' + value.CategoryName + ',">' + value.CategoryName + ',</span>';
                    }
                });
                input_html += '</p></a></dt>';
                input_html += '<dd><div class="mutliSelect"><ul>';
                jQuery.each(all_cat_data, function (index, value) {
                    var CategoryName = value.CategoryName;
                    var CategoryId = value.CategoryId;
                    CategoryId = CategoryId.toString();
                    if (in_array(CategoryId, save_cat_array)) {
                        input_html += '<li><label><input data-value="' + CategoryId + '" class = "cat_checkbox" type = "checkbox" value = "' + CategoryName + '" checked="checked" />' + CategoryName + ' </label></li>';
                    } else {
                        input_html += '<li><label><input data-value="' + CategoryId + '" class = "cat_checkbox" type = "checkbox" value = "' + CategoryName + '" />' + CategoryName + ' </label></li>';
                    }
                });
                input_html += '</ul></div></dd></dl></div>';
                input_html += '<div class="cat_action"><button type="button" class="button del_row">Delete Group</button></div>';
                input_html += '</div>';

            });
        } else {
            input_html = jQuery('#row_sample').html();
        }
        jQuery('.cat_group_container').html(input_html);
    });
</script>