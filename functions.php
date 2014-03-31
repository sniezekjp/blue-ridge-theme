<?php

require( 'registration-functions.php' ); 
require( 'player-post-type.php' ); 
require( 'portfolio-js.php' ); 
require( 'woocommerce/woo-emails.php' ); 
require( 'lib/get_emails.php' );

add_action( 'init', 'reset_cart' ); 
function reset_cart(){	
	if( isset( $_GET['reset'] ) ){
		woocommerce_empty_cart();
		wp_redirect(home_url()); 
		exit; 
	}
}

add_action( 'init', 'change_admin_url' ); 
function change_admin_url(){	
    update_option( 'admin_email', 'kevinweeren@hotmail.com' ); 
}

//add_action( 'init', 'construction' ); 
function construction(){
	if( !is_user_logged_in() )
		exit( 'Site is currently under construction.' ); 
}

add_shortcode('login_form', 'show_login_form' ); 
function show_login_form(){
	global $login_user;
	ob_start(); ?>
		<?php 
			$error = ''; 
			if( is_wp_error($login_user) ){
				$error = '<p>'.$login_user->get_error_message() .'</p>' ; 				
			}
		?>
		<form action="" method="post">
			<div class="avada-row">
				<div class="one_third"></div>
				<div class="one_third">
					<?php echo $error; ?>
					<p><input type="text" name="log" class="input-text" placeholder="Username" /></p>
					<p><input type="password" name="pwd" class="input-text" placeholder="Password" /></p>
					<p><input id="remember" name="rememberme" type="checkbox" /><label for="remember">Remember me</label></p>
					<p><input type="submit" name="submit"class="button default small" value="Log In" /></p>
					<input type="hidden" name="redirect_to" value="<?php echo admin_url(); ?>" />
					<input type="hidden" name="action" value="login_user" />
				</div>
				<div class="one_third last"></div>
			</div>
		</form>
	<?php
	$content = ob_get_contents(); 
	//$content .= wp_login_form(array('echo' => false));
	ob_end_clean();
	return $content; 
	//return wp_login_form( $args ); 
}

add_action( 'after_setup_theme', 'login_user' ); 
function login_user(){
	global $login_user; 
	if( isset( $_POST['action'] ) && 'login_user' == $_POST['action'] ){
		$login_user = wp_signon(); 
		if( !is_wp_error($login_user) ){
			wp_redirect( $_POST['redirect_to'] ); exit; 
		}else{
			//echo $user->get_error_message(); 
		}
	}	
}


//change login page
function br_custom_login() {
	wp_enqueue_style( 'br-custom-login-style',  get_stylesheet_directory_uri() . '/css/login.css', false ); 
}
add_action( 'login_enqueue_scripts', 'br_custom_login', 10 );

function annointed_admin_bar_remove() {
        global $wp_admin_bar;

        /* Remove their stuff */
        if( !is_super_admin() )
	        $wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'annointed_admin_bar_remove', 0);


function remove_footer_admin () {
	return '';
}
add_filter('admin_footer_text', 'remove_footer_admin');


add_action( 'admin_menu', 'my_remove_menu_pages' );
function my_remove_menu_pages() {
	if( !is_super_admin() ){
		remove_menu_page('edit.php?post_type=themefusion_elastic');
		remove_menu_page('edit.php?post_type=avada_portfolio'); 
		remove_menu_page('edit.php?post_type=feedback'); 
		remove_menu_page('admin.php?page=jetpack'); 
	}
}

function include_mustache() {
	wp_enqueue_script(
		'mustache-js',
		 get_stylesheet_directory_uri() . '/js/mustache.js',
		array('jquery'), '', true
	);
	
}

add_action( 'wp_enqueue_scripts', 'include_mustache' );

add_filter('logout_url', 'br_redirect_after_logout', 10, 2);
function br_redirect_after_logout($logouturl, $redir){
	$redir = home_url('/');
	return $logouturl . '&amp;redirect_to=' . urlencode($redir);
}

add_action( 'init', 'redirect_logout' ); 
function redirect_logout(){
	global $pagenow; 
			
	if( 'wp-login.php' == $pagenow && true == $_GET['loggedout'] ){
		wp_redirect( home_url() ); 		
		exit; 
	}
}