<?php
/* In the functions.php is only the most used Wordpress Stuff.
 * F.e. Menus, jQuery, Modernizr, CSS body classes, Widgets.
 * The other stuff is in seperate files.
 * Use it or not - it's your decision ;-)
 */



/* !-------- SET SOME CONSTANTS ------------------- */
/*------------------------------------------------------------ */
/* Set the constants based on whether the Theme is a parent theme or child theme */

if ( STYLESHEETPATH == TEMPLATEPATH ) {
	define('BB_ASSETS_URL', TEMPLATEPATH . '/assets/');
	define('BB_ASSETS_DIRECTORY', get_bloginfo('template_directory') . '/assets/');
} else {
	define('BB_ASSETS_URL', STYLESHEETPATH . '/assets/');
	define('BB_ASSETS_DIRECTORY', get_bloginfo('stylesheet_directory') . '/assets/');
}




/* !-------- TRANSLATION READY ------------------- */
/*------------------------------------------------------------ */
load_theme_textdomain( 'big_bang', TEMPLATEPATH . '/languages' ); // 'big_bang' is the domain, if you replace this with your themename, you have to replace this in the whole theme http://codex.wordpress.org/Function_Reference/load_theme_textdomain

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );
// For more information read http://codex.wordpress.org/Translating_WordPress
// Codestyling Localization is a great Wordpress Plugin for making a translation of your Theme/Plugin
// http://wordpress.org/extend/plugins/codestyling-localization/




/* !-------- REMOVE UNNECESSARY HEADLINKS ------------------- */
/*------------------------------------------------------------ */
function bb_remove_headlinks() {
	// remove simple discovery link
	remove_action('wp_head', 'rsd_link');
	// remove windows live writer link
	remove_action('wp_head', 'wlwmanifest_link');
	// remove the version number
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'start_post_rel_link');
	remove_action('wp_head', 'index_rel_link');
}

add_action( 'init', 'bb_remove_headlinks' );




/* !-------- ADD SCRIPTS ------------------- */
/*------------------------------------------------------------ */
function bb_add_scripts() {
	//add jquery served with wordpress
	wp_enqueue_script( 'jquery' );
	// wanna have jquery from Google? Remove the slashes in front of the next 3 lines
	//wp_deregister_script('jquery');
	//wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
	//wp_enqueue_script('jquery');
	
	//add modernizr.js
	wp_register_script('modernizr', BB_ASSETS_DIRECTORY.'js/modernizr-2.5.2.min.js', '', '1.0');
	wp_enqueue_script('modernizr');
}

add_action('wp_enqueue_scripts', 'bb_add_scripts');




/* !-------- BODY-TAG BROWSER CLASSES ------------------- */
/*------------------------------------------------------------ */
function bb_browser_class($classes) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
	
	if($is_lynx) $classes[]       = 'browser-lynx';
	elseif($is_gecko) $classes[]  = 'browser-gecko';
	elseif($is_opera) $classes[]  = 'browser-opera';
	elseif($is_NS4) $classes[]    = 'browser-ns4';
	elseif($is_safari) $classes[] = 'browser-safari';
	elseif($is_chrome) $classes[] = 'browser-chrome';
	elseif($is_IE) $classes[]     = 'browser-ie';
	else $classes[]               = '';
	if($is_iphone) $classes[]     = 'browser-iphone';
	return $classes;
}

add_filter('body_class', 'bb_browser_class');




/* !-------- MENUS ------------------- */
/*------------------------------------------------------------ */
function bb_menus() {
	if(function_exists( 'register_nav_menus' )) {
		register_nav_menus(
			array(
				'main_nav'		=> __('Main Menu', 'big_bang'),
				'footer_nav'	=> __('Footer Menu', 'big_bang')
			)
		);
	}
}

add_action('init', 'bb_menus');


/**
 * bb_main_nav function
 * add the main navigation, you can set your own CSS-classes
 * 
 * @access public
 * @param string $menu_class (default: '')
 * @return void
 */
function bb_main_nav($menu_class = '') {
	if ( strlen($menu_class) > 0 ) { $menu_class = ' '.$menu_class; }
	wp_nav_menu(
		array(
			'menu'				=> 'main_nav',
			'theme_location'	=> 'main_nav',
			'container'			=> false,
			'menu_class'		=> 'menu main-nav'.$menu_class,
			'fallback_cb'		=> 'bb_main_nav_fallback'
		)
	);
}


