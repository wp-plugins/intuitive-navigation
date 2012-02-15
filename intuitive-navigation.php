<?php
/*
Plugin Name: Intuitive Navigation
Plugin URI: http://steamingkettle.net/web-design/wordpress-plugins/
Description: Creates navigation to next and previous posts specific to the category or tag a visitor came from. You can embed the navigation automatically or use a custom function <code>addIntNav()</code> in your template files. Customize the appearance with post thumbnails and labels. Swap next and previous post links mirror-wise. Bold links to the current category or tag. Optimized for use with caching plugins.
Version: 0.6
Author: Denis Buka
Author URI: http://steamingkettle.net

Copyright (C) 2011 SteamingKettle.net

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public Licensealong with this program. If not, see <http://www.gnu.org/licenses/>.
*/

register_activation_hook(__FILE__, 'int_nav_add_defaults');
register_uninstall_hook(__FILE__, 'int_nav_delete_plugin_options');
add_action('admin_init', 'int_nav_init' );
add_action('admin_menu', 'int_nav_add_options_page');

function int_nav_delete_plugin_options() {
  delete_option('int_nav_options');
}

function int_nav_add_defaults() {
  $tmp = get_option('int_nav_options');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
    delete_option('int_nav_options'); 
    $arr = array(  "int_nav_display_thumbs" => "1",
            "int_nav_crop_thumbs" => "0",
            "int_nav_bold" => "1",
            "int_nav_swap" => "0",
            "int_nav_frame" => "0",
            "int_nav_style" => "0",
            "int_nav_next_text" => "Next&nbsp;&raquo;",
            "int_nav_prev_text" => "&laquo;&nbsp;Previous",
            "int_nav_auto" => "none",
            "int_nav_height" => "0",
            "int_nav_style_url" => "",
            "int_nav_style_embed" => ""
    );
    update_option('int_nav_options', $arr);
  }
}

function int_nav_init(){
  register_setting( 'int_nav_plugin_options', 'int_nav_options', 'int_nav_validate_options' );
}

function int_nav_add_options_page() {
  add_options_page('Intuitive Navigation Options', 'Intuitive Navigation', 'manage_options', __FILE__, 'int_nav_render_form');
}

