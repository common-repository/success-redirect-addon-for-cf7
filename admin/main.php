<?php
/**
 * Provide a admin area view for the plugin
 *
 * Contact form 7 add new tab for success redirect selection.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    cf7-success-redirect-addon
 * @subpackage cf7-success-redirect-addon/admin
 */


/**
 * To display redirect panel in contact form 7
 *
 * @since    	1.0.0
 * @param      	string    $plugin_name       		Success redirect addon for cf7
 * @param      	string    $version    				1.0.0.
 */
add_action( 'wpcf7_editor_panels', 'cf7sr_tab' );
function cf7sr_tab($tabs) {
    $tab = __('Success Redirect Settings','cf7-sr-addon');
    $tabs['redirect-panel'] = array( 'title' => $tab, 'callback' => 'cf7sr_callback' );
    return $tabs;
}
function cf7sr_callback($post){
	wp_nonce_field( 'cf7sr_meta_nonce', 'cf7sr_meta_nonce' );
	$metaId = get_post_meta($post->id(),"cf7-sr-id",true);
	$dropdown_options = array (
            'echo' => 0,
            'name' => 'cf7-sr-id', 
            'show_option_none' => '--', 
            'option_none_value' => '0',
            'selected' => $metaId
        );

    echo __('<p>Please select the redirect page from the list.</p>').wp_dropdown_pages( $dropdown_options );
}

/**
 * To data data into database
 *
 * @since       1.0.0
 * @param       string    $plugin_name              Success redirect addon for cf7
 * @param       string    $version                  1.0.0.
 */
add_action( 'wpcf7_after_save', 'cf7sr_save_form' );
function cf7sr_save_form( $post ) {
    $postId = $post->id();
    if ( !isset( $_POST ) || empty( $_POST ) || !isset( $_POST['cf7-sr-id'] ) ) {
        return;
    }
    else {
        if ( ! wp_verify_nonce( $_POST['cf7sr_meta_nonce'], 'cf7sr_meta_nonce' ) ) {
            return;
        }
        $ID = sanitize_text_field( $_POST['cf7-sr-id'] );
        update_post_meta( $postId, 'cf7-sr-id', $ID );
    }
}

/**
 * Rection after form submit
 *
 * @since       1.0.0
 * @param       string    $plugin_name              Success redirect addon for cf7
 * @param       string    $version                  1.0.0.
 */
add_filter('wpcf7_form_hidden_fields','cf7sr_add_hidden_nonce');
function cf7sr_add_hidden_nonce($fields){
  $nonce = wp_create_nonce('cf7-redirect-id');
  $fields['redirect_nonce'] = $nonce;
  add_filter('do_shortcode_tag', function($output, $tag, $attrs) use ($nonce){
    $metaId = get_post_meta($attrs['id'],"cf7-sr-id",true);
    if($tag != "contact-form-7" || !$metaId) return $output;
    $script = '<script>'.PHP_EOL;
    $script .= 'document.addEventListener( "wpcf7mailsent", function( event ){'.PHP_EOL;
    $script .= '    location = "'.esc_url(get_permalink($metaId)).'";'.PHP_EOL;
    $script .= '});'.PHP_EOL;
    $script .= '</script>'.PHP_EOL;
    return $output.PHP_EOL.$script;
  },10,3);
  return $fields;
}

?>