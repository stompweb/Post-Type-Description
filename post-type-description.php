<?php
/*
Plugin Name: Post Type Description
Plugin URI: http://stomptheweb.co.uk
Description: Adds the ability to add a description of your post type into your template
Version: 1.0.0
Author: Steven Jones
Author URI: http://stomptheweb.co.uk/
License: GPL2
*/

// Add a submenu item labelled 'Description' to each public post type (filterable)
function ptd_add_submenu_page() {

	$post_types = ptd_get_post_types();

	foreach ($post_types as $post_type => $post_type_obj) {

		$parent_slug = 'edit.php?post_type='.$post_type;
		$page_title  = $post_type_obj->labels->name . ' description';
		$menu_title  = 'Description';
		$capability  = $post_type_obj->cap->edit_posts;
		$menu_slug	 = $post_type_obj->name . '-description';
		$function    = 'ptd_manage_description';

		add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );

	}
}
add_action('admin_menu', 'ptd_add_submenu_page');

// Admin page to edit the description
function ptd_manage_description() {

	if ( empty( $_GET['post_type'] ) )
		return;

	$post_type = get_post_type_object( $_GET['post_type'] ); 
	
	$current_description = stripslashes(get_option($post_type->name . '-description')); 

	?>
	<h2><?php echo esc_html( $post_type->labels->name ); ?> Description</h2>

	<?php if ( isset( $_GET['updated'] ) && $_GET['updated'] ) { ?>

		<div id="message" class="updated">
			<p>Description Updated.</p>
		</div>

	<?php } ?>

	<form method="POST">
		<div style="width: 95%; margin-top: 50px;">
			<?php wp_editor( $current_description, 'description', $settings = array() ); ?>
		</div>

		<input type="hidden" name="post_type" value="<?php echo esc_attr( $post_type->name ); ?>" />
		
		<p class="submit">
			<input class="button-primary" type="submit" name="ptd_update_description" value="Update Description"/>
		</p>

	</form>


<?php }

// Update the description
function ptd_update_description() {

	if(isset($_POST['ptd_update_description'])) {

		$post_type = $_POST['post_type'];
		$description = $_POST['description'];

		update_option($post_type . '-description', $description);

		wp_redirect( add_query_arg( array('post_type' => $post_type, 'page' => 'description', 'updated' => 'true', 'post_type' => $post_type), $wp_get_referer ) ); exit;

	}

}
add_action('init', 'ptd_update_description');

// Front end function to display the description in the template
function ptd_description() {

	$post_type = get_query_var( 'post_type' );
	$post_type_description = stripslashes(get_option($post_type . '-description'));

	echo apply_filters('the_content', $post_type_description);

}

// Helper function to get all the post types (filterable)
function ptd_get_post_types() {

	$post_types = get_post_types(array(
		'public' => true,
		'show_ui' => true,
	), 'objects');

	// Allow post types to be filterable
	$post_types = apply_filters('ptd_enabled_post_types', $post_types);

	return $post_types;

}

// Helper function to get an array of post types
function pta_get_enabled_post_type_array() {

	$post_types_array = array();

	$post_types = ptd_get_post_types();

	foreach ($post_types as $post_type => $post_type_obj) {

		$post_types_array[] = $post_type;

	}

	return $post_types_array;

}

// It's unlikely that pages will need a description
function ptd_remove_pages_post_type($post_types) {

    unset($post_types['page']);
    return $post_types;
    
}
add_filter('ptd_enabled_post_types', 'ptd_remove_pages_post_type');

// Add Edit Description to the admin bar for better UX :-)
function ptd_admin_bar( $wp_admin_bar ) {

	$post_types = pta_get_enabled_post_type_array();

	if (is_post_type_archive( $post_types )) {

		$post_type = get_query_var( 'post_type' );

		$args = array(
			'id'    => 'edit_description',
			'title' => 'Edit Description',
			'href'  => admin_url() . 'edit.php?page=description&post_type=' . $post_type
		);
	
		$wp_admin_bar->add_node( $args );
	}
}
add_action( 'admin_bar_menu', 'ptd_admin_bar', 999 );