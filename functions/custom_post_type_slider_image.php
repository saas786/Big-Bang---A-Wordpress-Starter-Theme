<?php
/* !-------- CUSTOM POST TYPE ------------------- */
/*------------------------------------------------------------ */

/**
 * This is a Template for a Custom Post Type, in this case a Slider Image Post Type.
 * I use this Post Type for Slideshows like Flexslider.
 * Copy this file and/or customize it for you needs.
 */

function bb_create_cpt_slider_image() {
	$name = array(
		'singular' => _x('Slider Image', 'CPT Slider Image Singular Name', 'big_bang'),
		'plural'   => _x('Slider Images', 'CPT Slider Images Plural Name', 'big_bang')
	 );

	$labels = array(
		'name'               => $name['plural'],
		'singular_name'      => $name['singular'],
		'add_new'            => __('Add', 'big_bang').' '.$name['singular'],
		'add_new_item'       => __('Add new', 'big_bang').' '.$name['singular'],
		'edit_item'          => __('Edit', 'big_bang').' '.$name['singular'],
		'new_item'           => __('New', 'big_bang').' '.$name['singular'],
		'view_item'          => __('View', 'big_bang').' '.$name['singular'],
		'search_items'       => __('Search', 'big_bang').' '.$name['plural'],
		'not_found'          => __('No', 'big_bang').' '.strtolower($name['plural']).' '.__('found', 'big_bang'),
		'not_found_in_trash' => __('No', 'big_bang').' '.strtolower($name['plural']).' '.__('found in Trash', 'big_bang'),
		'parent_item_colon'  => ''
		);

	$args = array(
		'labels'               => $labels,
		'description'          => __('The Custom Post Type for the Slider Images', 'big_bang'),
		'public'               => true,
		'publicly_queryable'   => true,
		'exclude_from_search'  => true,
		'show_ui'              => true,
		'menu_position'        => 6,
		'menu_icon'            => get_stylesheet_directory_uri(). '/assets/images/custom-post-icon-1.png',
		'query_var'            => true,
		'rewrite'              => array('with_front' => false, 'slug' => 'sliderimages'),
		'supports'             => array('title', 'thumbnail'),
		'register_meta_box_cb' => 'bb_register_cpt_slider_image_meta_box'
		);

	register_post_type('slider_image', $args);
}

add_action('init', 'bb_create_cpt_slider_image');

	


/* !-------- Slider Image Metabox ------------------- */
/*------------------------------------------------------------ *\
\*------------------------------------------------------------ */
function bb_register_cpt_slider_image_meta_box() {
	add_meta_box('slider_image_meta_data', __('Slider Image Data', 'big_bang'), 'bb_render_cpt_slider_image_meta_box', 'slider_image', 'normal', 'default');
}


function bb_render_cpt_slider_image_meta_box( $post ) {

	$values = get_post_custom( $post->ID );
	$caption = isset( $values['slider_image_caption'] ) ? esc_attr( $values['slider_image_caption'][0] ) : '';
	$slider_image_ext_link = isset( $values['slider_image_ext_link'] ) ? esc_attr( $values['slider_image_ext_link'][0] ) : '';
	$slider_image_int_link = isset( $values['slider_image_int_link'] ) ? esc_attr( $values['slider_image_int_link'][0] ) : '';

	$posts = get_posts('numberpost=-1');
	$pages = get_pages();

	wp_nonce_field('slider_image_nonce', 'slider_image_nonce_field');
	?>

	<table class="meta-form-table">
		<tr>
			<td class="left">
				<label for="slider_image_caption"><?php _e('Caption', 'big_bang'); ?></label>
			</td>
			<td class="right">
				<textarea name="slider_image_caption" id="slider_image_caption"><?php echo $caption; ?></textarea>
			</td>
		</tr>
		<tr>
			<td class="left">
				<label for="slider_image_ext_link"><?php _e('External Link', 'big_bang'); ?></label>
			</td>
			<td class="right">
				<input type="text" name="slider_image_ext_link" id="slider_image_ext_link" value="<?php echo $slider_image_ext_link; ?>" />
			</td>
		</tr>
		<tr>
			<td class="left">
				<label for="slider_image_int_link"><?php _e('Interal Link', 'big_bang'); ?></label>
			</td>
			<td class="right">
				<select name="slider_image_int_link" id="slider_image_int_link">
					<option value="">---</option>
					<optgroup label="<?php _e('Pages', 'big_bang'); ?>">
					<?php foreach ( $pages as $page) : ?>
						<option value="<?php echo get_permalink($page->ID); ?>" <?php selected($slider_image_int_link, get_permalink($page->ID)); ?>>
							<?php echo $page->post_title; ?>
						</option>
					<?php endforeach; ?>
					</optgroup>
					<optgroup label="<?php _e('Posts', 'big_bang'); ?>">
					<?php foreach ( $posts as $post) : ?>
						<option value="<?php echo get_permalink($post->ID); ?>" <?php selected($slider_image_int_link, get_permalink($post->ID)); ?>>
							<?php echo $post->post_title; ?>
						</option>
					<?php endforeach; ?>
					</optgroup>
				</select>
			</td>
		</tr>
	</table>

<?php
}




