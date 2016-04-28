<?php
/*
	Template Name: Homepage
*/
?>

<?php get_header(); ?>

<div class="main-content">
	<div class="container">
		<div class="content-top clearfix center-text peche">
			<!-- Content Top Widget here-->
			<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Content Top Widgets')) : else : ?>
			<?php endif; ?>
			<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Content Our Clients')) : else : ?>
			<?php endif; ?>
		</div>
		
		<hr />
		
		<div class="content-mid clearfix">

			<div class="grid2">
				<div class="welcome-message gray-bg">
					<h2><span class="pink">Welcome to</span> National Products Fulfilment </h2>
					<!-- Content Top Widget here-->
					<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Welcome Message Widget')) : else : ?>
					<?php endif; ?>
				</div>
			</div>
			
			<div class="grid2">
				<div class="vid-header">
					<h3>Find out how Steve finds Fulfilment</h3>
				</div>
				
				<div class="vid-container">
					<!--Widget here-->
					<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Video 1 Widget')) : else : ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
		
		<hr />
		
		<div class="content-bottom clearfix">
			<div class="npf-services">
			<?php 
				$catquery = new WP_Query( 'category_name=services&orderby=date&showposts=9999&order=ASC' );
				while($catquery->have_posts()) : $catquery->the_post(); 
			?>
				<div class="npf-service">
					<?php the_post_thumbnail('thumbnail'); ?>
					<div class="npf-service-details">
						<div class="npf-service-header">
							<h4><?php the_title(); ?></h4>
							<span><i><?php the_excerpt(); ?></i></span>
						</div>
						<div class="npf-service-body">
							<p> <?php the_content(); ?></p>	
							<?php $link = get_post_custom_values('link'); ?>
							<a href="<?php echo $link[0]; ?>">Learn More</a>
						</div>
							
					</div>
				</div>
				
			<?php endwhile; ?>
			</div>
		</div>
	</div>
</div>

<div class="testimonial-title">
	<div class="container">
		<h2>Testimonials</h2>
		<hr />
	</div>
</div>

<div class="home-testimonial">
	<div class="container">
		<div class="clearfix">
			<div class="tes-left">
				<div style="border: 5px solid #FFFFFF; box-shadow: 0 0 1px rgba(0, 0, 0, 0.3); height: 190px; width: 250px;">
					<div id="slides">
						<!--Widget here-->
						<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Testimonial Videos')) : else : ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			
			<div class="tes-right">
				<!--Widget here-->
				<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('testimonial')) : else : ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<script>
$(function() {
	$('#slides').slidesjs({
         width: 250,
        height: 190,
		navigation: {
			active: true
		},
		
		play: {
			active: false,
			auto: false
		},
		
		pagination: {
			active: false
		}
	});
});
</script>


<?php get_footer(); ?>