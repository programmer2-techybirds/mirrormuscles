<?php
/**
 * Locate template
 * @param $template
 */
function bbm_message_load_template($template){
    $template .= '.php';

    if(file_exists(STYLESHEETPATH.'/buddyboss-inbox/'.$template)) {
        include(STYLESHEETPATH.'/buddyboss-inbox/'.$template);
    }
    else if(file_exists(TEMPLATEPATH.'/buddyboss-inbox/'.$template)){
        include (TEMPLATEPATH.'/buddyboss-inbox/'.$template);
    }
    else{
        $template_dir = apply_filters('bbm_message_load_template', buddyboss_messages()->templates_dir);
        include trailingslashit($template_dir) . $template;
    }
}

/**
 * register the location of the plugin templates
 * @return string
 */
function bbm_register_template_location() {
    
    if(file_exists(STYLESHEETPATH.'/buddyboss-inbox/')) {
        $templates_dir = STYLESHEETPATH.'/buddyboss-inbox/';
    }
    else if(file_exists(TEMPLATEPATH.'/buddyboss-inbox/')){
        $templates_dir = TEMPLATEPATH.'/buddyboss-inbox/';
    }
    else{
        $template_dir = apply_filters('bbm_message_load_template', buddyboss_messages()->templates_dir);
        $templates_dir = trailingslashit($template_dir) ;
    }
    
    $template_dir = apply_filters('bbm_templates_dir_filter', $templates_dir);
    return trailingslashit($template_dir);
}

/**
 * Replace message template
 * @param $templates
 * @param $slug
 * @param $name
 * @return array
 */
function buddyboss_messages_replace_template( $templates, $slug, $name ) {
    $current_action = bp_current_action();
    if($slug == 'members/single/home' && $current_action == 'compose'){
        return array( 'members/single/messages/compose.php' );
    }
    if($slug == 'members/single/home' && $current_action == 'view'){
        return array( 'members/single/messages/single.php' );
    }
    return $templates;
}

/**
 * Set template location
 */
function buddyboss_messages_template_stack() {
    if( function_exists( 'bp_register_template_stack' ) )
        bp_register_template_stack( 'bbm_register_template_location', 1 );
}