/* !-------- SAVE SLIDER IMAGE META ------------------- */
/*------------------------------------------------------------ */
function bb_save_cpt_slider_image( $post_id ) {

	//Bail if we're doing an auto save
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	
	//if our nonce isn't there, or we can't verify it, bail
	if( !isset($_POST['slider_image_nonce_field']) || !wp_verify_nonce( $_POST['slider_image_nonce_field'], 'slider_image_nonce' ) ) return;
	
	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;
	
	//now we can save the data
	//allowed html tags
	$allowed = array(
		'a' => array(
			'href' => array(),
			'title'=> array()
			)
		);
	//make sure your data is set before trying to save it
	if ( isset($_POST['slider_image_caption']) && $_POST['slider_image_caption'] != '' ) {
		update_post_meta($post_id, 'slider_image_caption', wp_kses( $_POST['slider_image_caption'], $allowed ) );
	}
	else {
		delete_post_meta($post_id, 'slider_image_caption', get_post_meta($post_id, 'slider_image_caption', true));
	}
	
	if ( isset($_POST['slider_image_ext_link']) && $_POST['slider_image_ext_link'] != '' ) {
		update_post_meta($post_id, 'slider_image_ext_link', esc_attr( $_POST['slider_image_ext_link'] ) );
	}
	else {
		delete_post_meta($post_id, 'slider_image_ext_link', get_post_meta($post_id, 'slider_image_ext_link', true));
	}
	
	if ( isset($_POST['slider_image_int_link']) && $_POST['slider_image_int_link'] != '' ) {
		update_post_meta($post_id, 'slider_image_int_link', esc_attr( $_POST['slider_image_int_link'] ) );
	}
	else {
		delete_post_meta($post_id, 'slider_image_int_link', get_post_meta($post_id, 'slider_image_int_link', true));
	}
}

add_action('save_post', 'bb_save_cpt_slider_image');




/* !-------- ADMIN COLUMNS FOR SLIDER IMAGES ------------------- */
/*------------------------------------------------------------ */
/**
 * Add Post Thumbnail Size for the Preview in the Backend
 */
function bb_cpt_slider_image_col_size() {
	add_image_size('admin_col', 100, 100, true);
}

add_action('after_setup_theme', 'bb_cpt_slider_image_col_size');



/**
 * Call the filter/action to manipulate the Admin Columns
 */
function bb_cpt_slider_image_admin_cols() {
	add_filter( 'manage_edit-slider_image_columns', 'bb_cpt_slider_image_columns_filter', 10, 1);
	add_action( 'manage_slider_image_posts_custom_column', 'bb_cpt_slider_image_columns_action', 10, 1);
}

add_action('admin_init', 'bb_cpt_slider_image_admin_cols');



/**
 * Manipulate the Array for the Columns and add our Meta Data as Columns
 */
function bb_cpt_slider_image_columns_filter( $columns ) {
	$columns_thumbnail = array( 'thumbnail' => __('Thumbnail', 'big_bang') );
	$columns_caption = array( 'caption' => __('Caption', 'big_bang' ) );
	$columns_extlink = array( 'ext_link' => __('External Link', 'big_bang' ) );
	$columns_intlink = array( 'int_link' => __('Internal Link', 'big_bang' ) );
	$columns = array_slice( $columns, 0, 1, true) + $columns_thumbnail + array_slice( $columns, 1, 1, true) + $columns_caption + $columns_extlink + $columns_intlink + array_slice( $columns, 2, NULL, true);
	return $columns;
}



/**
 * Fill the columns with content
 */
function bb_cpt_slider_image_columns_action( $column ) {
	global $post;
	$meta = get_post_custom( $post->ID );
	if ( $intlink_id = url_to_postid($meta['slider_image_int_link'][0]) ) {
		$title = get_the_title($intlink_id);
	}
	else {
		$title = '-';
	}
	switch ( $column ) {
		case 'thumbnail':
			echo get_the_post_thumbnail( $post->ID, 'admin_col' );
			break;
		case 'caption':
			echo $meta['slider_image_caption'][0];
			break;
		case 'ext_link':
			echo $meta['slider_image_ext_link'][0];
			break;
		case 'int_link':
			echo $title;
			break;
	}
}




/* !-------- CPT SLIDER IMAGE TAXONOMY ------------------- */
/*------------------------------------------------------------ */
function bb_cpt_slider_image_cat() {
	$labels = array(
		'name'			=> __('Slider Image Categories', 'big_bang'),
		'singular_name'	=> __('Slider Image Category', 'big_bang'),
		'search_items'	=> __('Search Slider Image Categories', 'big_bang'),
		'all_items'		=> __('Categories', 'big_bang'),
	);
	
	register_taxonomy('slider_image_category', array('slider_image'),
		array(
			'labels'		=> $labels,
			'hierarchical'	=> true,
			'show_ui'		=> true,
			'query_var'		=> true,
			'rewrite'		=> array( 'slug' => 'slider_image')
		)
	);
}

add_action('init', 'bb_cpt_slider_image_cat');




/* !-------- CPT SLIDER IMAGE TAXONOMY ADMIN PAGE FILTER ------------------- */
/*------------------------------------------------------------ */
function bb_filter_manage_posts() {
	global $typenow;
	$taxonomy = $typenow.'_category';
	if( $typenow != "page" && $typenow != "post" ){
		$filters = array($taxonomy);
		foreach ($filters as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
			echo "<option value=''>".__('Show all', 'big_bang')." $tax_name</option>";
			foreach ($terms as $term) { echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; }
			echo "</select>";
		}
	}
}

add_action( 'restrict_manage_posts', 'bb_filter_manage_posts' );




//End of file custom_post_type_slider_image.php