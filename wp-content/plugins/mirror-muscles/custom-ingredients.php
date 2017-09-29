<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Ingredients_Table extends WP_List_Table
{

    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'ingredient',
            'plural' => 'ingredients',
        ));
    }


    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }


    function column_user_id($item)
    {

        $actions = array(
            'edit' => sprintf('<a href="?page=ingredients_form&id=%s">%s</a>', $item['id'],'Edit'),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'],'Delete'),
        );

        return sprintf('%s %s',
            bp_get_profile_field_data('field=1&user_id='.$item['user_id']).' '.bp_get_profile_field_data('field=2&user_id='.$item['user_id']),
            $this->row_actions($actions)
        );
    }


    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }


    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'user_id' => 'Name',
            'name' => 'Ingredient Name',
            'updated' => 'Updated',
        );
        return $columns;
    }


    function get_sortable_columns()
    {
        $sortable_columns = array(
            'user_id' => array('user_id', true),
            'name' => array('name', false),
            'updated' => array('updated', false),
        );
        return $sortable_columns;
    }

    
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }


    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_ingredients';

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }


    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_ingredients'; // do not forget about tables prefix

        $per_page = 5; // constant, how much records will be shown per page

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'name';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}


function ingredients_admin_menu()
{
    add_menu_page('Ingredients', 'Ingredients', 'activate_plugins', 'ingredients', 'ingredients_page_handler','dashicons-shield-alt');
    add_submenu_page('ingredients','Ingredients', 'Ingredients', 'activate_plugins', 'ingredients', 'ingredients_page_handler');
    // add new will be described in next part
    add_submenu_page('ingredients', 'Add new', 'Add new', 'activate_plugins', 'ingredients_form', 'ingredients_form_page_handler');
}

add_action('admin_menu', 'ingredients_admin_menu');


