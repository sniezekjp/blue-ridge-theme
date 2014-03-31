<?php

add_filter( 'woocommerce_after_shop_loop_item_title', 'br_custom_title' ); 
function br_custom_title( $title ){
	return $title . ' custom'; 
}