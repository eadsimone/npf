<?php
/**
 * The Sidebar widget area for static frontpage.
 *
 * @package sparkling
 */
?>
</div>
<div id="secondary" class="widget-area col-sm-12 col-md-4" role="complementary">
	<div class="product-meta">
		<div class="product-meta-container">
			<table width="100%">
				<tr>
					<td>Price</td>
					<td><?php the_field('npf_price'); ?></td>
				</tr>
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
</div><!-- #secondary