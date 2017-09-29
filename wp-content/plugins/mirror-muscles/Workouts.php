
<?php 


if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Exercises extends WP_List_Table
{

    public function get_muscle_name($ids){
        global $wpdb;
        $results = $wpdb->get_results(" SELECT name FROM {$wpdb->prefix}workout_muscles WHERE id IN(".$ids.")", ARRAY_N);
        return implode(', ',array_map(function($i) {return $i[0];}, $results));
    }

    public function get_equipment_name($ids){
        global $wpdb;
        $results = $wpdb->get_results(" SELECT name FROM {$wpdb->prefix}workout_equipment WHERE id IN(".$ids.")", ARRAY_N);
        return implode(', ',array_map(function($i) {return $i[0];}, $results));
    }


    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'exercise',
            'plural' => 'exercises',
        ));
    }


    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    function column_muscles($item)
    {   
        return $this->get_muscle_name(implode(', ', json_decode($item['muscles'])));
    }

    function column_muscles_secondary($item)
    {   
        return $this->get_muscle_name(implode(', ', json_decode($item['muscles_secondary'])));
    }

    function column_equipment($item)
    {
        return $this->get_equipment_name(implode(', ', json_decode($item['equipment'])));
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
            'name' => 'Name',
            'muscles' => 'Muscles',
            'muscles_secondary' => 'Secondary Muscles',
            'equipment' => 'Equipment'
        );
        return $columns;
    }

     function column_name($item)
    {
       $actions = array(
            'edit' => sprintf('<a href="?page=exercises_form&id=%s">%s</a>', $item['id'],'Edit'),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'],'Delete'),
        );

        return sprintf('%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
        
    }



    function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' => array('name', true)
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
        $table_name = $wpdb->prefix . 'workout_exercise';

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
        $table_name = $wpdb->prefix . 'workout_exercise';
        $per_page = 20;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();

        
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        $paged = isset($_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1;
        $offset = ( $paged - 1 ) * $per_page;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';

        
        $this->items = $wpdb->get_results($wpdb->prepare("
            SELECT e.* FROM $table_name AS `e` ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $offset), ARRAY_A);

        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}/*ends Exercises*/



class Categories extends WP_List_Table{

    function __construct(){
        global $status, $page;
        parent::__construct(array(
            'singular' => 'exercise_category',
            'plural' => 'exercise_categories',
        ));
    }


    function column_default($item, $column_name){
            return $item[$column_name];
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    function get_columns(){
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'name' => 'Name'
        );
        return $columns;
    }

    function column_name($item){
       $actions = array(
            'edit' => sprintf('<a href="?page=categories_form&id=%s">%s</a>', $item['id'],'Edit'),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'],'Delete'),
        );

        return sprintf('%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
        
    }

    function get_sortable_columns(){
        $sortable_columns = array(
            'name' => array('name', true)
        );
        return $sortable_columns;
    }

    function get_bulk_actions(){
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action()    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'workout_exercisecategory';

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    function prepare_items(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'workout_exercisecategory';
        $per_page = 20;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");
        $paged = isset($_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1;
        $offset = ( $paged - 1 ) * $per_page;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
        $this->items = $wpdb->get_results($wpdb->prepare("
            SELECT e.* FROM $table_name AS `e` ORDER BY $orderby $order LIMIT %d OFFSET %d",
            $per_page, $offset), ARRAY_A);
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}/*end Categories*/




class Muscles extends WP_List_Table{

    function __construct(){
        global $status, $page;
        parent::__construct(array(
            'singular' => 'muscle',
            'plural' => 'muscles',
        ));
    }

    function column_default($item, $column_name){
        return $item[$column_name];
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    function get_columns(){
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => 'Name',
            'is_front'=>'Front or Back'
        );
        return $columns;
    }

    function column_name($item){
       $actions = array(
            'edit' => sprintf('<a href="?page=muscles_form&id=%s">%s</a>', $item['id'],'Edit'),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'],'Delete'),
        );

        return sprintf('%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
        
    }

    function column_is_front($item){
        return ($item['is_front']==1) ? 'Front' : 'Back';
    }

    function get_sortable_columns(){
        $sortable_columns = array(
            'name' => array('name', true),
            'is_front' => array('is_front', true)
        );
        return $sortable_columns;
    }

    function get_bulk_actions(){
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action()    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'workout_muscles';

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    function prepare_items(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'workout_muscles';
        $per_page = 20;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");
        $paged = isset($_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1;
        $offset = ( $paged - 1 ) * $per_page;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
        $this->items = $wpdb->get_results($wpdb->prepare("
            SELECT e.* FROM $table_name AS `e` ORDER BY $orderby $order LIMIT %d OFFSET %d",
            $per_page, $offset), ARRAY_A);
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}/*end Muscles*/




class Equipment extends WP_List_Table{

    function __construct(){
        global $status, $page;
        parent::__construct(array(
            'singular' => 'equipment',
            'plural' => 'equipments',
        ));
    }

    function column_default($item, $column_name){
        return $item[$column_name];
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    function get_columns(){
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => 'Name'
        );
        return $columns;
    }

    function column_name($item){
       $actions = array(
            'edit' => sprintf('<a href="?page=equipment_form&id=%s">%s</a>', $item['id'],'Edit'),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'],'Delete'),
        );

        return sprintf('%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
        
    }

    function get_sortable_columns(){
        $sortable_columns = array(
            'name' => array('name', true)
        );
        return $sortable_columns;
    }

    function get_bulk_actions(){
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action()    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'workout_equipment';

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    function prepare_items(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'workout_equipment';
        $per_page = 20;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");
        $paged = isset($_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1;
        $offset = ( $paged - 1 ) * $per_page;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
        $this->items = $wpdb->get_results($wpdb->prepare("
            SELECT e.* FROM $table_name AS `e` ORDER BY $orderby $order LIMIT %d OFFSET %d",
            $per_page, $offset), ARRAY_A);
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}/*end Muscles*/


class Comments extends WP_List_Table{

    function __construct(){
        global $status, $page;
        parent::__construct(array(
            'singular' => 'comment',
            'plural' => 'comments',
        ));
    }

    public function get_exercise_name($id){
        global $wpdb;
        $result = $wpdb->get_var(" SELECT name FROM {$wpdb->prefix}workout_exercise WHERE id=".$id);
        return $result;
    }


    function column_default($item, $column_name){
            return $item[$column_name];
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    function get_columns(){
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'comment' => 'Comment',
            'exercise' => 'Exercise'
        );
        return $columns;
    }

    function column_comment($item){
       $actions = array(
            'edit' => sprintf('<a href="?page=comments_form&id=%s">%s</a>', $item['id'],'Edit'),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'],'Delete'),
        );

        return sprintf('%s %s',
            $item['comment'],
            $this->row_actions($actions)
        );
        
    }

    function column_exercise($item){
        return $this->get_exercise_name($item['exercise']);
    }

    function get_sortable_columns(){
        $sortable_columns = array(
            'comment' => array('comment', true)
        );
        return $sortable_columns;
    }

    function get_bulk_actions(){
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action()    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'workout_exercisecomment';
        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    function prepare_items(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'workout_exercisecomment';
        $per_page = 20;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");
        $paged = isset($_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1;
        $offset = ( $paged - 1 ) * $per_page;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
        $this->items = $wpdb->get_results($wpdb->prepare("
            SELECT e.* FROM $table_name AS `e` ORDER BY $orderby $order LIMIT %d OFFSET %d",
            $per_page, $offset), ARRAY_A);
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}/*end Comments*/

class Images extends WP_List_Table{

    function __construct(){
        global $status, $page;
        parent::__construct(array(
            'singular' => 'image',
            'plural' => 'images',
        ));
    }

    public function get_exercise_name($id){
        global $wpdb;
        $result = $wpdb->get_var(" SELECT name FROM {$wpdb->prefix}workout_exercise WHERE id=".$id);
        return $result;
    }


    function column_default($item, $column_name){
            return $item[$column_name];
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    function get_columns(){
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'image' => 'Image',
            'exercise' => 'Exercise'
        );
        return $columns;
    }

    function column_image($item){
       $actions = array(
            'edit' => sprintf('<a href="?page=images_form&id=%s">%s</a>', $item['id'],'Edit'),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'],'Delete'),
        );

        return sprintf('%s %s',
            '<img style="width:100px; height: auto;" src="'.$item['image'].'">',
            $this->row_actions($actions)
        );
        
    }

    function column_exercise($item){
        return $this->get_exercise_name($item['exercise']);
    }

    function get_sortable_columns(){
        $sortable_columns = array(
            'exercise' => array('exercise', true)
        );
        return $sortable_columns;
    }

    function get_bulk_actions(){
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action()    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'workout_exerciseimage';
        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    function prepare_items(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'workout_exerciseimage';
        $per_page = 20;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");
        $paged = isset($_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1;
        $offset = ( $paged - 1 ) * $per_page;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
        $this->items = $wpdb->get_results($wpdb->prepare("
            SELECT e.* FROM $table_name AS `e` ORDER BY $orderby $order LIMIT %d OFFSET %d",
            $per_page, $offset), ARRAY_A);
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}/*end Images*/



function workouts_admin_menu(){
    add_menu_page('Workouts', 'Workouts', 'manage_options', 'workouts', 'exercises_page_handler','dashicons-editor-paste-word');
    
    add_submenu_page('workouts', 'Exercises', 'Exercises', 'manage_options', 'exercises', 'exercises_page_handler');
    add_submenu_page('exercises', 'Exercises', 'Exercises', 'manage_options', 'exercises_form', 'exercises_form_page_handler');
    
    add_submenu_page('workouts', 'Categories', 'Categories', 'manage_options', 'categories', 'categories_page_handler');
    add_submenu_page('categories', 'Categories', 'Categories', 'manage_options', 'categories_form', 'categories_form_page_handler');
    
    add_submenu_page('workouts', 'Muscles', 'Muscles', 'manage_options', 'muscles', 'muscles_page_handler');
    add_submenu_page('muscles', 'Muscles', 'Muscles', 'manage_options', 'muscles_form', 'muscles_form_page_handler');

    add_submenu_page('workouts', 'Equipment', 'Equipment', 'manage_options', 'equipment', 'equipment_page_handler');
    add_submenu_page('equipment', 'Equipment', 'Equipment', 'manage_options', 'equipment_form', 'equipment_form_page_handler');

    add_submenu_page('workouts', 'Comments', 'Comments', 'manage_options', 'comments', 'comments_page_handler');
    add_submenu_page('comments', 'Comments', 'Comments', 'manage_options', 'comments_form', 'comments_form_page_handler');

    add_submenu_page('workouts', 'Images', 'Images', 'manage_options', 'images', 'images_page_handler');
    add_submenu_page('images', 'Images', 'Images', 'manage_options', 'images_form', 'images_form_page_handler');

}
add_action('admin_menu', 'workouts_admin_menu');



function exercises_page_handler(){
    global $wpdb;

    $table = new Exercises();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf('Items deleted: %d', count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php echo 'Exercises'?>
        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=exercises_form');?>"><?php echo 'Add new'?></a>
    </h2>
    <?php echo $message; ?>

    <form id="exercises-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>

</div>
<?php
}



function exercises_form_page_handler(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'workout_exercise'; // do not forget about tables prefix

    $message = '';
    $notice = '';

    // this is default $item which will be used for new records
    $default = array(
        'id' => 0,
        'name' => '',
        'description' => '',
        'category' => '', 
        'muscles' => '',
        'muscles_secondary' => '',
        'equipment' => ''
    );

    $_REQUEST['muscles'] = json_encode($_REQUEST['muscles'],JSON_NUMERIC_CHECK);
    print_r($_REQUEST['muscles_secondary']);
    if(is_null($_REQUEST['muscles_secondary']))
        $_REQUEST['muscles_secondary'] = array();
    $_REQUEST['muscles_secondary'] = json_encode($_REQUEST['muscles_secondary'],JSON_NUMERIC_CHECK);
    $_REQUEST['equipment'] = json_encode($_REQUEST['equipment'],JSON_NUMERIC_CHECK);
    $_REQUEST['description'] = htmlentities($_REQUEST['description']);
	

    // here we are verifying does this request is post back and have correct nonce
    if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        // combine our default item with request params
        $item = shortcode_atts($default, $_REQUEST);
        // validate data, and if all ok save item to database
        // if id is zero insert otherwise update
        $item_valid = validate_exercise($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
                $item['id'] = $wpdb->insert_id;
				update_option('excercisevideo'.$item['id'], $_POST['excercisevideo']);
                if ($result) {
                    $message = 'Item was successfully saved';
                } else {
                    $notice = 'There was an error while saving item';
                }
            } else {
                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
				update_option('excercisevideo'.$item['id'], $_POST['excercisevideo']);
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
    add_meta_box('exercises_form_meta_box', 'Exercise', 'exercises_form_meta_box_handler', 'exercises', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php echo 'Exercise';?>
        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=exercises');?>"><?php echo 'back to list';?></a>
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
                    <?php do_meta_boxes('exercises', 'normal', $item); ?>
                    <input type="submit" value="<?php echo 'Save';?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}



function exercises_form_meta_box_handler($item)
{   
    global $wpdb;
    $categories = $wpdb->get_results(" SELECT * FROM {$wpdb->prefix}workout_exercisecategory");
    $muscles = $wpdb->get_results(" SELECT * FROM {$wpdb->prefix}workout_muscles");
    $equipments = $wpdb->get_results(" SELECT * FROM {$wpdb->prefix}workout_equipment");
    ?>
<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row"><label for="name">Name</label></th>
        <td>
            <input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>" size="50" class="code" placeholder="Exercise name" required>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row"><label for="name">Category</label></th>
        <td>
            <select id="category" name="category" size="50" class="code" placeholder="Category" required style="height:160px; width:50%;">
                <?php foreach ($categories as $key => $category): ?>
                    <option muliple value="<?php echo $category->id;?>" <?php selected( $category->id, $item['category'] ); ?>><?php echo $category->name;?></option>
                <?php endforeach;?>
            </select>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row"><label for="muscles">Muscles</label></th>
        <td>
            <select id="muscles" name="muscles[]" multiple placeholder="Muscles" style="height:160px; width:50%;">
                <option value="">-</option>
                <?php foreach ($muscles as $key => $muscle): ?>
                    <option value="<?php echo $muscle->id;?>" <?php echo (in_array($muscle->id, json_decode($item['muscles']))) ? 'selected': ''; ?>><?php echo $muscle->name;?></option>
                <?php endforeach;?>
            </select>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row"><label for="muscles_secondary">Secondary muscles</label></th>
        <td>
            <select id="muscles_secondary" name="muscles_secondary[]" multiple placeholder="Secondary muscles" style="height:160px; width:50%;">
                <option value="">-</option>
                <?php foreach ($muscles as $key => $muscle): ?>
                    <option value="<?php echo $muscle->id;?>" <?php echo (in_array($muscle->id, json_decode($item['muscles_secondary']))) ? 'selected': ''; ?>><?php echo $muscle->name;?></option>
                <?php endforeach;?>
            </select>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row"><label for="equipment">Equipment</label></th>
        <td>
            <select id="equipment" name="equipment[]" multiple placeholder="Equipment" style="height:160px; width:50%;">
                <option value="">-</option>
                <?php foreach ($equipments as $key => $equipment): ?>
                    <option value="<?php echo $equipment->id;?>" <?php echo (in_array($equipment->id, json_decode($item['equipment']))) ? 'selected': ''; ?>><?php echo $equipment->name;?></option>
                <?php endforeach;?>
            </select>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row"><label for="description">Description</label></th>
        <td>
            <?php wp_editor( $item['description'], 'description', $settings = array('textarea_name'=>'description', 'media_buttons'=>false) ); ?>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row"><label for="description">Video</label></th>
        <td>
        	<?php $excercisevideo = stripslashes(esc_html(get_option('excercisevideo'.$_REQUEST['id']))); ?>
            <textarea name="excercisevideo" id="excercisevideo"><?php echo $excercisevideo; ?></textarea>
        </td>
    </tr>
    </tbody>
</table>
<?php
}

function validate_exercise(){return true;}



function categories_page_handler(){
    global $wpdb;

    $table = new Categories();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf('Items deleted: %d', count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php echo 'Exercise categories'?>
        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=categories_form');?>"><?php echo 'Add new'?></a>
    </h2>
    <?php echo $message; ?>

    <form id="exercises-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>
</div>
<?php
}

function categories_form_page_handler(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'workout_exercisecategory'; // do not forget about tables prefix

    $message = '';
    $notice = '';

    // this is default $item which will be used for new records
    $default = array(
        'id' => 0,
        'name' => ''
    );

    if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);
        $item_valid = validate_category($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = 'Item was successfully saved';
                } else {
                    $notice = 'There was an error while saving item';
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
    add_meta_box('categories_form_meta_box', 'Categories', 'categories_form_meta_box_handler', 'categories', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php echo 'Exercise category';?>
        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=categories');?>"><?php echo 'back to list';?></a>
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
                    <?php do_meta_boxes('categories', 'normal', $item); ?>
                    <input type="submit" value="<?php echo 'Save';?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}

function categories_form_meta_box_handler($item){   
    ?>
<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row"><label for="name">Name</label></th>
        <td>
            <input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>" size="50" class="code" placeholder="Exercise category name" required>
        </td>
    </tr>
    </tbody>
</table>
<?php
}

function validate_category(){return true;}



function muscles_page_handler(){
    global $wpdb;

    $table = new Muscles();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf('Items deleted: %d', count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php echo 'Exercise categories'?>
        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=muscles_form');?>"><?php echo 'Add new'?></a>
    </h2>
    <?php echo $message; ?>

    <form id="exercises-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>
</div>
<?php
}

function muscles_form_page_handler(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'workout_muscles';
    $message = '';
    $notice = '';
    $default = array(
        'id' => 0,
        'name' => '',
        'is_front' => ''
    );

    if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);
        $item_valid = validate_muscle($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = 'Item was successfully saved';
                } else {
                    $notice = 'There was an error while saving item';
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
    add_meta_box('muscles_form_meta_box', 'Muscles', 'muscles_form_meta_box_handler', 'muscles', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php echo 'Muscle';?>
        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=muscles');?>"><?php echo 'back to list';?></a>
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
                    <?php do_meta_boxes('muscles', 'normal', $item); ?>
                    <input type="submit" value="<?php echo 'Save';?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}

function muscles_form_meta_box_handler($item){   
    ?>
<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row"><label for="name">Name</label></th>
        <td>
            <input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>" size="50" class="code" placeholder="Muscle name" required>
        </td>
    </tr>
    <tr class="form-field">
        <td colspan="2" style="text-align: center;">
        <label>Front:</label> <input name="is_front" type="radio" value="1" required <?php checked( 1, $item['is_front'] ); ?> />
        <label>Back:</label> <input name="is_front" type="radio" value="0" required <?php checked( 0, $item['is_front'] ); ?> />
        </td>
    </tr>
    </tbody>
</table>
<?php
}

function validate_muscle(){return true;}



function equipment_page_handler(){
    global $wpdb;

    $table = new Equipment();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf('Items deleted: %d', count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php echo 'Exercise categories'?>
        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=equipment_form');?>"><?php echo 'Add new'?></a>
    </h2>
    <?php echo $message; ?>

    <form id="exercises-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>
</div>
<?php
}

function equipment_form_page_handler(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'workout_equipment';
    $message = '';
    $notice = '';
    $default = array(
        'id' => 0,
        'name' => ''
    );

    if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);
        $item_valid = validate_muscle($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);

                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = 'Item was successfully saved';
                } else {
                    $notice = 'There was an error while saving item';
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
    add_meta_box('equipment_form_meta_box', 'Equipment', 'equipment_form_meta_box_handler', 'equipment', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php echo 'Equipment';?>
        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=equipment');?>"><?php echo 'back to list';?></a>
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
                    <?php do_meta_boxes('equipment', 'normal', $item); ?>
                    <input type="submit" value="<?php echo 'Save';?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}

function equipment_form_meta_box_handler($item){   
    ?>
<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row"><label for="name">Name</label></th>
        <td>
            <input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>" size="50" class="code" placeholder="Equipment name" required>
        </td>
    </tr>
    </tbody>
</table>
<?php
}

function validate_equipment(){return true;}



function comments_page_handler(){
    global $wpdb;

    $table = new Comments();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf('Items deleted: %d', count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php echo 'Comments'?>
        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=comments_form');?>"><?php echo 'Add new'?></a>
    </h2>
    <?php echo $message; ?>

    <form id="exercises-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>
</div>
<?php
}

function comments_form_page_handler(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'workout_exercisecomment'; // do not forget about tables prefix

    $message = '';
    $notice = '';

    // this is default $item which will be used for new records
    $default = array(
        'id' => 0,
        'comment' => '',
        'exercise' => ''
    );

    if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);
        $item_valid = validate_comment($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = 'Item was successfully saved';
                } else {
                    $notice = 'There was an error while saving item';
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
    add_meta_box('comments_form_meta_box', 'Comments', 'comments_form_meta_box_handler', 'comments', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php echo 'Comment';?>
        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=comments');?>"><?php echo 'back to list';?></a>
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
                    <?php do_meta_boxes('comments', 'normal', $item); ?>
                    <input type="submit" value="<?php echo 'Save';?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}

function comments_form_meta_box_handler($item){
    global $wpdb;
    $exercises = $wpdb->get_results(" SELECT `e`.`id`,`e`.`name`,`c`.`id` AS category_id, `c`.`name` AS category_name FROM {$wpdb->prefix}workout_exercise AS e 
                                    LEFT JOIN {$wpdb->prefix}workout_exercisecategory AS c 
                                    ON `c`.`id` = `e`.`category`
                                    ORDER BY category_name ASC, name ASC", ARRAY_A);

?>
<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row"><label for="exercise">Exercise</label></th>
        <td>
            <select id="exercise" name="exercise" required style="width:50%;">
                <?php
                    $category = '';
                    foreach ($exercises as $exercise) {
                      if ($category != $exercise['category_id']) {
                        if ($category != '') {
                          echo '</optgroup>';
                        }
                        echo '<optgroup label="'.$exercise['category_name'].'">';
                      }
                      echo '<option value="'.$exercise['id'].'" '.selected($exercise['id'],$item['exercise']).'>'.$exercise['name'].'</option>';
                      $category = $exercise['category_id'];    
                    }
                    if ($category != '') {
                      echo '</optgroup>';
                    }
                ?>
            </select>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row"><label for="comment">Comment</label></th>
        <td>
            <input id="comment" name="comment" type="text" style="width: 95%" value="<?php echo esc_attr($item['comment'])?>" size="50" class="code" placeholder="Type exercise comment" required>
        </td>
    </tr>
    </tbody>
</table>
<?php
}

function validate_comment(){return true;}



function images_page_handler(){
    global $wpdb;

    $table = new Images();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf('Items deleted: %d', count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php echo 'Images'?>
        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=images_form');?>"><?php echo 'Add new'?></a>
    </h2>
    <?php echo $message; ?>

    <form id="images-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>
</div>
<?php
}

function images_form_page_handler(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'workout_exerciseimage'; // do not forget about tables prefix

    $message = '';
    $notice = '';

    // this is default $item which will be used for new records
    $default = array(
        'id' => 0,
        'image' => '',
        'exercise' => ''
    );

    if ( ! function_exists( 'wp_handle_upload' ) )
        require_once( ABSPATH . 'wp-admin/includes/file.php' );

    $upload_overrides = array( 'test_form' => false );
    $movefile1 = wp_handle_upload( $_FILES['exerciseimage'], $upload_overrides );

    $_REQUEST['image'] = $_REQUEST['exerciseimage'];

    if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);
        $item_valid = validate_image($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = 'Item was successfully saved';
                } else {
                    $notice = 'There was an error while saving item';
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
    add_meta_box('images_form_meta_box', 'Images', 'images_form_meta_box_handler', 'images', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php echo 'Images';?>
        <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=images');?>"><?php echo 'back to list';?></a>
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
                    <?php do_meta_boxes('images', 'normal', $item); ?>
                    <input type="submit" value="<?php echo 'Save';?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}

function images_form_meta_box_handler($item){
    global $wpdb;
    $exercises = $wpdb->get_results(" SELECT `e`.`id`,`e`.`name`,`c`.`id` AS category_id, `c`.`name` AS category_name FROM {$wpdb->prefix}workout_exercise AS e 
                                    LEFT JOIN {$wpdb->prefix}workout_exercisecategory AS c 
                                    ON `c`.`id` = `e`.`category`
                                    ORDER BY category_name ASC, name ASC", ARRAY_A);

?>
<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row"><label for="exercise">Exercise</label></th>
        <td>
            <select id="exercise" name="exercise" required style="width:50%;">
                <?php
                    $category = '';
                    foreach ($exercises as $exercise) {
                      if ($category != $exercise['category_id']) {
                        if ($category != '') {
                          echo '</optgroup>';
                        }
                        echo '<optgroup label="'.$exercise['category_name'].'">';
                      }
                      echo '<option value="'.$exercise['id'].'" '.selected($exercise['id'],$item['exercise']).'>'.$exercise['name'].'</option>';
                      $category = $exercise['category_id'];    
                    }
                    if ($category != '') {
                      echo '</optgroup>';
                    }
                ?>
            </select>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row"><label for="image">Image</label></th>
        <td>
            <input id="exerciseimage" class="image-upl" type="text" size="36" name="exerciseimage" value="<?php echo $item['image'];?>" style="width:60%;height:40px;padding:10px;" placeholder="Set exercise image" required/>
            <input id="exerciseimage_btn" type="button" value="Upload Image" />
            <div id="exerciseimage_preview" style="min-height: 100px; max-width: 250px;">
                <img style="max-width:100%;" src="<?php echo esc_url( $item['image'] ); ?>" />
            </div>
            
        </td>
    </tr>
    </tbody>
</table>
<?php
}

function validate_image(){return true;}

?>