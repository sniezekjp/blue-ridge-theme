<h1>Registration Information</h1>

<?php
	if(false){
		echo do_shortcode('[alert type="notice"]Registration is closed for the 4-7th grades boy\'s camp in July.[/alert]');
		echo do_shortcode( '[separator top="25" style="none"]' );
	}
?>

<form action="" id="registration_form" method="post">
<?php 
	global $registration_errors; 
	global $woocommerce; 
	if( 'error' == $registration_errors['errors'] ){
		echo do_shortcode( '[alert type="error"]All fields are required. You must also agree to the Terms & Conditions.[/alert]' );
		echo do_shortcode( '[separator top="25" style="none"]' );
	}		
?>

<?php function get_reg_table( $id, $data = null ){ ?>

<?php if( 1 != $id ) : ?>
<p><a href="#" class="delete_player" data-delete_id="<?php echo $id; ?>">Delete Player</a></p>
<?php endif; ?>
<table class="reg_table reg_table-<?php echo $id; ?>">
	<tr>
		<td><h3>Player Information</h3></td>
		<td></td>
	</tr>
	<tr>
		<td>Full Name</td>
		<td><input type="text" name="camper[<?php echo $id;?>][name]" class="input-text input-name" data-player_id="<?php echo $id; ?>" value="<?php if( $data['name'] ){ echo $data['name']; }?>" /></td>
	</tr>
	<tr>
		<td>Camps</td>
		<td><?php get_player_camps( $id, $data['camp'] ? $data['camp'] : array() ); ?></td>
	</tr>	
	<tr>
		<td>Rising Grade</td>
		<td><select name="camper[<?php echo $id; ?>][grade]" id="" class="select"><?php get_player_options('grade', $data['grade']); ?></select></td>
	</tr>
	<tr>
		<td>T-Shirt Size</td>
		<td><select name="camper[<?php echo $id;?>][size]" id="" class="select"><?php get_player_options('size', $data['size']); ?></select></td>
	</tr>
	<tr>
		<td>Allergies / Medical Concerns <br />for me to be aware of <br />(medication, inhalers)</td>
		<td><textarea class="input-text" name="camper[<?php echo $id; ?>][medical]" id="" cols="30" rows="10"><?php echo $data['medical'] ? $data['medical'] : 'None'; ?></textarea></td>
	</tr>
	<tr>
		<td><h3>Parent/Guardian Information</h3></td>
		<td></td>
	</tr>
	<tr>
		<td>Full Name</td>
		<td><input type="text" name="camper[<?php echo $id;?>][guardian]" class="input-text" data-player_id="<?php echo $id; ?>" value="<?php if( $data['guardian'] ){ echo $data['guardian']; }?>" /></td>
	</tr>
	<tr>
		<td>Email</td>
		<td><input type="text" name="camper[<?php echo $id;?>][guardian_email]" class="input-text" data-player_id="<?php echo $id; ?>" value="<?php if( $data['guardian_email'] ){ echo $data['guardian_email']; }?>" /></td>
	</tr>
	<tr>
		<td>Phone Number</td>
		<td><input type="text" name="camper[<?php echo $id;?>][guardian_phone]" class="input-text" data-player_id="<?php echo $id; ?>" value="<?php if( $data['guardian_phone'] ){ echo $data['guardian_phone']; }?>" /></td>
	</tr>	
</table>
<?php } ?>

<?php //woocommerce_empty_cart(); ?>

<div id="players" class="tab-holder shortcode-tabs clearfix tabs-vertical">
	<div class="tab-hold tabs-wrapper">
	<?php if( !isset($woocommerce->session->player_info) ) : ?>
		<ul id="tabs" class="tabset tabs">
			<li class="active"><a href="#tab1" id="player-1">Player 1</a></li>
			<a href="#" id="add_a_player">Add Player</a>
		</ul>
		<div class="tab-box tabs-container">
			<div id="tab1" class="tab tab_content" style="display: block;"><?php get_reg_table(1); ?></div>
		</div>
	<?php else : ?>
		<ul id="tabs" class="tabset tabs">
		<?php foreach( $woocommerce->session->player_info as $key => $camper ) : ?>	
			<?php $name = $camper['name'] ? $camper['name'] : 'Player ' . $key; ?>
			<li class="<?php echo $key == 1 ? 'active' : '';?>"><a href="#tab<?php echo $key;?>" id="player-<?php echo $key; ?>"><?php echo $name; ?></a></li>	
		<?php endforeach; ?>
			<a href="#" id="add_a_player">Add Player</a>
		</ul>	
		<div class="tab-box tabs-container">
		<?php foreach( $woocommerce->session->player_info as $key => $camper ) : ?>	
			<div id="tab<?php echo $key; ?>" class="tab tab_content" style="display: block;"><?php get_reg_table($key, $camper ); ?></div>
		<?php endforeach; ?>			
		</div>
	<?php endif; ?>
	</div>
</div>

<div class="one_half"></div>
<div class="one_half last">
	<p class="">
		<input class="" id="consent" type="checkbox" name="medical_consent" value="yes" <?php checked($woocommerce->session->medical_consent, 'yes'); ?> />
		<label for="consent" class="">I agree to the <a href="#terms" id="terms">Terms & Conditions.</a></label>
		<p id="release" style="display:none;">I give my consent and approval to the Blue Ridge Basketball Camp, its director, staff, or employees to act on my behalf in securing emergency medical attention for the applicant(s) from a licensed hospital or physician. 
	
In addition, I attest that my applicant(s) is in good health and that I am aware of the inherent risk of injury involved in athletics. 
	
I unconditionally release Blue Ridge Basketball from any liabilities, claims, and expenses of any kind whatsoever from participation in the basketball camp.</p>
	</p>

<input type="hidden" name="br_register" value="br_registration" />
<p><input type="submit" class="button small green" value="Review & Pay" /></p>

</div>
<?php wp_nonce_field( 'registration_action_nonce', 'registration_n' ); ?>

</form>

<script id="registrationTemplate" type="text/template">
	<div id="tab{{id}}" class="tab tab_content" style="display:none;">
	<?php get_reg_table('{{id}}', array() ); ?>
	</div>
</script>

<script>
	(function($){
		$('.delete_player').live('click', function(e){
			e.preventDefault(); 
			var id = $(this).data('delete_id'); 
			$('#tab'+id).remove(); 
			$('#player-'+id).parent().remove();
			$('#player-1').trigger('click');
		});
		
		$('.reg_table .input-name').live('change', function(){
			var id = $(this).data('player_id');
			var name = $( '#player-' + id );
			var val = $(this).val(); 
			
			if( '' == val )
				name.html( 'Player ' + id  );
			else
				name.html( $(this).val() ); 
			//alert( $(this).data('player_id') ); 
		});
		
		$('#add_a_player').live('click', function(e){
			e.preventDefault();	
			var template = $('#registrationTemplate').html();			
			var data = { id : $('#registration_form table').length + 1 };
			var result = Mustache.to_html(template, data); 
			
			$('#result').append(result);
			
			$('#tabs li:last').after('<li><a href="#tab'+data.id+'" id="player-'+data.id+'">Player '+data.id+'</a></li>');
			$('.tab-box').append( Mustache.to_html(template, data) );
			$('#player-'+data.id).trigger('click');
		});
		
		$('#terms').click( function(e){
			e.preventDefault(); 
			alert( $('#release').html() );
		} );
			
	})(jQuery)
	
</script>

<div style="clear:both;"></div>

<?php //do_action('show_br_form_data'); ?>