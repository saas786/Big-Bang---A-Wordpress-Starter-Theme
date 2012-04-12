<!DOCTYPE html>

<!-- Add classes for each IE to the HTML Tag -->
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?>> <!--<![endif]-->
	<head>
		<meta charset="UTF-8"/>
		<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
		<meta name="description" content="">
		<meta name="author" content="">
		
		<!-- Mobile Specific Metas
	  ================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		
		<!-- Default Stylesheets
	  ================================================== -->
		<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/assets/css/base.css">
		<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/assets/css/skeleton.css">
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>"/>
		
		<!-- Favicons
		================================================== -->
		<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/assets/images/favicon.ico">
		<link rel="apple-touch-icon" href="<?php bloginfo('template_url'); ?>/assets/images/apple-touch-icon.png">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php bloginfo('template_url'); ?>/assets/images/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo('template_url'); ?>/assets/images/apple-touch-icon-114x114.png">
		
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		
		<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
		
		<!-- WP_HEAD -->
		<?php wp_head(); ?>
		
	</head>
	
	<body <?php body_class(); ?>>
	
		<div id="header">
			<?php bb_main_nav(); ?>
		</div>
