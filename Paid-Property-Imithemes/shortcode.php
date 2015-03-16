<?php 
function propertyShortcode($args)
{
extract( shortcode_atts( array(
'email' => get_option('paypal_email_address'),
'property_price'=>'',
'property_plan' => '',
'plan_name' => '',
'description' => '',	
'currency' => get_option('paypal_currency_options'),
'reference' => '',	
'return' => '',
'cancel_url' => '',
'tax' => '',
'paypal_payment' => get_option('paypal_payment_option'),
), $args));	
$output = "";
if(empty($email)){
        $output = '<div id="message"><div class="alert alert-error">Error! Please enter your PayPal email address in property options page.</div></div>';
        return $output;
}
$paypal_payment = ($paypal_payment=="live")?"https://www.paypal.com/cgi-bin/webscr":"https://www.sandbox.paypal.com/cgi-bin/webscr";
$window_target = '';
if(!empty($new_window)){
$window_target = 'target="_blank"';
}
if(empty($property_price)){
$paypal_payment='';  
$pay_now =__('Free Subscription','framework');
}
else{
  $pay_now = get_option('payment_form_info');
  $pay_now=!empty($pay_now)?$pay_now:__('Pay Now','framework');
}
$output .= '<div class="wp_paypal_button_widget_any_amt">';
$output .= '<form id="property-payment" class="paypal-submit-form sai" name="_xclick" action="'.$paypal_payment.'" method="post" '.$window_target.'>';
if(!empty($reference)){
$output .= '<div class="wp_pp_button_reference_section">';
$output .= '<label for="wp_pp_button_reference">'.$reference.'</label>';
$output .= '<br />';
$output .= '<input type="hidden" name="on0" value="Reference" />';
$output .= '<input type="text" name="os0" value="" class="wp_pp_button_reference" />';
$output .= '</div>';
}
$this_email = '';
$this_first_name = '';
$this_last_name = '';
$this_username = '';
$this_actualast_name = '';
if(is_user_logged_in()) {
global $current_user;
get_currentuserinfo();
$this_email = $current_user->user_email;
$this_first_name = $current_user->user_firstname;
$this_last_name = $current_user->user_lastname;
$this_username = $current_user->display_name;
$this_actualast_name = ($this_first_name=='')?$this_username:$this_first_name; }
$unique = uniqid();
$output .= '
<div class="row">
<div class="col-md-6">
<input type="text" value="'.$this_actualast_name.'" id="username" name="first_name" class="form-control" placeholder="First name (Required)">
<input type="hidden" id="postname" name="postname" value="property">
</div>
<div class="col-md-6">
        <input id="lastname" value="'.$this_last_name.'" type="text" name="last_name" class="form-control" placeholder="Last name">
</div>
</div>
<div class="row">
<div class="col-md-6">
<input type="text" value="'.$this_email.'" name="email" id="email" class="form-control" placeholder="Your email (Required)">
</div>
<div class="col-md-6">
<input id="phone" type="phone" name="H_PhoneNumber" class="form-control" placeholder="Your phone">
</div>
</div>
<div class="row">
<div class="col-md-6">
<textarea id="address1" name="address1" rows="3" cols="5" class="form-control" placeholder="Your Address"></textarea>
</div>
<div class="col-md-6">
        <textarea id="notes" rows="3" cols="5" name ="noteToSeller" class="form-control" placeholder="Additional Notes"></textarea>
</div>
</div>';
$output .= '<input type="hidden" name="rm" value="2">';
$output .= '<input type="hidden" name="amount" value="'.$property_price.'">';	
$output .= '<input type="hidden" name="cmd" value="_xclick">';
$output .= '<input type="hidden" name="business" value="'.$email.'">';
$output .= '<input type="hidden" name="currency_code" value="'.$currency.'">';
$output .= '<input type="hidden" name="item_name" value="'.stripslashes($description).'">';
$output .= '<input type="hidden" name="item_number" value="'.$plan_name.'">';
$output .= '<input type="hidden" name="return" value="'.get_permalink($property_plan).'" />';
if(is_numeric($tax)){
    $output .= '<input type="hidden" name="tax" value="'.$tax.'" />';
}
if(!empty($cancel_url)){
        $output .= '<input type="hidden" name="cancel_return" value="'.$cancel_url.'" />';
}
if(!empty($country_code)){
        $output .= '<input type="hidden" name="lc" value="'.$country_code.'" />';
}
$free_plan_old = get_user_meta($current_user->ID,'free_plan_name_value',true);
if(empty($property_price)&&!empty($free_plan_old)){
global $imic_options;
  if(isset($imic_options['free_plan_scheme'])&&!empty($imic_options['free_plan_scheme'])){
      if($imic_options['free_plan_scheme']>reset($free_plan_old)){
$output .= '<input id="donate-property" type="submit" name="donate" class="btn btn-primary btn-lg btn-block" value="'.$pay_now.'">';
      }else{
    $pay_for_plan_url= imic_get_template_url('template-price-listing.php');
  if(!empty($pay_for_plan_url)){
     $pay_for_plan_url= '<a href="'.$pay_for_plan_url.'">'.__('Choose plan','framework').'</a>';
  }
    $output .= "<div id=\"message\"><div class=\"alert alert-success\">".__('Your free maximum limit reached to add new property  ','framework').$pay_for_plan_url."</div></div>"; 
} 
}}else{
    $output .= '<input id="donate-property" type="submit" name="donate" class="btn btn-primary btn-lg btn-block" value="'.$pay_now.'">';  
}

if(!empty($property_price)){
$output .= '<div id="message"></div>';
}
$output .= '</form>';
$output .= '</div>';
return $output;
} 
?>