function ingredients_page_handler()
{
    global $wpdb;

    $table = new Ingredients_Table();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf('Items deleted: %d', count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php echo 'Ingredients'?> <a class="add-new-h2"
                                 href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=ingredients_form');?>"><?php echo 'Add new'?></a>
    </h2>
    <?php echo $message; ?>

    <form id="ingredients-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>

</div>
<?php
}


function ingredients_form_page_handler()
{
    global $wpdb;
    global $bp;
    $table_name = $wpdb->prefix . 'custom_ingredients'; // do not forget about tables prefix

    $message = '';
    $notice = '';

    // this is default $item which will be used for new records
    $default = array(
        'id' => 0,
        'user_id' => $bp->loggedin_user->id,
        'name' => '',
        'number_of_units' => '',
        'measurement_description' => '', 
        'calories' => '',
        'fat' => '',
        'saturated_fat' => '',
        'polyunsaturated_fat' => '',
        'monounsaturated_fat' => '',
        'trans_fat' => '',
        'cholesterol' => '',
        'sodium' => '',
        'potassium' => '',
        'carbohydrate' => '',
        'fiber' => '',
        'sugar' => '',
        'protein' => '',
        'vitamin_a' => '',
        'vitamin_c' => '',
        'calcium' => '',
        'iron' => ''
    );

    // here we are verifying does this request is post back and have correct nonce
    if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        // combine our default item with request params
        $item = shortcode_atts($default, $_REQUEST);
        // validate data, and if all ok save item to database
        // if id is zero insert otherwise update
        $item_valid = validate_ingredient($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = __('Item was successfully saved', 'custom_table_example');
                } else {
                    $notice = __('There was an error while saving item', 'custom_table_example');
                }
            } else {
                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                if ($result) {
                    $message = 'Item was successfully updated';
                } else {
                    $notice = 'There was an error while updating item';
                }
            }
        } else {
            // if $item_valid not true it contains error message(s)
            $notice = $item_valid;
        }
    }
    else {
        // if this is not post back we load item to edit or give new one to create
        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
            if (!$item) {
                $item = $default;
                $notice = 'Item not found';
            }
        }
    }

    // here we adding our custom meta box
    add_meta_box('ingredients_form_meta_box', 'Ingredient data', 'ingredients_form_meta_box_handler', 'ingredient', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php echo 'Ingredient';?> <a class="add-new-h2"
                                href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=ingredients');?>"><?php echo 'back to list';?></a>
    </h2>

    <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo $message ?></p></div>
    <?php endif;?>

    <form id="form" method="POST">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
        <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
        <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

        <div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    <?php /* And here we call our custom meta box */ ?>
                    <?php do_meta_boxes('ingredient', 'normal', $item); ?>
                    <input type="submit" value="<?php echo 'Save';?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}


function ingredients_form_meta_box_handler($item)
{
    ?>
<input type="hidden" name="user_id" value="<?php echo esc_attr($item['user_id']);?>">
<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row"><label for="name">Name</label></th>
        <td>
            <input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>" size="50" class="code" placeholder="Ingredient name" required>
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row">
            <label for="number_of_units">Serving number</label>
        </th>
        <td>
            <input type="number" min="1" step="any" style="width: 25%" name="number_of_units" id="number_of_units" value="<?php echo $item['number_of_units'];?>" size="50" class="code" placeholder="Serving number" required>
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="measurement_description">Serving description</label></th>
        <td>
            <input type="text" name="measurement_description" id="measurement_description" style="width: 25%" value="<?php echo $item['measurement_description'];?>" size="50" class="code" placeholder="Measurment description" required>
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="calories">Calories, kcal</label></th>
        <td>
            <input type="number" min="1" step="any" name="calories" id="calories" style="width: 25%" value="<?php echo $item['calories'];?>" size="50" class="code" placeholder="Calories" required>
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="fat">Total fat, g</label></th>
        <td>
            <input type="number" min="0" step="any" name="fat" id="fat" style="width: 25%" value="<?php echo $item['fat'];?>" size="50" class="code" placeholder="Total fat" required>
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="saturated_fat">Saturated fat, g</label></th>
        <td>
            <input type="number" min="0" step="any" name="saturated_fat" id="saturated_fat" style="width: 25%" value="<?php echo $item['saturated_fat'];?>" size="50" class="code" placeholder="Saturated fat">
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="polyunsaturated_fat">Polyunsaturated fat, g</label></th>
        <td>
                <input type="number" min="0" step="any" name="polyunsaturated_fat" id="polyunsaturated_fat" style="width: 25%" value="<?php echo $item['polyunsaturated_fat'];?>" size="50" class="code" placeholder="Polyunsaturated fat">
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="monounsaturated_fat">Monounsaturated fat, g</label></th>
        <td>
                <input type="number" min="0" step="any" name="monounsaturated_fat" id="monounsaturated_fat" style="width: 25%" value="<?php echo $item['monounsaturated_fat'];?>" size="50" class="code" placeholder="Monounsaturated fat">
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="trans_fat">Trans fat, g</label></th>
        <td>
            <input type="number" min="0" step="any" name="trans_fat" id="trans_fat" style="width: 25%" value="<?php echo $item['trans_fat'];?>" size="50" class="code" placeholder="Trans fat">
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="cholesterol">Cholesterol, mg</label></th>
        <td>
            <input type="number" min="0" step="any" name="cholesterol" id="cholesterol" style="width: 25%" value="<?php echo $item['cholesterol'];?>" size="50" class="code" placeholder="Cholesterol" required>
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="sodium">Sodium, mg</label></th>
        <td>
            <input type="number" min="0" step="any" name="sodium" id="sodium" style="width: 25%" value="<?php echo $item['sodium'];?>" size="50" class="code" placeholder="Sodium" required>
        </td>
    </tr>
    <tr>
        <td valign="top" scope="row"><label for="potassium">Potassium, mg</label></th>
        <td>
            <input type="number" min="0" step="any" name="potassium" id="potassium" style="width: 25%" value="<?php echo $item['potassium'];?>" size="50" class="code" placeholder="Potassium">
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="carbohydrate">Total carbohydrate, g</label></th>
        <td>
            <input type="number" min="0" step="any" name="carbohydrate" id="carbohydrate" style="width: 25%" value="<?php echo $item['carbohydrate'];?>" size="50" class="code" placeholder="Total carbohydrate" required>
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="fiber">Dietary fiber, g</label></th>
        <td>
            <input type="number" min="0" step="any" name="fiber" id="fiber" style="width: 25%" value="<?php echo $item['fiber'];?>" size="50" class="code" placeholder="Dietary fiber" required>
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="sugar">Sugars, g</label></th>
        <td>
            <input type="number" min="0" step="any" name="sugar" id="sugar" style="width: 25%" value="<?php echo $item['sugar'];?>" size="50" class="code" placeholder="Sugars" required>
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="protein">Protein, g</label></th>
        <td>
            <input type="number" min="0" step="any" name="protein" id="protein" style="width: 25%" value="<?php echo $item['protein'];?>" size="50" class="code" placeholder="Protein" required>
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="vitamin_a">Vitamin A, %</label></th>
        <td>
                <input type="number" min="0" step="any" name="vitamin_a" id="vitamin_a" style="width: 25%" value="<?php echo $item['vitamin_a'];?>" size="50" class="code" placeholder="Vitamin A">
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="vitamin_c">Vitamin C, %</label></th>
        <td>
            <input type="number" min="0" step="any" name="vitamin_c" id="vitamin_c" style="width: 25%" value="<?php echo $item['vitamin_c'];?>" size="50" class="code" placeholder="Vitamin C">
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="calcium">Calcium, %</label></th>
        <td>
            <input type="number" min="0" step="any" name="calcium" id="calcium" style="width: 25%" value="<?php echo $item['calcium'];?>" size="50" class="code" placeholder="Calcium">
        </td>
    </tr>
    <tr>
        <th valign="top" scope="row"><label for="iron">Iron, %</label></th>
        <td>
            <input type="number" min="0" step="any" name="iron" id="iron" style="width: 25%" value="<?php echo $item['iron'];?>" size="50" class="code" placeholder="Iron">
        </td>
    </tr>
    </tbody>
</table>
<?php
}


function validate_ingredient($item)
{
    //$messages = array();

    //if (empty($item['name'])) $messages[] = __('Name is required', 'custom_table_example');
    //if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format', 'custom_table_example');
    //if (!ctype_digit($item['age'])) $messages[] = __('Age in wrong format', 'custom_table_example');
    //if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
    //if(!empty($item['age']) && !preg_match('/[0-9]+/', $item['age'])) $messages[] = __('Age must be number');
    //...

    //if (empty($messages)) return true;
    //return implode('<br />', $messages);
    return true;
}