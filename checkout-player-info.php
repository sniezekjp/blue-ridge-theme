<?php 
	function display_camps($camps){
		foreach($camps as $camp){
			echo '-' . $camp->name . '<br />';
		}
	}
?>
<h2>Player Information</h2>

<?php global $woocommerce; ?>

<table class="review_players">
	<tr>
		<td><h3>Name</h3></td>
		<td><h3>Rising Grade</h3></td>
		<td><h3>Camps</h3></td>
		<td style="display:none;"><h3>Size</h3></td>
		<td><h3>Medical</h3></td>
		<td><h3>Contact</h3></td>
	</tr>
	<?php foreach( $woocommerce->session->player_info as $player ) : ?>
	<?php
		$camps = '';
		foreach( $player['camp'] as $camp ){
			$camps[] = get_term_by('slug', $camp, 'camp');  			
		}		
		$size  = get_term_by('slug', $player['size'], 'size'); 
		$grade = get_term_by('slug', $player['grade'], 'grade');  
	?>
		
		<tr>
			<td><p><?php echo $player['name']; ?></p></td>
			<td><p><?php echo $grade->name;    ?></p></td>
			<td><p><?php display_camps($camps);    ?></p></td>
			<td style="display:none;"><p><?php echo $size->name;     ?></p></td>
			<td><p><?php echo $player['medical']; ?></p></td>
			<td><p><?php echo $player['guardian'] . '<br />' . $player['guardian_email'] . '<br />' . $player['guardian_phone']; ?></p></td>
		</tr>
	<?php endforeach; ?>
</table>


<?php echo do_shortcode( '[separator top="20" style="none"]' ); ?>
<p><a style="color: #a0ce4e;" href="<?php echo home_url('/register'); ?>">Click here </a> to edit information.</p>
