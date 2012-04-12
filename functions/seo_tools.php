<?php
/* !---------------- SEO METABOX ------------------- */
/*------------------------------------------------------------ *\
\*------------------------------------------------------------ */
function seo_meta_box() {
	if ( function_exists('add_meta_box') ) {
		add_meta_box('seo_settings', 'Suchmaschinen Optimierung', 'seo_meta_box_content', 'post', 'normal');
		add_meta_box('seo_settings', 'Suchmaschinen Optimierung', 'seo_meta_box_content', 'page', 'normal');
	}
}

add_action('add_meta_boxes', 'seo_meta_box');




function seo_meta_box_content( $post ) {
	$seo_vals = get_post_custom( $post->ID );
	$seo_custom_title = isset( $seo_vals['seo_custom_title'] ) ? esc_attr( $seo_vals['seo_custom_title'][0] ) : '';
	$seo_keywords = isset( $seo_vals['seo_keywords'] ) ? esc_attr( $seo_vals['seo_keywords'][0] ) : '';
	$seo_description = isset( $seo_vals['seo_description'] ) ? esc_attr( $seo_vals['seo_description'][0] ) : '';
		
	wp_nonce_field('seo_tools_nonce', 'seo_tools_nonce_name'); ?>
	
	<table class="meta-form-table">
		<tr>
			<td class="left">
				<label for="seo_custom_title"><?php _e('Custom Title', 'big_bang'); ?></label>
			</td>
			<td class="right">
				<input type="text" name="seo_custom_title" id="seo_custom_title" value="<?php echo $seo_custom_title; ?>" />
			</td>
		</tr>
		<tr>
			<td class="left">
				<label for="seo_keywords"><?php _e('Keywords', 'big_bang'); ?></label>
			</td>
			<td class="right">
				<input type="text" name="seo_keywords" id="seo_keywords" value="<?php echo $seo_keywords; ?>" />
			</td>
		</tr>
		<tr>
			<td class="left">
				<label for="seo_description"><?php _e('Description', 'big_bang'); ?></label>
			</td>
			<td class="right">
				<textarea name="seo_description" id="seo_description"><?php echo $seo_description; ?></textarea>
			</td>
		</tr>
	</table>
	
	<?php
}




/* !---------------- SEO METABOX SAVING ------------------- */
/*------------------------------------------------------------ *\
\*------------------------------------------------------------ */
function seo_meta_box_save( $post_id ) {
//Bail if we're doing an auto save
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
//if our nonce isn't there, or we can't verify it, bail
	if ( !isset($_POST['seo_tools_nonce_name'] ) || !wp_verify_nonce( $_POST['seo_tools_nonce_name'], 'seo_tools_nonce' ) ) return;
//if our current user can't edit this post, bail
	if ( !current_user_can( 'edit_post' ) ) return;
	
	
	if ( isset( $_POST['seo_custom_title'] ) && $_POST['seo_custom_title'] != '' ) {
		update_post_meta( $post_id, 'seo_custom_title', esc_attr( $_POST['seo_custom_title'] ) );
	}
	else {
		delete_post_meta( $post_id, 'seo_custom_title', get_post_meta( $post_id, 'seo_custom_title', true ) );
	}
	
	
	if ( isset($_POST['seo_keywords'] ) && $_POST['seo_keywords'] != '' ) {
		update_post_meta( $post_id, 'seo_keywords', esc_attr( $_POST['seo_keywords'] ) );
	}
	else {
		delete_post_meta( $post_id, 'seo_keywords', get_post_meta( $post_id, 'seo_keywords', true ) );
	}
	
	
	if ( isset( $_POST['seo_description'] ) && $_POST['seo_description'] != '' ) {
		update_post_meta( $post_id, 'seo_description', esc_attr( $_POST['seo_description'] ) );
	}
	else {
		delete_post_meta( $post_id, 'seo_description', get_post_meta( $post_id, 'seo_description', true ) );
	}
	
}

add_action('save_post', 'seo_meta_box_save');




/* !---------------- ADDING METATAGS TO WP_HEAD ------------------- */
/*------------------------------------------------------------ *\
\*------------------------------------------------------------ */
function seo_meta_values() {
	global $post;
	
	$std_keywords = '<meta name="keywords" content="Telekomcenter, Telekommunikation, Handy, Freischalten, Telekommunikationslösungen, A1, T-Mobile, Drei, Orange, Bob, Telering, Yesss, Entsperren" />';
	$std_description = '<meta name="description" content="Telekomcenter - Ihr Partner für Telekommunikationlösungen. Handys, Anmeldungen, Ummeldungen, Freischaltungen" />';
	
	if ( is_single() || is_page() ) {
		$seo_keywords = get_post_meta( $post->ID, 'seo_keywords', true );
		$seo_description = get_post_meta( $post->ID, 'seo_description', true );
		
		if ( strlen($seo_keywords) ) {
			echo '<meta name="keywords" content="'. trim(wptexturize(strip_tags(stripslashes($seo_keywords)))) .'" />'."\n";
		}
		else {
			echo $std_keywords."\n";
		}
		
		if ( strlen($seo_description) ) {
			echo '<meta name="description" content="'. trim(wptexturize(strip_tags(stripslashes($seo_description)))) .'" />'."\n";
		}
		else {
			echo $std_description."\n";
		}
	}
	else {
		echo $std_keywords."\n";
		echo $std_description."\n";
	}
}

add_action('wp_head', 'seo_meta_values');

?>