<?php

/* !-------- CPT SLIDER IMAGE TAXONOMY ADMIN PAGE FILTER ------------------- */
/*------------------------------------------------------------ */
function bb_filter_manage_posts() {
	global $typenow;
	$taxonomy = $typenow.'_category';
	if( $typenow == "slider_image" ){
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

// //End of file cpts_general_functions.php