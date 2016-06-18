<?php get_header();?>

<div id="content" class="site-content peche">


	<?php
	/* Content Top Widgets */
	?>
	<section class="providers" id="providers">
		<div class="container">

			<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Content Top Widgets')) : else : ?>
			<?php endif; ?>

		</div>
	</section>

	<section class="check-plans-and-pricing" id="check-plans-and-pricing">
		<div class="container">
			<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Check plans and Pricing')) : else : ?>
			<?php endif; ?>
		</div>
	</section>

	<section class="content-shiping" id="content-shiping">
		<div class="container">
			<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Content Shiping')) : else : ?>
			<?php endif; ?>
		</div>
	</section>

	<section class="ourservices" id="ourservices">
		<div class="container">
			<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Content Our Services')) : else : ?>
			<?php endif; ?>
		</div>
	</section>

	<section class="get-started-today-bar" id="get-started-today-bar">
		<div class="container">
			<div class="row">
				<div class="col-lg-5">
					<p>We’re dedicated to offering the fastest <br>and most accurate services.</p>
				</div>
				<div class="col-lg-2">
					<div class="">
						<img src="../wp-content/uploads/2016/04/ctalogo.png">
					</div>
				</div>
				<div class="col-lg-5">
					<p><a class="btn btn-lg btn-success" href="/pricing" role="button">GET STARTED TODAY!</a></p>
				</div>
			</div>
		</div>
	</section>

	<!--cambiar por our clients-->
	<section class="our-team" id="team">
		<div class="container">
			<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Content Our Clients')) : else : ?>
			<?php endif; ?>
		</div>
	</section>

	<section class="testimonials-video" id="testimonials-video">
		<div class="container">
			<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Content Testimonials Video')) : else : ?>
			<?php endif; ?>
		</div>
	</section>

<!--	<div class="round-button">-->
<!--		<div class="round-button-circle">-->
<!--			<a href="//www.youtube.com/embed/l5gEXP-WbXY?enablejsapi&rel=0&controls=0&showinfo=0&modestbranding=0&autoplay=1" class="round-button">-->
<!--				<img class="" src="http://www.npfulfilment.com.au/wp-content/uploads/2014/06/testi1_01.png" alt="testi1" width="230" height="160"  />-->
<!--			</a>-->
<!--		</div>-->
<!--	</div>-->

</div>

<?php get_footer(); ?>
