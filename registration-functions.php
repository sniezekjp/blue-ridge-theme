<?php
//construction
//add_action( 'init', 'under_construction' ); 
function under_construction(){
	global $pagenow; 
	if( !is_user_logged_in() && !is_admin() && 'wp-login.php' != $pagenow ){
		echo "Site is currently under construction."; 
		exit; 
	}
}


add_shortcode( 'br_registration', 'do_br_registration' );
function do_br_registration( $atts, $content = null ){
//    if(!is_super_admin()) {
//        return "<p>Under construction</p>";
//    }
	ob_start(); 
	get_template_part( 'content', 'registration2' );
	$contents = ob_get_contents();
	ob_end_clean();
	return $contents; 
}



add_shortcode( 'show_camp_dates', 'do_show_camp_dates' ); 
function do_show_camp_dates( $atts, $content = null ){
	ob_start();
	echo '<h2 style="text-align: center;">';
	$camps = get_terms( array('camp'), array( 'hide_empty' => false ) ); 
	$count = count( $camps ); 
	$x = 1; 
	foreach( $camps as $camp ) :
?>
	&nbsp; &nbsp;&nbsp;<span style="color: #c0c0c0;"><?php echo $camp->name; ?></span>&nbsp; &nbsp; <?php if( $count != $x ){echo '<span style="color: #a0ce4e;">|</span>';} $x++; ?>
<?php
	endforeach; 
	echo '</h2>';
	$contents = ob_get_contents();
	ob_end_clean();
	return $contents; 
}


add_filter( 'test_data',  'filter_test_data', 1, 1 );
function filter_test_data( $data ){
	global $woocommerce;	
	if( isset( $_POST['test_data'] ) ){
			$players['camper'][1] = array(
				'name'    => 'John Sniezek', 
				'camp'    => array( 'boys-84-88' ), 
				'grade'   => 3,
				'size'    => 'adult-medium',
				'medical' => 'None'
			);	
			$players['camper'][2] = array(
				'name'    => 'John Sniezek', 
				'camp'    => array( 'boys-84-88' ), 
				'grade'   => 4,
				'size'    => 'adult-medium',
				'medical' => 'None'
			);	
			$players['camper'][3] = array(
				'name'    => 'John Sniezek', 
				'camp'    => array( 'boys-84-88' ), 
				'grade'   => 5,
				'size'    => 'adult-medium',
				'medical' => 'None'
			);	
			$players['camper'][4] = array(
				'name'    => 'John Sniezek', 
				'camp'    => array( 'boys-84-88' ), 
				'grade'   => 6,
				'size'    => 'adult-medium',
				'medical' => 'None'
			);																
			var_dump($players);
			return $players; 
			
	}
	else{
		return $data; 
	}
}

add_action( 'init',  'br_submit_registration' );
function br_submit_registration(){
		//woocommerce_empty_cart(); 
		global $registration_errors; 
		global $woocommerce;
		
		$lower_fee = 4262; 
		$upper_fee = 4254;
		$prep_fee = 5551;
		
		if( isset( $_POST['br_register'] ) && 'br_registration' == $_POST['br_register'] ){
						
				if ( !isset($_POST['registration_n']) || !wp_verify_nonce( $_POST['registration_n'], 'registration_action_nonce') ){
						print 'Sorry, there was a problem processing your request.';
						exit;
				}
			
				woocommerce_empty_cart(); 			 
				 
				 $woocommerce->session->player_info = $_POST['camper'];
				 $woocommerce->session->medical_consent = $_POST['medical_consent'];
				 
				 if( is_registration_error() )
				 	return; 
				 
				 foreach( $_POST['camper'] as $player ){
					$isPrep = strpos($player['grade'], 'prep');
					if($isPrep !== false) {
						foreach($player['camp'] as $camp){
                            if(strpos($camp, 'prep') !== false) {
                                $woocommerce->cart->add_to_cart($prep_fee);
                            } else {
                                $woocommerce->cart->add_to_cart( $upper_fee );
                            }
						}
					}
					else if( $player['grade'] >= 4){
					 	foreach( $player['camp'] as $camp ){
							 $woocommerce->cart->add_to_cart( $upper_fee ); 
						}
					 }
					 else{
					 	foreach( $player['camp'] as $camp ){
							 $woocommerce->cart->add_to_cart( $lower_fee ); 
						}						 
					 }
				 }
				 
				 wp_redirect( home_url('/review-pay') ); exit;
							
		}
	
}

