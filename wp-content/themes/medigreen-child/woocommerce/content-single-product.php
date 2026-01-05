<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
	echo get_the_password_form();
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

	<?php
	woocommerce_show_product_images();
	?>

	<div class="summary entry-summary">
		<?php
		// Categories before title
		$categories = get_the_terms($product->get_id(), 'product_cat');
		if (!empty($categories) && !is_wp_error($categories)) {
			$cat_links = array();
			foreach ($categories as $category) {
				$cat_links[] = '<a href="' . get_term_link($category) . '">' . esc_html($category->name) . '</a>';
			}
			echo '<div class="product-categories-top">' . implode(', ', $cat_links) . '</div>';
		}

		// Title
		woocommerce_template_single_title();

		// Stock status + Rating wrapper
		$rating = $product->get_average_rating();
		$review_count = $product->get_review_count();
		$rating_width = ($rating / 5) * 100;
		?>
		<div class="stock-rating-wrapper">
			<?php if ($product->is_in_stock()) : ?>
				<div class="stock-status in-stock">In stock</div>
			<?php else : ?>
				<div class="stock-status out-of-stock">Out of stock</div>
			<?php endif; ?>
			<div class="product-rating-inline">
				<div class="star-rating" role="img" aria-label="<?php echo esc_attr(sprintf(__('Rated %s out of 5', 'woocommerce'), $rating)); ?>">
					<span style="width:<?php echo esc_attr($rating_width); ?>%"></span>
				</div>
				<?php if ($review_count > 0) : ?>
					<span class="rating-count">(<?php echo $review_count; ?>)</span>
				<?php endif; ?>
			</div>
		</div>

		<div class="product-price-wrapper">

			<?php // Price
			echo '<div class="variation-price-display variation-price-display-main">';
			if ($product->is_type('variable')) {
				$prices = $product->get_variation_prices(true);
				if (!empty($prices['price'])) {
					$min_price = current($prices['price']);
					$max_price = end($prices['price']);
					$min_reg_price = current($prices['regular_price']);

					if ($min_price !== $max_price) {
						echo '<span class="price">' . wc_price($min_price, array('decimals' => 0)) . ' – ' . wc_price($max_price, array('decimals' => 0)) . '</span>';
					} elseif ($product->is_on_sale() && $min_reg_price !== $min_price) {
						echo '<span class="price"><del>' . wc_price($min_reg_price, array('decimals' => 0)) . '</del> <ins>' . wc_price($min_price, array('decimals' => 0)) . '</ins></span>';
					} else {
						echo '<span class="price">' . wc_price($min_price, array('decimals' => 0)) . '</span>';
					}
				}
			} else {
				if ($product->is_on_sale()) {
					echo '<span class="price"><del>' . wc_price($product->get_regular_price(), array('decimals' => 0)) . '</del> <ins>' . wc_price($product->get_sale_price(), array('decimals' => 0)) . '</ins></span>';
				} else {
					echo '<span class="price">' . wc_price($product->get_price(), array('decimals' => 0)) . '</span>';
				}
			}
			echo '</div>';

			// CBD and THC
			$cbd_attr = $product->get_attribute('pa_cbd');
			$thc_pct_attr = $product->get_attribute('pa_thcpercentage');
			if ($cbd_attr || $thc_pct_attr) {
				echo '<div class="product-attributs-list">';
				if ($thc_pct_attr) {
					echo '<span><b>THC:</b> ' . esc_html($thc_pct_attr) . '</span>';
				}
				if ($cbd_attr) {
					echo '<span><b>CBD:</b> ' . esc_html($cbd_attr) . '</span>';
				}
				echo '</div>';
			} ?>

		</div>

		<?php
		// Flavors (linked products)
		$taxonomy = 'pa_flavors';
		$terms = wc_get_product_terms($product->get_id(), $taxonomy, array('fields' => 'all'));
		if (!empty($terms)) {
			$term_ids = wp_list_pluck($terms, 'term_id');
			$term_names = wp_list_pluck($terms, 'name');
			$flavor_title = implode(', ', $term_names);

			$args = array(
				'post_type' => 'product',
				'posts_per_page' => 10,
				'post__not_in' => array($product->get_id()),
				'tax_query' => array(
					array(
						'taxonomy' => $taxonomy,
						'field' => 'term_id',
						'terms' => $term_ids,
					),
				),
			);

			$related_products = new WP_Query($args);

			echo '<div class="linked-products flavors-products-list">';
			echo '<div><span style="font-weight: 600;">Flower:</span> "' . esc_html($flavor_title) . '"</div>';
			echo '<ul class="linked-products-list">';

			// Current product
			$image = $product->get_image('thumbnail');
			$title = $product->get_name();
			echo '<li class="linked-product-item current-flavor">';
			echo '<span title="' . esc_attr($title) . '">' . $image . '</span>';
			echo '</li>';

			// Related products
			while ($related_products->have_posts()) {
				$related_products->the_post();
				$related_product = wc_get_product(get_the_ID());
				$image = $related_product->get_image('thumbnail');
				$title = $related_product->get_name();
				$link = get_permalink();

				echo '<li class="linked-product-item">';
				echo '<a href="' . esc_url($link) . '" title="' . esc_attr($title) . '">' . $image . '</a>';
				echo '</li>';
			}
			wp_reset_postdata();

			echo '</ul>';
			echo '</div>';
		}

		// Add to cart
		woocommerce_template_single_add_to_cart();

		// Short description (excerpt)
		woocommerce_template_single_excerpt();

		// Free delivery info
		echo '<div class="product-delivery-info">We deliver ' . esc_html($product->get_name()) . ' and other weed / stuff to all the major cities <span>across the UK, including London, Birmingham, Leeds, Glasgow, Sheffield, Manchester, Edinburgh, Liverpool, and many more!</span></div>';

		// Product specs (THC, Type, Effects, scales)
		$thc = get_the_terms($product->get_id(), 'pa_thc');
		$types = get_the_terms($product->get_id(), 'pa_types');
		$effects = get_the_terms($product->get_id(), 'pa_effects');
		$cbd = $product->get_attribute('pa_cbd');
		$thcpercentage = $product->get_attribute('pa_thcpercentage');

		if ($thc || $types || $effects || $cbd || $thcpercentage) : ?>
			<div class="product-specs">
				<ul>
					<?php if ($thc && !is_wp_error($thc)) : ?>
						<li>
							<span><b>THC: </b>
								<?php foreach ($thc as $i => $term) : ?>
									<a href="/shop/swoof/thc-<?php echo esc_attr($term->slug); ?>/"><?php echo esc_html($term->name); ?></a><?php echo $i < count($thc) - 1 ? ', ' : ''; ?>
								<?php endforeach; ?>
							</span>
						</li>
					<?php endif; ?>
					<?php if ($types && !is_wp_error($types)) : ?>
						<li>
							<span><b>Type: </b>
								<?php foreach ($types as $i => $term) : ?>
									<a href="/shop/swoof/types-<?php echo esc_attr($term->slug); ?>/"><?php echo esc_html($term->name); ?></a><?php echo $i < count($types) - 1 ? ', ' : ''; ?>
								<?php endforeach; ?>
							</span>
						</li>
					<?php endif; ?>
					<?php if ($effects && !is_wp_error($effects)) : ?>
						<li>
							<span><b>Effects: </b>
								<?php foreach ($effects as $i => $term) : ?>
									<a href="/shop/swoof/effects-<?php echo esc_attr($term->slug); ?>/"><?php echo esc_html($term->name); ?></a><?php echo $i < count($effects) - 1 ? ', ' : ''; ?>
								<?php endforeach; ?>
							</span>
						</li>
					<?php endif; ?>
				</ul>

				<div class="product-scale">
					<?php if ($cbd) : ?>
						<div class="product-scale__item">
							<div>
								<span>Calming</span>
								<span>Energizing</span>
							</div>
							<div>
								<span style="width: <?php echo esc_attr($cbd); ?>;"></span>
							</div>
						</div>
					<?php endif; ?>
					<?php if ($thcpercentage) : ?>
						<div class="product-scale__item">
							<div>
								<span>Low THC</span>
								<span>High THC</span>
							</div>
							<div>
								<?php
								$thcp = (float)str_replace('%', '', $thcpercentage) * 4;
								$thcp = $thcp > 100 ? 100 : $thcp;
								?>
								<span style="width: <?php echo esc_attr($thcp . '%'); ?>"></span>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif;

		// Meta (SKU, categories, tags)
		woocommerce_template_single_meta();

		// Sharing
		woocommerce_template_single_sharing();

		// Structured data
		if (class_exists('WC_Structured_Data')) {
			$structured_data = WC()->structured_data;
			if ($structured_data) {
				$structured_data->generate_product_data();
			}
		}

		// Categories at the end
		if (!empty($categories)) {
			echo '<div class="product-categories"><span class="product-categories-title">Category:</span> ';
			$cat_count = count($categories);
			$i = 0;
			foreach ($categories as $category) {
				echo '<a href="' . get_term_link($category) . '">' . esc_html($category->name) . '</a>';
				if (++$i < $cat_count) {
					echo ', ';
				}
			}
			echo '</div>';
		}
		?>
	</div>

	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action('woocommerce_after_single_product_summary');
	?>
</div>

<?php do_action('woocommerce_after_single_product'); ?>
