<?php

/*
 * Creates options page for global settings
 */
if( function_exists('acf_add_options_page') ) {
  acf_add_options_page(array(
    'menu_title' => 'Site Globals',
    'position' => '2',
    'post_id' => 'options'
  ));
}

/*
 * adds custom css for acf fields in admin
 */
function custom_acf_admin_styles() {
  //wp_register_style( 'custom-acf-admin-css', get_stylesheet_directory_uri() . '/css/admin/custom-acf-admin-styles.css', false, '1.0.0' );
  //wp_enqueue_style( 'custom-acf-admin-css' );
}
add_action( 'acf/input/admin_enqueue_scripts', 'custom_acf_admin_styles' );


/*
 * adds custom scripts for acf fields in admin
 */
function custom_acf_admin_scripts() {
  wp_enqueue_script( 'acf-admin-js', get_template_directory_uri() . '/js/admin/acf.js', array(), '1.0.0', true );
}
add_action('acf/input/admin_enqueue_scripts', 'custom_acf_admin_scripts');


//filters labels
function fg_filter_labels( $content, $field, $value, $lead_id, $form_id ) {

  $content = str_replace('(', '<span>(', $content);
  $content = str_replace(')', ')</span>', $content);
  return $content;
}

add_filter( 'gform_field_content', 'fg_filter_labels', 10, 5 );
