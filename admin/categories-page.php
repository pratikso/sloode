<h2>Video Categories</h2>


<form method="post" action="" >

    <input type="submit" class="button" name="refresh_cat" value="Refresh Categories">

</form>

<br/>

<label>New Category: </label>
<form method="post" action="" >
    <input type="text" name="krono_new_cat" class="krono_new_cat" placeholder="Category Name">
    <input type="submit" class="button" name="add_new_krono_cat" value="Add Category">
    <a href="<?php echo admin_url('admin.php?page=categories_groups'); ?>" target="_blank" class="button" title="Category Groups">Category Groups</a>
</form>	


<div class="wait"><i><b>Plase wait...</b></i></div>

<div class="category_add_success success_msg">
    <?php if (isset($_REQUEST['krono_new_cat'])) { ?>
        <i><b>New category added successfully.</b></i>
    <?php } ?>
</div>

<div id="krono_cat_list_tbl">
    <form method="post" action="options.php" class="krono-category-form">
        <table class="krono_cat_list_tbl">
            <tr>
                <th class="id_col"> ID </th>
                <th> Name </th>
                <th>Map with</th>
                <th> </th>
            </tr>
            <tr>
                <th colspan=4> <strong>Normal Categories </strong> </th>
            </tr>
            <?php
            settings_fields('krono_category_setting_group');
            do_settings_sections('krono_category_setting_group');

            $cat_arr = get_kronopress_video_cat();
            foreach ($cat_arr as $cat) {

                if ($cat['Type'] == "Normal") {
                    $map_with = get_option('map_cat_' . $cat['CategoryId']);
                    ?>
                    <tr class="cat_row_<?php echo $cat['CategoryId']; ?>">
                        <td> <?php echo $cat['CategoryId']; ?> </td>
                        <td> <?php echo $cat['CategoryName']; ?> </td>
                        <td><?php
                            $args = array(
                                'hide_empty' => 0
                            );
                            $categories = get_categories($args);
                            echo '<select name="map_cat_' . $cat['CategoryId'] . '">';
                            echo "<option value=''>-Select-</option>";
                            foreach ($categories as $category) {
                                echo "<option value='" . $category->term_id . "'" . selected($map_with, $category->term_id, false) . " >" . $category->name . "</option>";
                            }
                            echo "</select>";
                            ?></td>

                        <td> <input type="button" value="Delete" class="delete_cat_btn" id="delete_cat_<?php echo $cat['CategoryId']; ?>">
                            <input type="hidden" name="delete_cat" class="delete_cat_<?php echo $cat['CategoryId']; ?>" value="<?php echo $cat['CategoryId']; ?>">
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            <tr class="special_cat_tr" style="background: #ccc;">
                <th colspan=4> <strong>Special Categories </strong> </th>
            </tr>
            <?php
            foreach ($cat_arr as $cat) {
                if ($cat['Type'] == "Special") {
                    $map_with = get_option('map_cat_' . $cat['CategoryId']);
                    ?>
                    <tr class="cat_row_<?php echo $cat['CategoryId']; ?> special_cat_tr">
                        <td> <?php echo $cat['CategoryId']; ?> </td>
                        <td> <?php echo $cat['CategoryName']; ?> </td>
                        <td><?php
                            $args = array(
                                'hide_empty' => 0
                            );
                            $categories = get_categories($args);
                            echo '<select name="map_cat_' . $cat['CategoryId'] . '">';
                            echo "<option value=''>-Select-</option>";
                            foreach ($categories as $category) {
                                echo "<option value='" . $category->term_id . "'" . selected($map_with, $category->term_id, false) . " >" . $category->name . "</option>";
                            }
                            echo "</select>";
                            ?></td>

                        <td> <input type="button" value="Delete" class="delete_cat_btn" id="delete_cat_<?php echo $cat['CategoryId']; ?>">
                            <input type="hidden" name="delete_cat" class="delete_cat_<?php echo $cat['CategoryId']; ?>" value="<?php echo $cat['CategoryId']; ?>">
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
