<?php
/**
 * Awebsome! Comment Author Mail Validation
 * Admin file, backend related
 * 
 * @package awebsome_camv
 * @author Felipe Mateus <eu@felipemateus.com>
 */
/**
 * Sets up the plugin and its related basic settings
 * 
 * @uses add_settings_field To add a new field to the Settings > Discussion menu
 * @uses register_setting To register our plugin option in the Discussion subsection
 * @uses wp_register_style To register the plugin CSS file
 * @uses load_plugin_textdomain To load plugin textdomain and translations
 * @uses WP_PLUGIN_URL
 * @uses basename
 * @uses dirname
 * 
 * @since awebsome_camv 1.0
 */
function aws_camv_adm_init()
{
	$plugin_dir = basename( dirname(__FILE__) );
	
	add_settings_field('aws_camv_onoff', 'Awebsome! Comment Author Mail Validation', 'aws_camv_adm_main_callback', 'discussion', 'default');
	register_setting('discussion', 'aws_camv_onoff');
	wp_register_style('aws_camv_css', WP_PLUGIN_URL .'/'. $plugin_dir .'/aws-camv.css');
	
	load_plugin_textdomain('awebsome-camv', null, $plugin_dir .'/languages/');
}

/**
 * Callback to add the CAMV option to the Discussion Settings field
 * 
 * @uses get_option To retrieve data from the WP options correctly 
 * 
 * @since awebsome_camv 1.0
 */
function aws_camv_adm_main_callback()
{
	get_option('aws_camv_onoff') ? $checked = 'checked="checked" ' : $checked = '';
?>
	<div class="aws_camv">
		<label for="aws_camv_onoff">
		<input <?php print $checked ?>name="aws_camv_onoff" id="aws_camv_onoff" type="checkbox" value="aws_camv_onoff" class="code" /> <?php _e('Require Comment Author Mail Validation', 'awebsome-camv') ?></label>
	</div>
<?php
}

/**
 * Includes custom CSS into the admin panel 
 * 
 * @uses wp_enqueue_style To enqueue a style in the WP admin header correctly
 * 
 * @since awebsome_camv 1.0
 */
function aws_camv_adm_styles()
{
	wp_enqueue_style('aws_camv_css');
}

/**
 * Includes custom jQuery based JavaScript functionality into the admin panel
 * Basically moves the option to the "Before a comment appears" fields section 
 * 
 * @uses wp_enqueue_script To enqueue a script in the WP admin header correctly
 * @uses WP_PLUGIN_URL
 * @uses basename
 * @uses dirname
 * 
 * @since awebsome_camv 1.0
 */
function aws_camv_adm_scripts()
{
	$plugin_dir = basename( dirname(__FILE__) );
	wp_enqueue_script('camv', WP_PLUGIN_URL .'/'. $plugin_dir .'/aws-camv.js');
}

// Add some basic WP hooks
add_action('admin_init', 'aws_camv_adm_init');
add_action('admin_menu', 'aws_camv_adm_scripts');
add_action('admin_print_styles', 'aws_camv_adm_styles');
?>