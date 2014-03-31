<h1>Player Information</h1>

<form action="" id="registration_form" method="post">
<?php 
	global $registration_errors; 
	global $woocommerce; 
	if( 'error' == $registration_errors['errors'] ){
		echo do_shortcode( '[alert type="error"]All fields are required.[/alert]' );
		echo do_shortcode( '[separator top="25" style="none"]' );
	}		
?>

<p><a href="#add" id="add_player">Click here to register another player.</a></p>
<table id="player_info">
	<tr>
		<td>Full Name</td>
		<td>Grade</td>
		<td>Camp</td>
		<td>T-Shirt Size</td>
		<td></td>
	</tr>
	<?php if( !isset( $woocommerce->session->player_info ) ) : ?>
	<tr>		
		<td><input type="text" class="input-text" name="camper[1][name]" value="" /></td>
		<td id="grades">
			<select name="camper[1][grade]" class="grade">
				<option value="">-</option>
				<?php get_grade_options(); ?>
			</select>
		</td>
		<?php 
			$camps = get_terms( array( 'camp' ), array( 'hide_empty' => false ) ); 
		?>
		<td id="camps">
			<select name="camper[1][camp]" class="grade">
				<option value="">-</option>
				<?php foreach( $camps as $camp ) : ?>
					<option value="<?php echo $camp->slug; ?>"><?php echo $camp->name; ?></option>
				<?php endforeach; ?>
			</select>			
		</td>
		<?php 
			$sizes = get_terms( array( 'size' ), array( 'hide_empty' => false ) ); 
		?>
		<td id="sizes">
			<select name="camper[1][size]" class="grade">
				<option value="">-</option>
				<?php foreach( $sizes as $size ) : ?>
					<option value="<?php echo $size->slug; ?>"><?php echo $size->name; ?></option>
				<?php endforeach; ?>
			</select>			
		</td>		
		<td></td>
	</tr>
	<?php endif; ?>
	<?php do_action('re_populate_registration_fields');  ?>
</table>

<div class="gform_wrapper" style="padding-top: 40px; ">
	<div class="gfield">
		<table>
			<tr>
				<td>Allergies / Medical Concerns <br />for me to be aware of (medication, inhalers)</td>
				<td><textarea style="color:white;" name="medical_information" id="medical_information" cols="40" rows="5"></textarea></td>
			</tr>
		</table>
	</div>
</div>

<?php echo do_shortcode( '[separator top="25" style="none"]' ); ?>
<p>
	<input id="consent" type="checkbox" name="medical_consent" value="yes" <?php checked($woocommerce->session->medical_consent, 'yes'); ?> />
	<label for="consent">I agree to the <a href="#terms" id="terms">Terms & Conditions.</a></label>
	<p id="release" style="display:none;">I give my consent and approval to the Blue Ridge Basketball Camp, its director, staff, or employees to act on my behalf in securing emergency medical attention for the applicant(s) from a licensed hospital or physician. 

In addition, I attest that my applicant(s) is in good health and that I am aware of the inherent risk of injury involved in athletics. 

I unconditionally release Blue Ridge Basketball from any liabilities, claims, and expenses of any kind whatsoever from participation in the basketball camp.</p>
</p>
<input type="hidden" name="br_register" value="br_registration" />
<p><input type="submit" class="button small green" value="Review & Pay" /></p>
<?php wp_nonce_field( 'registration_action_nonce', 'registration_n' ); ?>

</form>

<?php //do_action('show_br_form_data'); ?>

<script>
	(function($){
		
		$('#add_player').click( function(e){
			var count  = $( '#player_info tr' ).length;
			var camps  = $( '#camps' ).html(); 
			var grades = $( '#grades' ).html();
			var sizes  = $( '#sizes' ).html(); 
			camps  = camps.replace('[1]', '['+count+']'); 
			grades = grades.replace('[1]', '['+count+']'); 
			sizes = sizes.replace('[1]', '['+count+']'); 
			e.preventDefault();
			var id = 'player_' + count; 
			$('#player_info').append( '<tr id="'+id+'"><td><input type="text" class="input-text" name="camper[' + count +'][name]"/></td><td>'+grades+'</td><td>'+camps+'</td><td>'+sizes+'</td><td><a href="#remove" class="remove_player">X</a></td>' );	
			$('#'+id+' td select').find('option:selected').removeAttr("selected");		
		});
		
		$( '.remove_player' ).live( 'click', function(e){
			e.preventDefault(); 
			$(this).parent().parent().remove(); 
		});
		
		$('#terms').click( function(e){
			e.preventDefault(); 
			alert( $('#release').html() );
		} );
		
	})(jQuery)
</script>


