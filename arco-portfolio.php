<?php
/*
Plugin Name: ArCo-Portfolio
Plugin URI: http://wpcode.ru/arco-portfolio
Description: Portfolio plugin to showcase your portfolio items on your site
Version: 1.1
Author: artalex
Author URI: http://wpcode.ru
Text Domain: arcoportfolio
Domain Path: /languages/
*/

// localization
function arcop_plugin_init() {
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain( 'arcoportfolio', false, $plugin_dir.'/languages/' );
}

add_action('plugins_loaded', 'arcop_plugin_init');


// activate plugin
register_activation_hook(__FILE__, 'arcop_set_options');
register_deactivation_hook(__FILE__, 'arcop_unset_options');

// DB settings
$arcop_table = arcop_get_table_handle();
function arcop_get_table_handle() {
    global $wpdb;
    return $wpdb->prefix . "arcoportfolio";
}

// Get plugin options
function get_arcop_options(){
	global $wpdb, $arcop_table;
	$obj = $wpdb->get_results("SELECT * FROM $arcop_table");
	return $obj;
}

// Initialization
function arcop_set_options() {
	global $wpdb; 
	$arcop_table = arcop_get_table_handle();
    $charset_collate = $wpdb->get_charset_collate();
	if($wpdb->get_var("SHOW TABLES LIKE '$arcop_table'") != $arcop_table) { # if table not exists
		$sql = "CREATE TABLE `" . $arcop_table . "` (
			`id` INT NOT NULL AUTO_INCREMENT,
           `arcop_option_1` varchar(1024),
           `arcop_option_2` int,
           UNIQUE KEY id (id)
           ) $charset_collate;
";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);
}
}

// Portfolio Thumbnails size
add_image_size( 'arcop', 480, 480, true );
add_image_size( 'arcop_min', 80, 80, true );

// delete plugin
function arcop_unset_options () {
	global $wpdb;
	$arcop_table = arcop_get_table_handle();
	$sql = "DROP TABLE $arcop_table";
	$wpdb->query($sql);
}

