jQuery(document).ready(function(){ 

    jQuery('.arco-portfolio-item').hover(  

        function(){

            jQuery(this).find('figcaption').stop().animate({left: 0, opacity: 1}, {duration: 300, easing: 'easeOutBounce'});
            jQuery(this).find('.caption').stop().fadeIn();
            jQuery(this).find('.arcop-button').stop().fadeIn('slow');
            jQuery(this).find('.caption p').stop().animate({top: "100%", opacity: 1}, {duration: 300, easing: 'easeOutBounce'});
        }, 
        
        function(){
            jQuery(this).find('figcaption').stop().animate({left: "-100%", opacity: 0}, {duration: 300, easing: 'easeOutBounce'});
            jQuery(this).find('.caption').stop().fadeOut();
            jQuery(this).find('.arcop-button').stop().hide();
            jQuery(this).find('.caption p').stop().animate({top: 0, opacity: 0}, 200);
        }
        );
       
    });