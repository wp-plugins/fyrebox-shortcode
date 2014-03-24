<?php
/*
Plugin Name: FyreBox Shortcode
Plugin URI: http://wordpress.org/extend/plugins/fyrebox-shortcode/
Description: Converts Fyrebox WordPress shortcodes to a Fyrebox widget. Example: [fyrebox gid="i6WOeUCiUl" gt="8" /]
Version: 1.3
Author: Fyrebox Pty Ltd
Author URI: http://fyrebox.co
License: GPLv2

Original version: Cyril Gaillard <cyril@fyrebox.co>
*/

/* Register Fyrebox shortcode
   -------------------------------------------------------------------------- */

add_shortcode("fyrebox", "fyrebox_shortcode");


/**
 * Fyrebox shortcode handler
 * @param  {string|array}  $atts     The attributes  [fyrebox attr1="value" /].
 *                                   Is an empty string when no arguments are given.
 * @param  {string}        $content  The content between non-self closing [fyrebox]…[/fyrebox] tags.
 * @return {string}                  Widget embed code HTML
 */
function fyrebox_shortcode($atts, $content = null) {

  // Custom shortcode options
  $shortcode_options = array_merge(array('url' => trim($content)), is_array($atts) ? $atts : array());

  // Return html embed code
    return fyrebox_iframe_widget($shortcode_options); 
}

/**
 * Iframe widget embed code
 * @param  {array}   $options  Parameters
 * @return {string}            Iframe embed code
 */
function fyrebox_iframe_widget($options) {

  $url = 'https://www.fyrebox.com/wg/'.$options['gt'].'/'.$options['oid'].'/'.$options['gid'];
  switch($options['gt']){
    case '7':
      $height = 650;
    break;
    case '8':
      $height = 420;
    break;
    default:
      $height = 400;
    break;
  }
  return sprintf('<iframe width="700" height=%s scrolling="no" frameborder="no" allowTransparency="true" src="%s"></iframe>',$height ,$url);
}


/* Settings
   -------------------------------------------------------------------------- */

/* Add settings link on plugin page */
add_filter("plugin_action_links_" . plugin_basename(__FILE__), 'fyrebox_settings_link');

function fyrebox_settings_link($links) {
  $settings_link = '<a href="options-general.php?page=fyrebox-shortcode">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}

/* Add admin menu */
add_action('admin_menu', 'fyrebox_shortcode_options_menu');
function fyrebox_shortcode_options_menu() {
  add_options_page('FyreBox Options', 'FyreBox', 'manage_options', 'fyrebox-shortcode', 'fyrebox_shortcode_options');
  add_action('admin_init', 'register_fyrebox_settings');
}

function register_fyrebox_settings() {
  //Empty for now
}

function fyrebox_shortcode_options() {
  if (!current_user_can('manage_options')) {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }
?>
<div class="wrap">
  <h2>FyreBox Shortcode Settings</h2>
  <p>At the moment, there are no settings. Just use the shortcode provided on the "Publish" page of your game. If you haven't created a game yet, just go to <a href="http://www.fyrebox.com?utm_source=wordpress&utm_medium=settings&utm_campaign=plugin" target=_blank>Fyrebox.com</a> to get started.</p>

<?php
}
?>
