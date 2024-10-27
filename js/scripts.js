jQuery(document).ready(function() {


	jQuery( ".arcon-radio" ).buttonset();	

restore_send_to_editor = window.send_to_editor;

			jQuery('#arco_item_image_media').click(function() {
			uploadID = jQuery('#arco_item_image');
 			formfield = jQuery('#arco_item_image').attr('name');
 			tb_show('Full-size image or video', 'media-upload.php?type=image&amp;TB_iframe=true');
 			upload_img(uploadID)	
 			return false;
			});

function upload_img(uploadID) {
			window.send_to_editor = function(html) {
 			imgurl = jQuery('img',html).attr('src');
 			uploadID.val(imgurl);
 			tb_remove();
 			window.send_to_editor = restore_send_to_editor;
			}
		}		
 
			


			jQuery('#arcon_jqs').change(function(){show_animation_script_desc();});
			show_animation_script_desc();

			jQuery('#arcon_lightbox').change(function(){show_lightbox_script_desc();});
			show_lightbox_script_desc();
		});

function show_animation_script_desc() {
			if(jQuery('#arcon_jqs').val() == 'mixitup') { jQuery('#mixitup_about').show(); jQuery('#isotope_about').hide();  }
			if(jQuery('#arcon_jqs').val() == 'isotope') { jQuery('#mixitup_about').hide(); jQuery('#isotope_about').show();  }
			}

function show_lightbox_script_desc() {
			if(jQuery('#arcon_lightbox').val() == 'colorbox') { jQuery('#colorbox_about').show(); jQuery('#fancybox_about').hide();  }
			if(jQuery('#arcon_lightbox').val() == 'fancybox') { jQuery('#colorbox_about').hide(); jQuery('#fancybox_about').show();  }
			}			