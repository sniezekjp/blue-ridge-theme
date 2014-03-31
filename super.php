<?php
    //Template Name: Super Admin
    if(!is_super_admin){
        header('Location: http://blueridgebasketballcamp.com'); 
    }
?>

<?php get_header(); ?>
<?php $status = get_terms('shop_order_status'); ?>
<pre>
<?php 
    $args = array(
      'post_type' => 'shop_order',
      'post_status' => 'publish',
      'meta_key' => '_customer_user',
      'posts_per_page' => -1,
      'tax_query' => array(
    		array(
    			'taxonomy' => 'shop_order_status',
    			'field' => 'slug',
    			'terms' => 'on-hold'
    		)
        )
    );
    $my_query = new WP_Query($args);
    $customer_orders = $my_query->posts;
    echo 'found posts:' . $my_query->found_posts;
    
    //var_dump($my_query->posts);
    foreach ($customer_orders as $customer_order) {
     $order = new WC_Order();
     $order->populate($customer_order);
     echo '<p>'.$order->id.'</p>';
     echo '<p>'.$order->status.'</p>'; 
     echo '<p>'.$order->billing_email.'</p>'; 
 
    }
?>

<?php get_footer(); ?>