// Load JS & CSS to backend
add_action( 'admin_enqueue_scripts', 'arcop_js_css_backend' );
function arcop_js_css_backend(){
    wp_enqueue_script( 'jquery' );
    wp_enqueue_style('thickbox');
    wp_enqueue_script('media-upload');
    wp_enqueue_script( 'jquery-ui-button' ); 
    wp_register_style('jquery_ui_styles', 'https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
    wp_enqueue_style('jquery_ui_styles');
    wp_register_style('arcop_styles', plugin_dir_url( __FILE__ ).'css/admin-style.css');
    wp_enqueue_style('arcop_styles');
    wp_register_script('arcop_scripts', plugin_dir_url( __FILE__ ).'js/scripts.js');
    wp_enqueue_script('arcop_scripts');
}

// Load JS & CSS to frontend
add_action( 'wp_enqueue_scripts', 'arcop_js_css_fronend' );
function arcop_js_css_fronend() {
    wp_enqueue_script( 'jquery' );

    $settings = get_option( 'arcop_settings' );
    // Jquery animation
    $js_animation = isset($settings['arcon_jqs']) ? $settings['arcon_jqs'] : 'mixitup';
    switch($js_animation) {
        case 'isotope' : wp_register_script('arcop_animation', plugin_dir_url( __FILE__ ).'js/isotope.js'); wp_enqueue_script('arcop_animation'); break;
        case 'mixitup' : wp_register_script('arcop_animation', plugin_dir_url( __FILE__ ).'js/mixitup.js'); wp_enqueue_script('arcop_animation'); break;
        default: wp_register_script('arcop_animation', plugin_dir_url( __FILE__ ).'js/mixitup.js'); wp_enqueue_script('arcop_animation');
    }
    // jQuery lightbox
    $js_lightbox = isset($settings['arcon_lightbox']) ? $settings['arcon_lightbox'] : 'colorbox';
    switch($js_lightbox) { 
        case 'colorbox' : 
        wp_register_script('arcop_colorbox', plugin_dir_url( __FILE__ ).'js/colorbox.js'); wp_enqueue_script('arcop_colorbox');
        wp_register_style('css_arcop_colorbox', plugin_dir_url( __FILE__ ).'css/colorbox.css'); wp_enqueue_style( 'css_arcop_colorbox');
        break;
        case 'fancybox' : 
        wp_register_script('arcop_fancybox', plugin_dir_url( __FILE__ ).'js/fancybox.js'); wp_enqueue_script('arcop_fancybox');
        wp_register_style('css_arcop_fancybox', plugin_dir_url( __FILE__ ).'css/fancybox.css'); wp_enqueue_style( 'css_arcop_fancybox');
        break;
        default:
        wp_register_script('arcop_colorbox', plugin_dir_url( __FILE__ ).'js/colorbox.js'); wp_enqueue_script('arcop_colorbox');
        wp_register_style('css_arcop_colorbox', plugin_dir_url( __FILE__ ).'css/colorbox.css'); wp_enqueue_style( 'css_arcop_colorbox');
    }    
    //FontAwesome
    wp_register_style('font-awesome', plugin_dir_url( __FILE__ ).'css/font-awesome.min.css'); wp_enqueue_style( 'font-awesome');
    // Custom css
    wp_register_style('arcop_css', plugin_dir_url( __FILE__ ).'css/style.css'); wp_enqueue_style('arcop_css');
    // jQuery UI
    wp_enqueue_script('jquery-effects-core');
    // Custom js
    wp_register_script('arcop_js', plugin_dir_url( __FILE__ ).'js/arcop.js'); wp_enqueue_script('arcop_js');
}

// Admin menu plugin links
add_action( 'admin_menu', 'register_arcop_menu_page' );

function register_arcop_menu_page(){
	global $submenu;
	add_menu_page( __('ArCo Portfolio','arcoportfolio'), __('ArCo Portfolio','arcoportfolio'), 'manage_options' , 'arco_portfolio', '', 'dashicons-admin-customizer', 5 );
	add_submenu_page('arco_portfolio', __('Add Portfolio Item','arcoportfolio'), __('Add Portfolio Item','arcoportfolio'), 'manage_options', 'post-new.php?post_type=portfolio');
	add_submenu_page('arco_portfolio', __('Filters','arcoportfolio'), __('Filters','arcoportfolio'), 'manage_options', 'edit-tags.php?taxonomy=portfolio_filter&post_type=portfolio');
	add_submenu_page( 'arco_portfolio', __('Portfolio settings','arcoportfolio'), __('Portfolio settings','arcoportfolio'), 'manage_options', 'arcop_settings', 'arco_portfolio_settings' );
	$submenu['arco_portfolio'][0][0] = __('Portfolio items', 'arcoportfolio');
}

function arco_portfolio_settings(){
  ?>
  <div class="wrap"><h2><?php _e('Portfolio settings','arcoportfolio');?></h2></div>
  <?php
  include_once('arco-portfolio-settings.php');
}



// Add post type portfolio
add_action('init', 'arcop_init');

function arcop_init() {
    $args = array(
        'label' => __('Portfolio items','arcoportfolio'),
        'labels' => array(
          'name' => __('Portfolio Items','arcoportfolio'),
          'singular_name' => __('Portfolio Item','arcoportfolio'),
          'edit_item' => __('Edit Portfolio Items','arcoportfolio'),
          'add_new_item' => __('Add new Portfolio Item','arcoportfolio'),
          'view_item' => __('View Portfolio Item','arcoportfolio'),
          ),
        'singular_label' => __('Portfolio Item','arcoportfolio'),
        'public' => true,
        'show_ui' => true, 
        'show_in_menu' => 'arco_portfolio',
        'show_in_admin_bar'   => true,
        '_edit_link' => 'post.php?post=%d',
        'capability_type' => 'post',
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies' => array('portfolio_filter')
        );       
    register_post_type( 'portfolio' , $args ); 
    flush_rewrite_rules();
}	

// Add portfolio Filter taxonomy
add_action('init', 'arcop_add_filters');

function arcop_add_filters() {
   $args = array(
      'hierarchical' => true, 
      'show_ui' => true, 
      'show_in_menu' => 'arco_portfolio', 
      'label' => __('Portfolio filters','arcoportfolio'), 
      'singular_label' => __('Portfolio filter','arcoportfolio'), 
      'update_count_callback' => '_update_post_term_count', 
      'query_var' => true, 
      'rewrite' => array( 'slug' => 'portfolio-filter', 'with_front' => true ), 
      'public' => true, 
      'show_tagcloud' => true, 
      );
   register_taxonomy('portfolio_filter', 'portfolio', $args);
}

// Add featured images support
add_theme_support( 'post-thumbnails' );



// Add extra fields form for portfolio item
add_action('admin_init', 'arcop_extra_fields', 1);   
function arcop_extra_fields() {  
    add_meta_box( 'arcop_options', __('Item Options','arcoportfolio'), 'arcop_options_func', 'portfolio', 'normal', 'high' );  
}

function arcop_options_func() {
	global $post;
	?>
	<div class="form-wrap">
        <div class="form-field">
            <label for="arco_item_image"><?php _e('Image or Video URL','arcoportfolio');?></label>
            <input id="arco_item_image" name="arco_item_image" value="<?php echo get_post_meta($post->ID, 'arco_item_image', 1);?>" style="width:70%;" type="text">
            <a id="arco_item_image_media" class="button"><?php _e('From Media Library','arcoportfolio');?></a>
            <p><?php _e('Enter full-size image or video (youtube, vimeo etc...) URL for the lightbox. You can also choose Image from your Media gallery.','arcoportfolio');?></p>
        </div>            
        <div class="form-field">
            <label for="arco_item_url"><?php _e('Portfolio URL','arcoportfolio');?></label>
            <input name="arco_item_url" id="arco_item_url" value="<?php echo get_post_meta($post->ID, 'arco_item_url', 1);?>" type="text">
            <p><?php _e('Enter this project live version URL.','arcoportfolio');?></p>
        </div>
    </div>

    <?php 
    wp_nonce_field( 'arco-portfolio-item-options', 'arco-portfolio-item-nonce' );
}


// Save extra fields
add_action('save_post', 'arcop_extra_fields_update', 0);

function arcop_extra_fields_update( $post_id ){
    if ( !isset($_POST['arco-portfolio-item-nonce']) )return false; // check nonce
   	if ( isset($_POST['arco-portfolio-item-nonce']) && !wp_verify_nonce($_POST['arco-portfolio-item-nonce'], 'arco-portfolio-item-options') ) return false; // check nonce value
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false; // check autosave
	if ( !current_user_can('edit_post', $post_id) ) return false; // check user grants
	// Validate and save data
	$arco_item_image = esc_url($_POST['arco_item_image']);
	$arco_item_url = esc_url($_POST['arco_item_url']);
	update_post_meta($post_id, 'arco_item_image', $arco_item_image);
	update_post_meta($post_id, 'arco_item_url', $arco_item_url);
}


// Add columns in portfolio items admin table
add_filter('manage_edit-portfolio_columns', 'add_new_arcop_columns');
function add_new_arcop_columns($arcop_columns){
    $column_thumbnail = array( 'arcop_item_thumb' => __('Thumbnail','arcoportfolio') );
    $column_filter = array( 'arcop_item_filter' => __('Item Filter','arcoportfolio') );
    $arcop_columns = array_slice( $arcop_columns, 0, 1, true ) + $column_thumbnail + array_slice( $arcop_columns, 1, NULL, true );
    $arcop_columns = array_slice( $arcop_columns, 0, 3, true ) + $column_filter + array_slice( $arcop_columns, 3, NULL, true );
    return $arcop_columns;
}

add_action('manage_portfolio_posts_custom_column', 'manage_portfolio_columns', 10, 2);
function manage_portfolio_columns($column_name, $id) {
    global $wpdb;
    switch ($column_name) {
        case 'arcop_item_filter':
        $cats = wp_get_post_terms($id, 'portfolio_filter');
        $cats_array = array();
        foreach($cats as $cat) {
            $cats_array[] = $cat->name;
        }
        echo implode(', ', $cats_array);
        break;
        case 'arcop_item_thumb':
        echo get_the_post_thumbnail( $id, 'arcop_min' );
        break; 
    } // end switch
}

// Shortcode
include_once('arco-portfolio-shortcode.php');
?>