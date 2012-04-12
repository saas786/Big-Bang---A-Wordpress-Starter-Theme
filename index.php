<?php get_header(); ?>
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<?php  // put loop info here! ?>
		<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
		<?php comments_number(); ?>
		<?php bb_excerpt('bb_news'); ?>
	<?php endwhile; ?>
	<?php bb_blog_nav('pagination'); ?>
<?php get_sidebar();?>
<?php get_footer(); ?>
