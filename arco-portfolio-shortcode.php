<?php 
add_shortcode( 'arco-portfolio' , 'arco_portfolio_shortcode' ); 

function arco_portfolio_shortcode(){
// Get settings
	$settings = get_option( "arcop_settings" );
// Grid - column width
	$col = ( $settings['arcon_columns'] ? 12 / intval($settings['arcon_columns']) : 3 );
	$col_tablets = ( $settings['arcon_columns_tablets'] ? 12 / intval($settings['arcon_columns_tablets']) : 4 );
	$col_phones = ( $settings['arcon_columns_phones'] ? 12 / intval($settings['arcon_columns_phones']) : 6 );

// Animation script
	$animation_script = isset($settings['arcon_jqs']) ? $settings['arcon_jqs'] : 'mixitup';
	$mix = $animation_script == 'isotope' ? 'isotop' : 'mix';
// Lightbox script
	$lightbox_script = isset($settings['arcon_lightbox']) ? $settings['arcon_lightbox'] : 'colorbox';
// Box Shadow
	$shadow = '';
	if($settings['arcon_shadow'] == 'yes') $shadow = 'arco-shadow';
// Open links in blank window
	$blank = '';
	if($settings['arcon_nw'] == 'true') $blank = 'target="_blank"';
// Contaner class (skin color-scheme)
	$container_class = ($settings['arcon_color_scheme'] == 'dark' ? 'arcop_dark' : 'arcop_light');

// Container
	$return = '<div id="arco-portfolio" class="'.$container_class.'">';
// Filters list
	$return .= '<ul id="portfolio-filter" class="arcop_filters">';
	$return .= '<li><a class="filter" href="#all" rel="#all" data-filter="*">'.__('All','arcoportfolio').'</a></li>';
	$filters = get_categories('taxonomy=portfolio_filter&type=portfolio');
	foreach($filters as $filter) {
		$return .= '<li><a class="filter" href="#filter_'.$filter->cat_ID.'" rel="filter_'.$filter->cat_ID.'" data-filter=".filter_'.$filter->cat_ID.'">'.$filter->name.'</a></li>';
	}
	$return .= '</ul>';

// Thumbnails
	$return .= '<ul id="arco-portfolio-list" class="arco-row da-thumbs">';

	$args = array(
		'post_type' => 'portfolio',
		'posts_per_page' => -1,	
		);

	query_posts($args);
	global $post;
	while (have_posts()) : the_post();
	$arco_item_big = get_post_meta($post->ID, 'arco_item_image', 1);
	$headers = get_headers($arco_item_big, 1); 
	$arcop_gal_type = substr_count($headers["Content-Type"], 'image/') ? 'arcop_gal' : 'arcop_video'; 

	$filter_ids = '';
	foreach(get_the_terms($post->ID, 'portfolio_filter') as $term) { $filter_ids .= 'filter_'.$term->term_id.' ';}
	$return .= '<li class="'.$mix.' '.$filter_ids.' arco-col-md-'.$col.' arco-col-sm-'.$col_tablets.' arco-col-xs-'.$col_phones.'">
	<figure class="arco-portfolio-item '.$shadow.'">
		<a style="display:none" title="'.get_the_title().'" href="'.$arco_item_big.'" class="arcop-button '.$arcop_gal_type.'"><i class="fa fa-search"></i></a>
		<figcaption>'.get_the_title().'</figcaption>'.
		get_the_post_thumbnail($post->ID, 'arcop').'
		<div class="caption" style="display: none;">
			<p>
				<span class="arcop-links">';
					$portfolio_link = get_post_meta($post->ID, 'arco_item_url', 1);
					if($portfolio_link) {
						$return .= '<a '.$blank.' href="'.$portfolio_link.'" class="arcop-link"><i class="fa fa-link"></i> '.$settings['arcon_url_link'].'</a><br />';
					}
					else {$return .= '<br />';}
					$return .= '<a href="'.get_the_permalink().'" class="arcop-link"><i class="fa fa-info"></i> '.$settings['arcon_postlink'].'</a>
				</span>
			</p>
		</div>
	</figure>	
</li>';
endwhile;
wp_reset_query();

	$return .= '</ul>'; // close arco-row	
	$return .= '</div>'; // close container

	if($animation_script == 'mixitup') { 
		$return .= "<script>jQuery(document).ready(function(){jQuery('#arco-portfolio-list').mixItUp();});</script>"; 
	}

	if($animation_script == 'isotope') { 
		$return .= "
		<script>
			jQuery( document ).ready(function() {
				var container = jQuery('#arco-portfolio-list');
    	// filter buttons
				jQuery('#portfolio-filter > li > a').click(function(){
					var curr = jQuery(this);
        // don't proceed if already selected
					if ( ! curr.hasClass('active') ) {
						curr.parents('#portfolio-filter').find('.active').removeClass('active');
						curr.addClass('active');
					}
					var selector = curr.attr('data-filter');
					container.isotope({  itemSelector: '.isotop', filter: selector });
					return false;
				});    
});
</script>
"; 
}

if($lightbox_script == 'colorbox') {
	$return .= '<script>jQuery(document).ready(function(){ jQuery(".arcop_gal").colorbox(); jQuery(".arcop_video").colorbox({iframe:true, innerWidth:640, innerHeight:390}); });</script>';
}

if($lightbox_script == 'fancybox') {
	$return .= '<script>jQuery(document).ready(function(){ jQuery(".arcop_gal").fancybox(); jQuery(".arcop_video").fancybox({"type":"iframe", "width":640, "height":390});});</script>';
}

return $return;
}
?>