function int_nav_render_form() {
  ?>
  <div class="wrap">
    
    <div class="icon32" id="icon-options-general"><br></div>
    <h2>Intuitive Navigation</h2>
    <p>Intuitive Navigation settings can be tweaked here to your liking.</p>
    <p>You can use the following code to embed Intuitive Navigation into your Wordpress theme:</p>
    <p><code>&lt;?php if ( function_exists( 'addIntNav' ) ) { addIntNav(); } ?&gt;</code></p>

    <form method="post" action="options.php">
      <?php settings_fields('int_nav_plugin_options'); ?>
      <?php $options = get_option('int_nav_options'); ?>

      <table class="form-table">

        <tr>
          <th scope="row" style="width:270px;"><strong>Display post thumbnails:</strong></th>
          <td>
            <label><input name="int_nav_options[int_nav_display_thumbs]" type="checkbox" value="1" <?php if (isset($options['int_nav_display_thumbs'])) { checked('1', $options['int_nav_display_thumbs']); } ?> /></label>
          </td>
        </tr>
        
        <tr>
          <th scope="row" style="width:270px;"><strong>Crop thumbnails:</strong></th>
          <td>
            <label><input name="int_nav_options[int_nav_crop_thumbs]" type="checkbox" value="1" <?php if (isset($options['int_nav_crop_thumbs'])) { checked('1', $options['int_nav_crop_thumbs']); } ?> /> <em>&nbsp;&nbsp;&nbsp;NB: Your theme may override this setting.</label>
          </td>
        </tr>
        
        <tr>
          <th scope="row" style="width:270px;"><strong>Bold links to the currently viewed category/tag:</strong></th>
          <td>
            <label><input name="int_nav_options[int_nav_bold]" type="checkbox" value="1" <?php if (isset($options['int_nav_bold'])) { checked('1', $options['int_nav_bold']); } ?> /> <em>&nbsp;&nbsp;&nbsp;Bold-highlighted links can be further styled by referencing <code style="font-style:normal;">strong.int_nav_strong</code> in your CSS file</label>
          </td>
        </tr>

        <tr>
          <th scope="row" style="width:270px;"><strong>Next post label text:</strong></th>
          <td>
            <input type="text" size="30" name="int_nav_options[int_nav_next_text]" value="<?php echo $options['int_nav_next_text']; ?>" />
          </td>
        </tr>

        <tr>
          <th scope="row" style="width:270px;"><strong>Previous post label text:</strong></th>
          <td>
            <input type="text" size="30" name="int_nav_options[int_nav_prev_text]" value="<?php echo $options['int_nav_prev_text']; ?>" />
          </td>
        </tr>
        
        <tr>
          <th scope="row" style="width:270px;"><strong>Autoinsert:</strong><br /><em>(automatically insert navigation above or below post content)</em></th>
          <td>
            <select name='int_nav_options[int_nav_auto]'>
              <option value='none' <?php selected('none', $options['int_nav_auto']); ?>>None</option>
              <option value='above' <?php selected('above', $options['int_nav_auto']); ?>>Above</option>
              <option value='below' <?php selected('below', $options['int_nav_auto']); ?>>Below</option>
            </select>
            <span style="color:#666666;margin-left:2px;"></span>
          </td>
        </tr>

        <tr>
          <th scope="row" style="width:270px;"><strong>Navigation height:</strong><br /><em>(use "0" for default height)</em></th>
          <td>
            <label><input style="text-align:right;" type="text" size="4" name="int_nav_options[int_nav_height]" value="<?php echo $options['int_nav_height']; ?>" /> px&nbsp;&nbsp;&nbsp;<em>(recommended to set to a certain value)</label>
          </td>
        </tr>
        
        <tr>
          <th scope="row" style="width:270px;"><strong>Swap next/previous items mirrorwise:</strong></th>
          <td>
            <label><input name="int_nav_options[int_nav_swap]" type="checkbox" value="1" <?php if (isset($options['int_nav_swap'])) { checked('1', $options['int_nav_swap']); } ?> /></label>
          </td>
        </tr>
        
        <tr>
          <th scope="row" style="width:270px;"><strong>Load navigation in a frame:</strong><br /><em>(for use with caching plugins)</em></th>
          <td>
            <label><input name="int_nav_options[int_nav_frame]" type="checkbox" value="1" <?php if (isset($options['int_nav_frame'])) { checked('1', $options['int_nav_frame']); } ?> /></label>
          </td>
        </tr>
        
        <tr>
          <th scope="row" style="width:270px;"><strong>Load main stylesheet in the frame:</strong><br /><em>(if previous option is selected)</em></th>
          <td>
            <label><input name="int_nav_options[int_nav_style]" type="checkbox" value="1" <?php if (isset($options['int_nav_style'])) { checked('1', $options['int_nav_style']); } ?> /> <em>&nbsp;&nbsp;&nbsp;NB: Keep in mind that frame content won't inherit any styles from the parent page.</label>
          </td>
        </tr>
      <?php if ( trim($options['int_nav_style_url']) == "" ) { ?>
        <tr>
          <th scope="row" style="width:270px;"><strong>Current stylesheet URL is:</strong></th>
          <td>
            <code><?php echo get_bloginfo('stylesheet_url'); ?></code>
          </td>
        </tr>
      <?php } ?>
        <tr>
          <th scope="row" style="width:270px;"><strong>Alternative frame stylesheet URL:</strong><br /><em></em></th>
          <td>
            <input type="text" size="60" name="int_nav_options[int_nav_style_url]" value="<?php echo $options['int_nav_style_url']; ?>" />
          </td>
        </tr>
        <tr>
          <th scope="row"><strong>Embed styles in frame:</strong></th>
          <td>
            <textarea name="int_nav_options[int_nav_style_embed]" rows="7" cols="70" type='textarea'><?php echo $options['int_nav_style_embed']; ?></textarea><br /><em>(these styles will be added to the frame's head section)</em>
          </td>
        </tr>

      </table>
      <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
      </p>
    </form>

	<br />
	<hr />
	<h3>My other plugins:</h3>
	<ul>
		<li><a href="http://wordpress.org/extend/plugins/generate-cache/">Generate Cache</a></li>   
		<li><a href="http://wordpress.org/extend/plugins/drop-in-dropbox/">Drop in Dropbox</a></li>   
	</ul>
</div>
  <?php  
}

function int_nav_validate_options($input) {
  $input['int_nav_next_text'] =  wp_filter_nohtml_kses($input['int_nav_next_text']); 
  $input['int_nav_prev_text'] =  wp_filter_nohtml_kses($input['int_nav_prev_text']); 
  return $input;
}

add_filter( 'plugin_action_links', 'int_nav_plugin_action_links', 10, 2 );
function int_nav_plugin_action_links( $links, $file ) {

  if ( $file == plugin_basename( __FILE__ ) ) {
    $int_nav_links = '<a href="'.get_admin_url().'options-general.php?page=intuitive-navigation/intuitive-navigation.php">'.__('Settings').'</a>';
    array_unshift( $links, $int_nav_links );
  }

  return $links;
}

add_filter( "the_content", "int_nav_add_content" );
function int_nav_add_content($text) {
  $options = get_option('int_nav_options');
  if ( $options['int_nav_auto'] == "above" ) {
    $p = initIntNav();
    ob_start();
    buildIntNav($p);
    $output = ob_get_contents();
    ob_end_clean();
    $text = "{$output}{$text}";
    return $text;
  } elseif ( $options['int_nav_auto'] == "below" ) {
    $p = initIntNav();
    ob_start();
    buildIntNav($p);
    $output = ob_get_contents();
    ob_end_clean();
    $text = "{$text}{$output}";
    return $text;
  } else {
    return $text;
  }
}


include dirname(__FILE__) . '/functions.php';

?>
