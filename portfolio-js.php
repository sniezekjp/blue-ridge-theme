<?php

add_action( 'wp_enqueue_scripts', 'portfolio_js' ); 

function portfolio_js(){
	wp_enqueue_script(
		'portfolio-js',
		 get_stylesheet_directory_uri() . '/js/portfolio-js/js/portfolio.pack.min.js',
		array('jquery')
	);
}

add_shortcode( 'portfolio_slider', 'do_portfolio_slider' ); 

function do_portfolio_slider( $atts ){
	ob_start(); 
		$imgs[] = 'http://blueridgebasketballcamp.com/wp-content/uploads/sites/10/2013/09/weeren-photo.png'; 
		$imgs[] = 'http://blueridgebasketballcamp.com/wp-content/uploads/sites/10/2013/10/kevajphoto-179x300.jpg';		
		$imgs[] = 'http://blueridgebasketballcamp.com/wp-content/uploads/sites/10/2013/09/matt-team.png'; 
		$imgs[]	= 'http://blueridgebasketballcamp.com/wp-content/uploads/sites/10/2013/09/kevin-award.png';
		$imgs[] = 'http://blueridgebasketballcamp.com/wp-content/uploads/sites/10/2013/09/jerry-award.png';		
		$imgs[] = 'http://blueridgebasketballcamp.com/wp-content/uploads/sites/10/2013/10/Kevin_coaching.jpg';
		$imgs[] = 'http://blueridgebasketballcamp.com/wp-content/uploads/sites/10/2013/10/Kevin_GMU.jpg';
	?>
		<div id="gallery">
				
				<?php foreach( $imgs as $pic ) : ?>
		        		<img id="imageframe-1"class="imageframe imageframe-bottomshadow" data-src="<?php echo $pic; ?>" src="<?php echo $pic; ?>" />
					</a>
		        <?php endforeach; ?>
		</div>
		
		<style>
			#gallery img{
				margin-right: 20px; 
			}
		</style>
		
		<script>
			(function($){
				
				var p = $('#gallery').portfolio({
					height : '175px',
				}); 
				p.init();
				
				$('#gallery img').live('click', function(e){
					//$.prettyPhoto( $(this).attr('src'), 'Title', 'Description' ); 
				})		
				
			})(jQuery)
		</script>
    <?
	    $contents = ob_get_contents(); 
	    ob_end_clean(); 
	    return $contents; 	
}