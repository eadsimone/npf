<?php get_header(); 

?>

<div id="content" class="site-content">


	<?php
	/* Content Top Widgets */

	 if (function_exists('dynamic_sidebar') && dynamic_sidebar('Content Top Widgets')) : else : ?>
	 <?php endif; ?>


	<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Content Our Clients')) : else : ?>
	<?php endif; ?>


</div>
	<?php

get_footer(); ?>
