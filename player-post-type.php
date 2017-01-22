<?php

add_action( 'init', 'register_players' );
add_action( 'init', 'register_player_taxonomy', 0 );

function register_players() {
  $labels = array(
    'name'               => 'Players',
    'singular_name'      => 'Player',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New Player',
    'edit_item'          => 'Edit Player',
    'new_item'           => 'New Player',
    'all_items'          => 'All Players',
    'view_item'          => 'View Player',
    'search_items'       => 'Search Players',
    'not_found'          => 'No Players found',
    'not_found_in_trash' => 'No Players found in Trash',
    'parent_item_colon'  => '',
    'menu_name'          => 'Players'
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'player' ),
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => 2,
    'supports'           => array( 'title', 'custom-fields' )
  );

  register_post_type( 'player', $args );
}




function register_player_taxonomy(){
	$labels = array(
		'name'              => _x( 'Camps', 'taxonomy general name' ),
		'singular_name'     => _x( 'Camp', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Camps' ),
		'all_items'         => __( 'All Camps' ),
		'parent_item'       => __( 'Parent Camp' ),
		'parent_item_colon' => __( 'Parent Camp:' ),
		'edit_item'         => __( 'Edit Camp' ),
		'update_item'       => __( 'Update Camp' ),
		'add_new_item'      => __( 'Add New Camp' ),
		'new_item_name'     => __( 'New Camp Name' ),
		'menu_name'         => __( 'Camps' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'camp' ),
	);

	register_taxonomy( 'camp', array( 'player', 'product' ), $args );
	
	$labels = array(
		'name'              => _x( 'Grades', 'taxonomy general name' ),
		'singular_name'     => _x( 'Grade', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Grades' ),
		'all_items'         => __( 'All Grades' ),
		'parent_item'       => __( 'Parent Grade' ),
		'parent_item_colon' => __( 'Parent Grade:' ),
		'edit_item'         => __( 'Edit Grade' ),
		'update_item'       => __( 'Update Grade' ),
		'add_new_item'      => __( 'Add New Grade' ),
		'new_item_name'     => __( 'New Grade Name' ),
		'menu_name'         => __( 'Grades' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'grade' ),
	);

	register_taxonomy( 'grade', array( 'player' ), $args );	
	
	$labels = array(
		'name'              => _x( 'Sizes', 'taxonomy general name' ),
		'singular_name'     => _x( 'Size', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Sizes' ),
		'all_items'         => __( 'All Sizes' ),
		'parent_item'       => __( 'Parent Size' ),
		'parent_item_colon' => __( 'Parent Size:' ),
		'edit_item'         => __( 'Edit Size' ),
		'update_item'       => __( 'Update Size' ),
		'add_new_item'      => __( 'Add New Size' ),
		'new_item_name'     => __( 'New Size Name' ),
		'menu_name'         => __( 'Sizes' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'size' ),
	);

	register_taxonomy( 'size', array( 'player' ), $args );			
}




add_filter( 'manage_edit-player_columns', 'my_edit_player_columns' ) ;

function my_edit_player_columns( $columns ) {

	//var_dump( $columns ); 
	
	
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Name' ),
		'grade' => __( 'Grade' ),
		'camp' => __( 'Camp' ),
		'size' => __( 'T-Shirt Size' ),
		'aftercare' => __( 'Aftercare' ),
		'medical' => __( 'Medical Info' ),
		'guardian' => __( 'Emergency Contact' ),
		'order_id' => __( 'Order ID' ),
		'date' => __( 'Date' )
	);

	return $columns;
}


add_action( 'manage_player_posts_custom_column', 'do_manage_player_cols', 10, 2 );

function do_manage_player_cols( $col, $post_id ){
	
		switch( $col ){
			case "grade" :  			
			case "camp" : 
			case "size" : 
				/* Get the genres for the post. */
				$terms = get_the_terms( $post_id, $col );
	
				/* If terms were found. */
				if ( !empty( $terms ) ) {
	
					$out = array();
	
					/* Loop through each term, linking to the 'edit posts' page for the specific term. */
					foreach ( $terms as $term ) {
						$out[] = $term->name;
					}
	
					/* Join the terms, separating them with a comma. */
					echo join( '<br /> ', $out );
				}
	
				/* If no terms were found, output a default message. */
				else {
					_e( 'No Assigned ' . ucfirst($col) );
				}
				break; 
			case "medical" :
				echo get_post_meta( $post_id, 'medical_info', true ); 
				break;
			case "aftercare" :
				echo get_post_meta( $post_id, 'aftercare', true ); 
				break;				 
			case "guardian" :
				echo get_post_meta( $post_id, 'guardian', true ) . '<br />'; 
				echo get_post_meta( $post_id, 'guardian_email', true ) . '<br />';
				echo get_post_meta( $post_id, 'guardian_phone', true );
				break; 				
			case "order_id" : 
				$id  = get_post_meta( $post_id, 'order_id', true ); 
				$view = add_query_arg( array('post' => $id, 'action'=> 'edit') , admin_url('post.php') );
				echo '<a href="'.$view.'">View Order #'.$id.'</a>'; 
				break; 
			default : 
				echo $col; 
				break; 
				
		}
	
}

add_action( 'restrict_manage_posts', 'wpse45436_admin_posts_filter_restrict_manage_posts' );
/**
 * First create the dropdown
 * make sure to change POST_TYPE to the name of your custom post type
 * 
 * @author Ohad Raz
 * 
 * @return void
 */
function wpse45436_admin_posts_filter_restrict_manage_posts(){
    $type = 'player';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }

    //only add filter to post type you want
    if ('player' == $type){
        //change this to the list of values you want to show
        //in 'label' => 'value' format
        $grades = get_terms( array('grade') ); 
        ?>
        <select name="grade">
        <option value=""><?php _e('Show all Grades', 'wose45436'); ?></option>
        <?php
            $current_v = isset($_GET['grade']) ? $_GET['grade'] : '';
            foreach ($grades as $grade) {
                printf
                    (
                        '<option value="%s"%s>%s</option>',
                        $grade->slug,
                        $grade->slug == $current_v ? ' selected="selected"':'',
                        $grade->name
                    );
                }
        ?>
        </select>
        
        <?php 
        	$camps = get_terms( array('camp') );    
        ?>
        
        <select name="camp">
        <option value=""><?php _e('Show all Camps', 'wose45436'); ?></option>
        <?php
            $current_v = isset($_GET['camp']) ? $_GET['camp'] : '';
            foreach ($camps as $camp) {
                printf
                    (
                        '<option value="%s"%s>%s</option>',
                        $camp->slug,
                        $camp->slug == $current_v? ' selected="selected"':'',
                        $camp->name
                    );
                }
        ?>
        </select>
<?php 
        	$sizes = get_terms( array('size') );    
        ?>
        
        <select name="size">
        <option value=""><?php _e('Show all Sizes', 'wose45436'); ?></option>
        <?php
            $current_v = isset($_GET['size']) ? $_GET['size'] : '';
            foreach ($sizes as $size) {
                printf
                    (
                        '<option value="%s"%s>%s</option>',
                        $size->slug,
                        $size->slug == $current_v? ' selected="selected"':'',
                        $size->name
                    );
                }
        ?>
        </select>
        
        <input type="text" name="order_id" value="<?php echo getGetVar('order_id'); ?>" placeholder="Order ID" />     
        <?php
    }
}


function getGetVar( $key ){
	return isset( $_GET[$key] ) ? $_GET[$key] : ''; 
}



add_action( 'parse_query', 'status_filter' );
function status_filter() {

	global $pagenow, $post_type;

	if( 'edit.php' != $pagenow || 'player' != $post_type || !isset( $_GET['order_id'] ) )
		return;
	
	if( '' == $_GET['order_id'] )
		return; 

	$meta_group = array(
		'key' => 'order_id',
		'value' => $_GET['order_id'],
		'compare' => '='
	);

	set_query_var( 'meta_query', array( $meta_group ) );
}