function is_registration_error(){
	global $registration_errors; 
	$registration_errors = false; 
		if( !isset($_POST['medical_consent']) ){
			$registration_errors['errors'] = 'error'; 
		}
		
		foreach( $_POST['camper'] as $player){
			if( '' == $player['grade'] ){
				$registration_errors['errors'] = 'error';
			}
			
			if( '' == $player['name'] ){
				$registration_errors['errors'] = 'error'; 
			}
			
			if( '' == $player['guardian'] || '' == $player['guardian_email'] || '' == $player['guardian_phone'] ){
				$registration_errors['errors'] = 'error'; 
			}			
			
			if( '' == $player['size'] ){
				//$registration_errors['errors'] = 'error';
			}			
			
			if( 0 == count( $player['camp'] ) ){
				$registration_errors['errors'] = 'error';
			}
		}	
	if($registration_errors == false)
		return false; 
	else
		return true; 
}

add_action( 'show_br_form_data', 'br_show_data' );
function br_show_data(){
		global $woocommerce;
		
		echo '<pre>Session vars: '; 
			var_dump( $woocommerce->session->player_info ); 
		echo '</pre>';
		
		if( isset( $_POST['action'] ) && 'br_registration' == $_POST['action'] ){
			echo '<pre>';
				var_dump( $_POST );
			echo '</pre>';
			
		}
	
}

function get_registration_grade( $grade ){
	selected( $_POST['grade'], $grade );
}

function get_grade_options(){
	$grades = get_terms( array('grade'), array('hide_empty' => false) );
	
	foreach( $grades as $grade ){
			echo '<option value="'.$grade->slug.'" '.get_registration_grade($grade->slug).'>'.$grade->name.'</option>';
	}
}

function get_player_options( $tax, $slug = null ){
	$terms = get_terms( array($tax), array('hide_empty' => false) );
	echo '<option value="">-</option>';
	foreach( $terms as $term ){
			echo '<option value="'.$term->slug.'"'.selected($term->slug, $slug, false).'>'.$term->name.'</option>';
	}	
}

function get_player_camps( $id, $camps = array() ){
	$terms = get_terms( array('camp'), array('hide_empty' => false, 'orderby'=>'id') );
	$max   = get_option('camp_max');
	foreach( $terms as $term ){
	    $show = '';
	    $limit = 1;
	    $year = '2016';
		if( in_array($term->slug, $camps) )
			$checked = 'checked="checked"';
		else
			$checked = '';			
			
			if($max){
    			if(isset($max[$term->slug]) && $max[$term->slug] != 0){
        			$limit = ($max[$term->slug] - $term->count);
        			if($limit > 0){ 
        			    if($limit <= $max['show_at']){
            			    $show = '<br /><span style="display: inline-block;
padding-left: 20px;"> ('.$limit.' spots left)</span>';
                        }
                    }
    			}
			}
			
			$inTheRightYear = strpos($term->name, $year);

			if($inTheRightYear === false) {
				continue;
			}

            if($limit == 0 || $limit < 0){
                $html = $term->name . ' (FULL) <br />';
            }
			else{
                $html  = '<input type="checkbox" name="camper['.$id.'][camp][]" ';
                $html .= 'value="'.$term->slug.'" id="'.$term->slug.'-'.$id.'" ';
                $html .= $checked.' /><label for="'.$term->slug.'-'.$id.'">'.$term->name.$show.'</label><br />'; 
            }
			
            echo $html;
	}	
}


add_action( 'br_checkout_player_information', 'br_show_player_info' ); 
function br_show_player_info(){
	get_template_part('checkout', 'player-info');
}

