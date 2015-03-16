<?php
/*
Plugin Name: Paid Property Imithemes
Version: v1.0.0
Author: IMITHEMES
Author URI: http://www.imithemes.com
Description: This plugin adds paid property functionality in Real Space theme.
License: This plugin is bundled with Real Space Theme and should be use with Real Space Theme only.
*/
if ( ! defined( 'IMI_PROPERTY_BASE_FILE' ) )
    define( 'IMI_PROPERTY_BASE_FILE', __FILE__ );
if ( ! defined( 'IMI_PROPERTY_BASE_DIR' ) )
    define( 'IMI_PROPERTY_BASE_DIR', dirname( IMI_PROPERTY_BASE_FILE ) );
if ( ! defined( 'IMI_PROPERTY_PLUGIN' ) )
    define( 'IMI_PROPERTY_PLUGIN', plugin_dir_url( __FILE__ ) );
include_once('shortcode.php');
include_once('property_functions.php');
include_once('property-payment.php');
add_shortcode('imic_property','propertyDonateNow');
function propertyDonateNow($args)
{ 
$output = propertyShortcode($args);
return $output;
}
add_action('admin_menu', 'propertyOptionPage');
function propertyOptionPage() {
global $propertyOption;
$propertyOption =	add_submenu_page( 'themes.php',__('Payment Options','framework'), __('Payment Options','framework'),'manage_options', 'property_options', 'property_options',7 );
//add_action('load-'.$propertyOption, 'propertyOptionHelpTab');
add_action( 'admin_init', 'imicRegisterSettings' );
}
if(!function_exists('propertyOptionHelpTab')){
	function propertyOptionHelpTab(){
$screen = get_current_screen();
$screen->add_help_tab( array(
'id'	=> 'auto_return',
'title'	=> __('Enable Auto Return'),
'content'	=> '<p>' . __( 'Here are the steps to enable Auto Return in your account.','framework').'</p><p>'.
__('Log into https://developer.paypal.com','framework').'</p><p>'
.__('Click Applications','framework').'</p><p>'.
__('Click accounts','framework').'</p><p>'.
__('Expand the account in question','framework').'</p><p>'.
__('Click Sandbox site','framework').'</p><p>'.
__('Login to the test account','framework').'</p><p>'.
__('Copy and paste "https://www.paypal.com/us/cgi-bin/webscr?cmd=_profile-website-payments" into your browser
Enable Auto Return and click Save','framework').'</p><p>'.
__('Enter the Auto Return URL and click Save','framework').'</p>',
));
$screen->add_help_tab( array(
'id'	=> 'token_id',
'title'	=> __('Token ID'),
'content'	=> '<p>' . __( 'Here are the steps to enable Auto Return in your account.','framework').'</p><p>',
));
$screen->add_help_tab( array(
'id'	=> 'template_id',
'title'	=> __('Templates'),
'content'	=> '<p>' . __( 'To get template ID, Follow Steps.','framework').'</p><p>'.
__('Create a page with selecting content with sidebar','framework').'</p><p>'.
__('When you publish that page you will get id in dashboard url','framework').'</p><p>'.
__('Copy that ID and paste it here','framework').'</p><p>'.
__('You can follow this step for both templates(Property List and Property Grid)','framework').'</p><p>',
));
}
}
function imicRegisterSettings() {
//register our settings
register_setting( 'property-options-group', 'paypal_email_address' );
register_setting( 'property-options-group', 'paypal_token_id' );
register_setting( 'property-options-group', 'paypal_currency_options' );
register_setting( 'property-options-group', 'paypal_payment_option' );
register_setting( 'property-options-group', 'property_list_id' );
register_setting( 'property-options-group', 'property_grid_id' );
register_setting( 'property-options-group', 'payment_form_info' );
register_setting( 'property-options-group', 'registration_form_info' );
}
function property_options() { ?>
<div class="wrap">
<h2><?php _e('Property Options','property-framework'); ?></h2>
<form method="post" action="options.php">
<?php settings_fields( 'property-options-group' ); ?>
<?php do_settings_sections( 'property-options-group' ); ?>
<table class="form-table">
<tr valign="top">
<th scope="row"><?php _e('Paypal Email Address:','property-framework'); ?></th>
<td><input type="text" name="paypal_email_address" value="<?php echo get_option('paypal_email_address'); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Paypal Token ID','property-framework'); ?></th>
<td><input type="text" name="paypal_token_id" value="<?php echo get_option('paypal_token_id'); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Paypal Currency Options','property-framework'); ?></th>
<td>
<select id="paypal_currency_options" name="paypal_currency_options">
<?php 
_e('<option value="USD"'); echo (get_option('paypal_currency_options')=="USD")?'selected':'';  _e('>US Dollar</option>');
_e('<option value="GBP"'); echo (get_option('paypal_currency_options')=="GBP")?'selected':'';  _e('>Pound Sterling</option>'); 
_e('<option value="EUR"'); echo (get_option('paypal_currency_options')=="EUR")?'selected':'';  _e('>Euro</option>');
_e('<option value="AUD"'); echo (get_option('paypal_currency_options')=="AUD")?'selected':'';  _e('>Australian Dollar</option>');
_e('<option value="CAD"'); echo (get_option('paypal_currency_options')=="CAD")?'selected':'';  _e('>Canadian Dollar</option>');
_e('<option value="NZD"'); echo (get_option('paypal_currency_options')=="NZD")?'selected':'';  _e('>New Zealand Dollar</option>');
_e('<option value="HKD"'); echo (get_option('paypal_currency_options')=="HKD")?'selected':'';  _e('>Hong Kong Dollar</option>');
?>
</select>
</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Paypal Payment Site','property-framework'); ?></th>
<td>
<select id="paypal_payment_option" name="paypal_payment_option">
<?php 
_e('<option value="live"'); echo (get_option('paypal_payment_option')=="live")?'selected':'';  _e('>Live</option>');
_e('<option value="sandbox"'); echo (get_option('paypal_payment_option')=="sandbox")?'selected':'';  _e('>Sandbox</option>'); ?>
</select>
</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Paypal button text:','property-framework'); ?></th>
<td><input type="text" name="payment_form_info" value="<?php echo get_option('payment_form_info'); ?>" /></td>
</tr>
</table>
<?php submit_button(); ?>
</form>
</div>
<?php } ?>