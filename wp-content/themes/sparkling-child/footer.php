<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package sparkling
 */
?>

	<div id="footer-area">
		<div class="container footer-inner">
			<div class="row">
				<?php get_sidebar( 'footer' ); ?>
			</div>
		</div>

		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="site-info container">
				<div class="row">
					<?php if( of_get_option('footer_social') ) sparkling_social_icons(); ?>
					<nav role="navigation" class="col-md-6">
						<?php sparkling_footer_links(); ?>
					</nav>
					<div class="copyright col-md-12">
						<?php //echo of_get_option( 'custom_footer_text', 'sparkling' ); ?>
						<?php //sparkling_footer_info(); ?>
						&copy; 2016 <span class="copywhite">NATIONAL PRODUCTS FULFILMENT</span> ALL RIGHTS RESERVED
					</div>
				</div>
			</div><!-- .site-info -->
			<div class="scroll-to-top"><i class="fa fa-angle-up"></i></div><!-- .scroll-to-top -->
		</footer><!-- #colophon -->
	</div>
</div><!-- #page -->

<script>
	$(function() {
		var vidLink;
		$('.video_popup').on('click', function() {
			vidLink = $(this).attr('href');
			//console.log(vidLink);
			$('.pop-container').find('iframe').attr('src', vidLink);
			$('.trans-bg').fadeIn();

			return false;
		});
	});
	$('.close-pop').on('click', function() {
		$('.trans-bg').fadeOut();
		$('.pop-container').find('iframe').attr('src', '');
	});
</script>

<div class="transbg12">
	<div class="vid-container12">
		<a href="javascript:;"><span class="close-btn1">x close</span></a>
		<iframe style="background:#000;" id="video1" width="750" height="422" src="" frameborder="0" allowfullscreen></iframe>
	</div>
</div>

<!-- Pop-up -->
<div class="trans-bg">
	<div class="pop-container">
		<div class="close-pop">x</div>
		<iframe></iframe>
	</div>
</div>

<script>
	$(function() {
		var vidLink;
		$('.video_popup').on('click', function() {
			vidLink = $(this).attr('href');
			console.log(vidLink);
			$('.pop-container').find('iframe').attr('src', vidLink);
			$('.trans-bg').fadeIn();

			return false;
		});
	});
	$('.close-pop').on('click', function() {
		$('.trans-bg').fadeOut();
		$('.pop-container').find('iframe').attr('src', '');
	});
</script>

<div class="transbg12">
	<div class="vid-container12">
		<a href="javascript:;"><span class="close-btn1">x close</span></a>
		<iframe style="background:#000;" id="video1" width="750" height="422" src="" frameborder="0" allowfullscreen></iframe>
	</div>
</div>

<script>
	$(function() {
		$('.vidbtn2').on('click', function() {
			$('.transbg12').fadeIn();
			$('#video1').attr('src', '//www.youtube.com/embed/-R9AMhETTIk?enablejsapi&rel=0&controls=0&showinfo=0&modestbranding=0&autoplay=1');

			return false;
		});


		$('.close-btn1').on('click', function() {
			$('.transbg12').fadeOut();
			$('#video1').attr('src', '');
		})
	})

</script>

<?php wp_footer(); ?>

</body>
</html>