add_action( 'woocommerce_cart_emptied', 'unset_session_player_info' ); 
function unset_session_player_info(){
	global $woocommerce; 
	unset( $woocommerce->session->player_info ); 
	unset( $woocommerce->session->medical_consent ); 
}

//add_action( 'wp_head', 'test_log' ); 
function test_log(){
	if( is_super_admin() ){
		global $woocommerce; 
		$logger = $woocommerce->logger(); 
		$logger->add( 'test-log', 'New test log message' ); 
	}
}

add_action( 'woocommerce_new_order', 'register_campers' );  
//add_action( 'woocommerce_order_status_on-hold', 'register_campers' );  
//add_action( 'woocommerce_payment_complete', 'register_campers', 1, 1 );  
function register_campers( $order_id ){
	global $woocommerce; 

	if( isset($woocommerce->session->player_info) ){		
		foreach( $woocommerce->session->player_info as $player){
			$data['post_title']  = $player['name']; 
			$data['post_type']   = 'player'; 
			$data['post_status'] = 'publish';		

			$id = wp_insert_post($data);			
			update_post_meta( $id, 'order_id', $order_id ); 
			update_post_meta( $id, 'medical_info', $player['medical'] ); 
			update_post_meta( $id, 'guardian', $player['guardian'] );
			update_post_meta( $id, 'guardian_email', $player['guardian_email'] );
			update_post_meta( $id, 'guardian_phone', $player['guardian_phone'] );
			
			wp_set_object_terms( $id, $player['camp'] , 'camp', true );
			wp_set_object_terms( $id, $player['size'] , 'size', true ); 
			wp_set_object_terms( $id, $player['grade'], 'grade', true ); 
		}
	}
	
}


add_action( 're_populate_registration_fields', 're_pop_fields' ); 
function re_pop_fields(){
	global $woocommerce;
	if( isset( $woocommerce->session->player_info ) ) :	
	foreach( $woocommerce->session->player_info as $key => $player ) : ?>
	<tr>		
		<td><input type="text" class="input-text" name="camper[<?php echo $key; ?>][name]" value="<?php echo $player['name']; ?>" /></td>
		<?php 
			$camps  = get_terms( array( 'camp' ), array( 'hide_empty' => false ) ); 
			$sizes  = get_terms( array( 'size' ), array( 'hide_empty' => false ) ); 
			$grades = get_terms( array( 'grade' ), array( 'hide_empty' => false ) ); 
		?>		
		<td id="<?php row_id( 'grades', $key ); ?>">
			<select name="camper[<?php echo $key; ?>][grade]" class="grade">
				<option value="">-</option>
				<?php foreach( $grades as $grade ) : ?>
					<option value="<?php echo $grade->slug; ?>" <?php selected( $player['grade'], $grade->slug );?>><?php echo $grade->name; ?></option>
				<?php endforeach; ?>								
			</select>
		</td>
		<td id="<?php row_id( 'camps', $key ); ?>">
			<select name="camper[<?php echo $key; ?>][camp]" class="grade">
				<option value="">-</option>
				<?php foreach( $camps as $camp ) : ?>
					<option value="<?php echo $camp->slug; ?>" <?php selected( $player['camp'], $camp->slug );?>><?php echo $camp->name; ?></option>
				<?php endforeach; ?>
			</select>			
		</td>
		<td id="<?php row_id( 'sizes', $key ); ?>">
			<select name="camper[<?php echo $key; ?>][size]" class="grade">
				<option value="">-</option>
				<?php foreach( $sizes as $size ) : ?>
					<option value="<?php echo $size->slug; ?>" <?php selected( $player['size'], $size->slug );?>><?php echo $size->name; ?></option>
				<?php endforeach; ?>
			</select>			
		</td>		
		<td>
			<?php if( 1 != $key ) : ?>
				<a href="#remove" class="remove_player">X</a>
			<?php endif; ?>
		</td>
	</tr>
	
	<?php endforeach; endif; 
}

function row_id( $name, $key ){
	if( 1 == $key ){
		echo $name;
	}
}
