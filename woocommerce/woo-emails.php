<?php
add_action( 'woocommerce_email_order_meta', 'add_player_info_to_email', 10, 3); 
function add_player_info_to_email( $order, $sent_to_admin = false, $plain = false ){ 		
	global $woocommerce; 
?>
	<br />
	<h2>Player Info</h2>
	<?php 
		$args['post_type'] = 'player';
		$args['meta_key'] = 'order_id'; 
		$args['meta_query'][] = array(
			'key' => 'order_id', 
			'value' => (int) $order->id,
			'compare' => '='
		);		
		$players = new WP_Query($args); ?>
		
		<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
			<thead>
				<tr>
					<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Name', 'woocommerce' ); ?></th>
					<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Rising Grade', 'woocommerce' ); ?></th>
					<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Camps', 'woocommerce' ); ?></th>					
				</tr>
			</thead>
			<tbody>		
		
		<?php
		while( $players->have_posts() ) : $players->the_post(); 
			$camps = ''; 
			$all   = ''; 			
			$id    = get_the_ID(); 		
			$camps = wp_get_post_terms( $id, 'camp' );
			$grade = wp_get_post_terms( $id, 'grade' );					
			
			foreach( $camps as $camp ){				
				$all[] = $camp->name; 
			}
			$camps = implode( $all, '<br />' ); 

			echo '<tr>'; 
			
			echo '<td>'.get_the_title().'</td>'; 
			echo '<td>'. $grade[0]->name .'</td>';
			echo '<td>'.$camps.'</td>'; 
			
			echo '</tr>'; 						
		endwhile; 
		wp_reset_postdata(); 
		?>
			
			</tbody>
		</table>
		<?php		
}