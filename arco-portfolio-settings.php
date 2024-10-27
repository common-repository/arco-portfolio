<div class="wrap">
  <?php
// Update settings function
  if ( isset($_POST['arcop-settings-submit']) && $_POST['arcop-settings-submit'] == 'Y' && check_admin_referer( 'arcop-settings-page' )) { 
   arcop_save_theme_settings();
   $url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
 }
// Go to tab
 if ( isset ( $_GET['tab'] ) ) ilc_admin_tabs(sanitize_key($_GET['tab'])); else ilc_admin_tabs('homepage');
// Tabs  
 function ilc_admin_tabs( $current = 'homepage' ) {
  $tabs = array( 'homepage' => __('Layout Settings' ,'arcoportfolio'), 'jquery' => __('JQuery settings','arcoportfolio') );
  echo '<h2 class="nav-tab-wrapper">';
  foreach( $tabs as $tab => $name ){
    $class = ( $tab == $current ) ? ' nav-tab-active' : '';
    echo "<a class='nav-tab$class' href='?page=arcop_settings&tab=$tab'>$name</a>";
  }
  echo '</h2>';
}
?>
<div id="poststuff" class="bsr-poststuff">
  <div id="post-body" class="metabox-holder columns-2">
    <div id="post-body-content">
      <div class="postbox">
        <div class="inside">
          <form method="post" action="<?php admin_url( 'admin.php?page=arcop_settings&noheader=true' ); ?>">
            <?php
            wp_nonce_field( "arcop-settings-page" ); 
            global $pagenow;
            $settings = get_option( "arcop_settings" );

            if ( $pagenow == 'admin.php' && $_GET['page'] == 'arcop_settings' ){
             if ( isset ( $_GET['tab'] ) ) $tab = sanitize_key($_GET['tab']);
             else $tab = 'homepage';
             echo '<table class="form-table">';
             switch ( $tab ){

              case 'homepage' :
              ?>
              <tr>
                <th><label for="arcon_color"><?php _e('Columns Count','arcoportfolio');?></label></th>
                <td>
                  <div class="arcon-radio"><div class="arcon_label"><?php _e('Desktops:','arcoportfolio');?></div>
                  <?php if(!isset($settings['arcon_columns'])) $settings['arcon_columns'] = 4; ?>
                  <input value="1" type="radio" id="arcon_columns_1" name="arcon_columns" <?php if ( $settings["arcon_columns"] == '1' ) echo 'checked="checked"'; ?>><label for="arcon_columns_1">1</label>
                  <input value="2" type="radio" id="arcon_columns_2" name="arcon_columns" <?php if ( $settings["arcon_columns"] == '2' ) echo 'checked="checked"'; ?>><label for="arcon_columns_2">2</label>
                  <input value="3" type="radio" id="arcon_columns_3" name="arcon_columns" <?php if ( $settings["arcon_columns"] == '3' ) echo 'checked="checked"'; ?>><label for="arcon_columns_3">3</label>
                  <input value="4" type="radio" id="arcon_columns_4" name="arcon_columns" <?php if ( $settings["arcon_columns"] == '4' ) echo 'checked="checked"'; ?>><label for="arcon_columns_4">4</label>
                  <input value="6" type="radio" id="arcon_columns_6" name="arcon_columns" <?php if ( $settings["arcon_columns"] == '6' ) echo 'checked="checked"'; ?>><label for="arcon_columns_6">6</label>
                </div>
                <div class="arcon-radio"><div class="arcon_label"><?php _e('Tablets:','arcoportfolio');?></div>
                <?php if(!isset($settings['arcon_columns_tablets'])) $settings['arcon_columns_tablets'] = 3; ?>
                <input value="1" type="radio" id="arcon_columns_tablets_1" name="arcon_columns_tablets" <?php if ( $settings["arcon_columns_tablets"] == '1' ) echo 'checked="checked"'; ?>><label for="arcon_columns_tablets_1">1</label>
                <input value="2" type="radio" id="arcon_columns_tablets_2" name="arcon_columns_tablets" <?php if ( $settings["arcon_columns_tablets"] == '2' ) echo 'checked="checked"'; ?>><label for="arcon_columns_tablets_2">2</label>
                <input value="3" type="radio" id="arcon_columns_tablets_3" name="arcon_columns_tablets" <?php if ( $settings["arcon_columns_tablets"] == '3' ) echo 'checked="checked"'; ?>><label for="arcon_columns_tablets_3">3</label>
                <input value="4" type="radio" id="arcon_columns_tablets_4" name="arcon_columns_tablets" <?php if ( $settings["arcon_columns_tablets"] == '4' ) echo 'checked="checked"'; ?>><label for="arcon_columns_tablets_4">4</label>
                <input value="6" type="radio" id="arcon_columns_tablets_6" name="arcon_columns_tablets" <?php if ( $settings["arcon_columns_tablets"] == '6' ) echo 'checked="checked"'; ?>><label for="arcon_columns_tablets_6">6</label>
              </div>
              <div class="arcon-radio"><div class="arcon_label"><?php _e('Phones:','arcoportfolio');?></div>
              <?php if(!isset($settings['arcon_columns_phones'])) $settings['arcon_columns_phones'] = 2; ?>
              <input value="1" type="radio" id="arcon_columns_phones_1" name="arcon_columns_phones" <?php if ( $settings["arcon_columns_phones"] == '1' ) echo 'checked="checked"'; ?>><label for="arcon_columns_phones_1">1</label>
              <input value="2" type="radio" id="arcon_columns_phones_2" name="arcon_columns_phones" <?php if ( $settings["arcon_columns_phones"] == '2' ) echo 'checked="checked"'; ?>><label for="arcon_columns_phones_2">2</label>
              <input value="3" type="radio" id="arcon_columns_phones_3" name="arcon_columns_phones" <?php if ( $settings["arcon_columns_phones"] == '3' ) echo 'checked="checked"'; ?>><label for="arcon_columns_phones_3">3</label>
              <input value="4" type="radio" id="arcon_columns_phones_4" name="arcon_columns_phones" <?php if ( $settings["arcon_columns_phones"] == '4' ) echo 'checked="checked"'; ?>><label for="arcon_columns_phones_4">4</label>
              <input value="6" type="radio" id="arcon_columns_phones_6" name="arcon_columns_phones" <?php if ( $settings["arcon_columns_phones"] == '6' ) echo 'checked="checked"'; ?>><label for="arcon_columns_phones_6">6</label>
            </div>
          </td>
        </tr>

        <tr>
          <th><label for="arcon_color_scheme"><?php _e('Skin color scheme','arcoportfolio');?></label></th>
          <td>
           <select id="arcon_color_scheme" name="arcon_color_scheme">
             <?php if(!isset($settings["arcon_color_scheme"])) $settings["arcon_color_scheme"] = 'light';?>
             <option value="light"<?php if ( $settings["arcon_color_scheme"] == 'light' ) echo ' selected="selected"';?>>light</option>
             <option value="dark"<?php if ( $settings["arcon_color_scheme"] == 'dark' ) echo ' selected="selected"';?>>dark</option>
           </select> 
         </td>
       </tr>

       <tr>
        <th><label for="arcon_shadow"><?php _e('Add Box Shadow','arcoportfolio');?></label></th>
        <td>
         <div class="arcon-radio">
          <?php if(!isset($settings['arcon_shadow'])) $settings['arcon_shadow'] = 'no'; ?>
          <input value="yes" type="radio" id="arcon_shadow_yes" name="arcon_shadow" <?php if ( $settings["arcon_shadow"] == 'yes' ) echo 'checked="checked"'; ?>><label for="arcon_shadow_yes"><?php _e('YES','arcoportfolio');?></label>
          <input value="no" type="radio" id="arcon_shadow_no" name="arcon_shadow" <?php if ( $settings["arcon_shadow"] == 'no' ) echo 'checked="checked"'; ?>><label for="arcon_shadow_no"><?php _e('NO','arcoportfolio');?></label>
        </div>
      </td>
    </tr>

    <tr>
      <th><label for="arcon_postlink"><?php _e('Portfolio Post link text','arcoportfolio');?></label></th>
      <td>
        <?php if(!isset($settings['arcon_postlink'])) $settings['arcon_postlink'] = __('Read about this project','arcoportfolio'); ?>
        <input type="text" id="arcon_postlink" name="arcon_postlink" value="<?=$settings['arcon_postlink']?>">
      </td>
    </tr>

    <tr>
      <th><label for="arcon_url_link"><?php _e('Portfolio URL link text','arcoportfolio');?></label></th>
      <td>
        <?php if(!isset($settings['arcon_url_link'])) $settings['arcon_url_link'] = __('Visit project site','arcoportfolio'); ?>
        <input type="text" id="arcon_url_link" name="arcon_url_link" value="<?=$settings['arcon_url_link']?>">
      </td>
    </tr>

    <tr>
      <th><label for="arcon_nw"><?php _e('Open links in new window','arcoportfolio');?></label></th>
      <td>
       <input id="arcon_nw" name="arcon_nw" type="checkbox" <?php if ( isset($settings['arcon_nw']) && $settings['arcon_nw'] == 'true') echo 'checked="checked"'; ?> value="true" />
       <label for="arcon_nw"><?php _e('Check this if you want to open outgoing portfolio links in new window','arcoportfolio');?></label>
     </td>
   </tr>
   <?php
   break;

   case 'jquery' :
   ?>
   <tr>
    <th><label for="arcon_jqs"><?php _e('Animation script','arcoportfolio');?></label></th>
    <td>
     <select id="arcon_jqs" name="arcon_jqs">
     <option value="mixitup" <?php if ( isset($settings["arcon_jqs"]) && $settings["arcon_jqs"] == 'mixitup' ) echo 'selected="selected"'; ?>>MixItUp.js</option>
      <option value="isotope" <?php if ( isset($settings["arcon_jqs"]) && $settings["arcon_jqs"] == 'isotope' ) echo 'selected="selected"'; ?>>Isotope.js</option>
    </select>
    <label for="arcon_jqs"><?php _e('Select JQuery script for portfolio items animation','arcoportfolio');?></label>
  </td>
</tr>
<tr id="mixitup_about" style="display:none;">
  <td>&nbsp;</td>
  <td><a href="https://mixitup.kunkalabs.com/" target="_blank">MixItUp</a> <?php _e('is a jQuery plugin providing animated filtering and sorting','arcoportfolio');?></td>
</tr>
<tr id="isotope_about" style="display:none;">
  <td>&nbsp;</td>
  <td><a href="http://isotope.metafizzy.co/" target="_blank">Isotope</a> <?php _e('Filter and sort magical layouts jQuery plugin','arcoportfolio');?></td>
</tr>
<tr>
  <th><label for="arcon_lightbox"><?php _e('Lightbox script','arcoportfolio');?></label></th>
  <td>
   <select id="arcon_lightbox" name="arcon_lightbox">
    <option value="colorbox" <?php if ( isset($settings["arcon_lightbox"]) && $settings["arcon_lightbox"] == 'colorbox' ) echo 'selected="selected"'; ?>>ColorBox.js</option>
    <option value="fancybox" <?php if ( isset($settings["arcon_lightbox"]) && $settings["arcon_lightbox"] == 'fancybox' ) echo 'selected="selected"'; ?>>FancyBox.js</option>
  </select>
  <label for="arcon_lightbox"><?php _e('Select JQuery script for portfolio items lightbox effect','arcoportfolio');?></label>
</td>
</tr>
<tr id="colorbox_about" style="display:none;">
  <td>&nbsp;</td>
  <td><a href="http://www.jacklmoore.com/colorbox/" target="_blank">Colorbox</a>. <?php _e('A lightweight customizable lightbox plugin for jQuery','arcoportfolio');?></td>
</tr>
<tr id="fancybox_about" style="display:none;">
  <td>&nbsp;</td>
  <td><a href="http://fancybox.net/" target="_blank">Fancybox</a> <?php _e('is a tool for displaying images, html content and multi-media in a Mac-style "lightbox"','arcoportfolio');?></td>
</tr>
<?php
break;
}
echo '</table>';
}
?>
<p class="submit" style="clear: both;">
  <input type="submit" name="Submit"  class="button-primary" value="<?php _e('Update Settings','arcoportfolio');?>" />
  <input type="hidden" name="arcop-settings-submit" value="Y" />
