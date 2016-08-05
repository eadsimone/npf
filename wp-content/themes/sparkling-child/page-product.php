<?php
/* Template Name: Product Page */
?>


<?php get_header(); ?>

<div class="main-content">
	<div class="container clearfix">
		<div class="content">
			<br /><br />
			<!--display page content-->
			<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
			<h1><?php the_title(); ?></h1>
			<hr />
			<?php the_content(); ?>
			<?php endwhile; endif;?>
		</div>
		
		
		
		<div class="sidebar"><br /><br />
			<div class="product-meta">
			
				<a class="product-btn" href="#"><?php the_title(); ?></a>
				
				<div class="product-meta-container">
					<table width="100%">
						<tr><td>Price</td><td><?php the_field('npf_price'); ?></td></tr>
					</table>
				</div>
				
				<div class="product-meta-container">
					<h3>Support & Sales</h3>
					<table width="100%">
						<tr><td>Phone</td><td><?php the_field('npf_phone'); ?></td></tr>
						<tr><td>Email</td><td><a href="mailto:<?php the_field('npf_email'); ?>"><?php the_field('npf_email'); ?></a></td></tr>
						<tr><td>Website</td><td><a href="http://<?php the_field('npf_website'); ?>" target="_blank"><?php the_field('npf_website'); ?></a></td></tr>
					</table>
				</div>
				
				<?php if(get_field('npf_sreenshots')) { ?>
				<div class="product-meta-container">
					<h3>Screenshots</h3>
					<img src="<?php the_field('npf_sreenshots'); ?>" />
				</div>
				<?php } ?>
				
				
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>