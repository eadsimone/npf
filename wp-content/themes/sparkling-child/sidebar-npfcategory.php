<?php
/**
 * The Sidebar widget area for static frontpage.
 *
 * @package sparkling
 */
?>
</div>
<div id="secondary" class="widget-area col-sm-12 col-md-4" role="complementary">
		<div id="ourservicemenu">
			<div class="header">
				<p>Our Services</p>
			</div>
			<div class="list-group panel">
				<ul>
					<?php
					$numberofparent = 13;
					echo wp_list_pages(array(
						'title_li'    => '',
						'child_of'    => $numberofparent,
						'sort_column' => 'menu_order',
						//	'show_date'   => 'modified',
						//	'date_format' => $date_format
					)); ?>
				</ul>
			</div>
		</div>
</div><!-- #secondary -->