/**
 * bb_footer_nav function.
 * add the footer navigation, you can set your own CSS-classes
 * 
 * @access public
 * @param string $menu_class (default: '')
 * @return void
 */
function bb_footer_nav($menu_class = '') {
	if ( strlen($menu_class) > 0 ) { $menu_class = ' '.$menu_class; }
	wp_nav_menu(
		array(
			'menu'				=> 'footer_nav',
			'theme_location'	=> 'footer_nav',
			'container'			=> 'false',
			'menu_class'		=> 'menu footer-nav'.$menu_class,
			'fallback_cb'		=> 'bb_footer_nav_fallback'
		)
	);
}


/**
 * bb_main_nav_fallback function
 * adds a fallback for the main Navigation, if no custom menu is set in the backend.
 * 
 * @access public
 * @return void
 */
function bb_main_nav_fallback() {
	wp_page_menu( 'show_home=Home&menu_class=menu' );
}


/**
 * bb_footer_nav_fallback function
 * adds a fallback for the footer Navigation, if no custom menu is set in the backend..
 * 
 * @access public
 * @return void
 */
function bb_footer_nav_fallback() {
	echo '<ul class="menu footer-nav"><li>'. __('Big Bang - A Wordpress Starter Theme', 'big_bang') .'</li></ul>';
}




/* !-------- WIDGETS ------------------- */
/*------------------------------------------------------------ */
function bb_widgets_init() {
	register_sidebar(array(
		'id'			=> 'sidebar1',
		'name'			=> __('Sidebar 1', 'big_bang'),
		'description'	=> __('The first Sidebar', 'big_bang'),
		'before_widget'	=> '<li id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</li>',
		'before_title'	=> '<h4 class="widgettitle">',
		'after_title'	=> '</h4>'
	));
}

add_action('widgets_init', 'bb_widgets_init');




/* !-------- EXCERPT ------------------- */
/*------------------------------------------------------------ */
function bb_news($length) {
    return 15;
}

function bb_teaser($length) {
    return 35;
}

function bb_index($length) {
    return 160;
}

function bb_readmore($more) {
    return '...<a href="'. get_permalink().'">'. __('Read More', 'big_bang') .' ></a>';
}


/**
 * bb_excerpt function
 * show the excerpt with custom length and custom readmore link
 * 
 * @access public
 * @param string $length_callback (default: '')
 * @param string $more_callback (default: '')
 * @return void
 */
function bb_excerpt($length_callback='', $more_callback='') {
	global $post;
	if ( function_exists($length_callback) ) {
		add_filter('excerpt_length', $length_callback);
	}	
	if ( function_exists($more_callback) ) {
		add_filter('excerpt_more', $more_callback);
	}
	$output = get_the_excerpt();
	$output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>'.$output.'</p>';
    echo $output;
}




/* !---------------- PAGE NAVIGATION ------------------- */
/*------------------------------------------------------------ *\
\*------------------------------------------------------------ */
/**
 * Display navigation to next/previous pages when applicable
 */
function bb_blog_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		
		<nav id="<?php echo $nav_id; ?>">
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'big_bang' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'big_bang' ) ); ?></div>
		</nav>
	<?php endif;
}




/* !-------- POST THUMBNAILS ------------------- */
/*------------------------------------------------------------ */
add_theme_support('post-thumbnails');




/* !-------- Custom functions Start ------------------- */
/*------------------------------------------------------------ */

// If you use one of the includes files below, plz
// add the backend stylesheet.
// Just remove the comments in front of the next 5 lines

function bb_add_admin_scripts_styles() {
	wp_register_style('admin_css', get_bloginfo('template_url').'/admin/css/bb_backend.css', '', '1.0');
	wp_enqueue_style('admin_css');
}
add_action('admin_init', 'bb_add_admin_scripts_styles');


 
/* !-------- SEO TOOLS ------------------- */
/*------------------------------------------------------------ */
include_once('functions/seo_tools.php');




/* !-------- CUSTOM POST TYPES ------------------- */
/*------------------------------------------------------------ */
include_once('functions/custom_post_type_slider_image.php');




?>