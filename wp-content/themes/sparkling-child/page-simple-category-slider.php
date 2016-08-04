<?php
/**
 * Template Name: Simple Category with Slider
 *
 * This is the template that displays full width page without sidebar
 *
 * @package sparkling
 */

get_header(); ?>

	<div id="primary" class="content-area ourservice-content">

		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( get_theme_mod( 'sparkling_page_comments', 1 ) == 1 ) :
					if ( comments_open() || '0' != get_comments_number() ) :
						comments_template();
					endif;
				endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar("npfcategory"); ?>


<?php

$page_id = get_page_id('our-services');

if(is_child($page_id)) {
	if ( is_child($page_id) ) {
		//Talking Guy Widget Here
		if (function_exists('dynamic_sidebar') && dynamic_sidebar('Talking Guy')) : else :
			// fallback here
		endif;
	}
}
?>

<?php get_footer(); ?>

<script>
	$(document).ready(function() {
		$("header.entry-header.page-header" ).remove();
//		$("h1.entry-title").addClass('hidden');
		$(".container.main-content-area").addClass('zeromp');

	});
</script>