</p>
</form>
</div>
</div>
<?php
// Save settings
function arcop_save_theme_settings() {
 global $pagenow;
 $settings = get_option( "arcop_settings" );

 if ( $pagenow == 'admin.php' && $_GET['page'] == 'arcop_settings' ){
  if ( isset ( $_GET['tab'] ) )
   $tab = sanitize_key($_GET['tab']);
 else
   $tab = 'homepage';

 switch ( $tab ){
   case 'jquery' :
   $settings['arcon_jqs'] = sanitize_key($_POST['arcon_jqs']);
   $settings['arcon_lightbox'] = sanitize_key($_POST['arcon_lightbox']);
   break;
   case 'homepage' :
   $settings['arcon_columns'] = intval($_POST['arcon_columns']);
   $settings['arcon_columns_tablets'] = intval($_POST['arcon_columns_tablets']);
   $settings['arcon_columns_phones'] = intval($_POST['arcon_columns_phones']);
   $settings['arcon_shadow'] = ($_POST['arcon_shadow'] == 'yes' ? 'yes' : 'no');
   $settings['arcon_color_scheme'] = ($_POST['arcon_color_scheme'] == 'dark' ? 'dark' : 'light');
   $settings['arcon_nw'] = ($_POST['arcon_nw'] == 'true' ? 'true' : 'false');
   $settings['arcon_postlink'] = sanitize_text_field($_POST['arcon_postlink']);
   $settings['arcon_url_link'] = sanitize_text_field($_POST['arcon_url_link']);
   break;
 }
}
$updated = update_option( "arcop_settings", $settings );
?>
<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
 <p><strong><?php _e('Settings Saved','arcoportfolio');?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Hide this message','arcoportfolio');?></span></button></div>
 <?php
}
?>
</div>

<div id="postbox-container-1" class="postbox-container">

  <div class="postbox">
    <h3><span><?php _e('Like this plugin?', 'arcoportfolio');?></span></h3>
    <div class="inside">
      <ul>
        <li><a href="https://wordpress.org/support/view/plugin-reviews/arco-portfolio" target="_blank"><?php _e('Rate it on WordPress.org','arcoportfolio');?></a></li>
        <li><a href="https://twitter.com/wpcoderu" target="_blank"><?php _e('Follow me on Twitter','arcoportfolio');?></a></li>
        <li><a href="http://wpcode.ru/campaign/donate/" target="_blank"><?php _e('Donate','arcoportfolio');?></a></li>
      </ul>
    </div> <!-- .inside -->
  </div> <!-- .postbox -->
</div> <!-- .postbox-container -->
</div>
</div>
</div>