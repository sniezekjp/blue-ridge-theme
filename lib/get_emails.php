<?php

//numbers display function
require('numbers-display.php');


add_action( 'admin_menu', 'get_emails' );
function get_emails(){
        
        add_menu_page( 'Camp Settings', 'Camp Settings', 'shop_manager', 'camp_settings', 'display_camp_settings', '', '2.5' );
        
        add_submenu_page( 'camp_settings','Get Emails', 'Emails', 'shop_manager', 'camp_emails', 'display_camp_emails', '', '2.5' );

        add_submenu_page( 'camp_settings','Camp numbers', 'Camp Numbers', 'shop_manager', 'camp_numbers', 'display_camp_numbers' );        
}

function display_camp_settings(){ 
    $terms = get_terms(array('camp'), array('hide_empty' => 0));
    echo '<pre>';
    if(isset($_POST['max'])){
        update_option('camp_max', $_POST['camp_max']);
    }
    $max   = get_option('camp_max');
    echo '</pre>';    
?>
    <h1>Camp Settings</h1>
    <h3>Max Players Settings</h3>
    <form action="" method="post">
        <table>
        <tr>
            <td>Start showing <br />remaining spots at:</td>
            <td><input type="text" name="camp_max[show_at]" value="<?php echo $max['show_at'] ? $max['show_at'] : 30; ?>" /></td>
        </tr>
        <tr>
            <td><h4>Spots available for each camp</h4></td>
        </tr>
        <?php foreach($terms as $camp) : ?>
            <?php 
                $inTheRightYear = strpos($camp->name, '2016');
                if($inTheRightYear === false) {
                    continue;
                }
            ?>
            <tr>
                <td><?php echo $camp->name; ?></td>
                <td><input type="text" name="camp_max[<?php echo $camp->slug?>]" value="<?php 
                echo $max[$camp->slug] ? $max[$camp->slug] : '0';?>" /></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td></td>
            <td>0 means that there is no limit</td>
        </tr>          
        <tr>
            <td><input type="submit" value="Save" name="max" /></td>
        </tr>
        </table>
    </form>
<?php    
}

function display_camp_emails(){
    $terms = get_terms(array('camp'));
    ?>
    <h1>Get Emails</h1>
    <form action="" method="post">
        <select name="camp" id="camp">
            <option value="">-</option>
            <?php foreach($terms as $term) : ?>
                <?php $selected = isset($_POST['camp']) ? selected($term->slug, $_POST['camp'], false) : ''; ?>
                <option value="<?php echo $term->slug?>" <?php echo $selected; ?>><?php echo $term->name;?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Get Email List" />
    </form>
    
    <?php
    
    if(isset($_POST['camp'])){
        $results = get_posts( array(
            'post_type' => 'player', 
            'camp' => $_POST['camp'],
            'meta_key' => 'guardian_email',
            'posts_per_page' => -1
        ));
        $email_list = array(); 
        foreach($results as $player){
            $email_list[] = get_post_meta($player->ID, 'guardian_email', true);
        }
        $list = implode(', ', array_unique($email_list)); 
        ?>
        <textarea name="list" id="list" cols="30" rows="10">
        <?php echo $list; ?>
        </textarea>
        <?php
    }
    ?>
    <h1>Orders on-hold</h1>
    <form action="" method="post">
        <input type="submit" value="Get On-hold emails" name="orders-on-hold" />
    </form>
    <?php
        if(isset($_POST['orders-on-hold'])){
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
        $email_list = array(); 
        foreach($customer_orders as $customer_order){
            $order = new WC_Order();
            $order->populate($customer_order);
            $email_list[] = $order->billing_email;
        }
        $list = implode(', ', array_unique($email_list)); 
        ?>
        <textarea name="on-hold-list" id="on-hold-list" cols="30" rows="10"><?php echo $list; ?></textarea>
        <?php
        }//end if $_POST 
    
}
    