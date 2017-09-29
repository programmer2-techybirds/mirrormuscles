<?php 

/*

Template Name: Test
Template Post Type: post, page, product 
*/
get_header();



function my_virtual_templates( $templates ) {

    $my_virtual_templates = array(
        'virtual_template_id_1' => 'testt',
       
    );

    // Merge with any templates already available
    $templates = array_merge( $templates, $my_virtual_templates );

    return $templates;
}

add_filter( 'theme_page_templates', 'my_virtual_templates' );

echo "hello";

get_footer()

?>