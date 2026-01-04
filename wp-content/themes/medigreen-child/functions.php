<?php
/*add_action('wp_enqueue_scripts', 'medigreen_child_enqueue_styles');
function medigreen_child_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'));
    wp_enqueue_script('medigreen-child-script', get_stylesheet_directory_uri() . '/script.js', array('jquery'), '1.0.0', true);
}*/

function my_theme_enqueue_styles()
{

	$parent_style = 'parent-style';

	wp_enqueue_style($parent_style, get_parent_theme_file_uri('style.css'));
	wp_enqueue_style('child-style',
		get_theme_file_uri('style.css'),
		array($parent_style),
		wp_get_theme()->get('Version')
	);
	wp_enqueue_script('medigreen-child-script', get_stylesheet_directory_uri() . '/script.js', array('jquery'), '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

// Enhance bt_bb_single_product - replace div with h2 for title
add_filter('bt_bb_single_product_output', 'enhance_bb_single_product_output', 10, 2);
function enhance_bb_single_product_output($output, $atts) {
	$output = preg_replace(
		'/<div class="bt_bb_single_product_title">(<a [^>]+>[^<]*<\/a>)<\/div>/',
		'<h2 class="bt_bb_single_product_title">$1</h2>',
		$output
	);
	return $output;
}

// Add outofstock class to bt_bb_single_product
add_filter('bt_bb_single_product_class', 'add_outofstock_class_to_single_product', 10, 2);
function add_outofstock_class_to_single_product($class, $atts) {
	$product_id = isset($atts['product_id']) ? intval($atts['product_id']) : 0;
	if ($product_id) {
		$product = wc_get_product($product_id);
		if ($product && !$product->is_in_stock()) {
			$class[] = 'outofstock';
		}
	}
	return $class;
}

// Remove sale badge from everywhere
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);

// Remove default loop price (we display it in content-product.php template)
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

// Change "Add to cart" and "Select options" to "Shop" in catalog
add_filter('woocommerce_product_add_to_cart_text', 'custom_add_to_cart_text', 10, 2);
function custom_add_to_cart_text($text, $product) {
	if (!$product->is_in_stock()) {
		return 'Read More';
	}
	return 'Shop';
}

// Make "Shop" button link to product page instead of adding to cart
add_filter('woocommerce_loop_add_to_cart_link', 'custom_loop_add_to_cart_link', 10, 2);
function custom_loop_add_to_cart_link($link, $product) {
	if ($product->is_in_stock()) {
		return sprintf(
			'<a href="%s" class="button product_type_%s">%s</a>',
			esc_url($product->get_permalink()),
			esc_attr($product->get_type()),
			esc_html('Shop')
		);
	}
	return $link;
}


// Show "From $X" instead of price range for variable products in catalog
// TEMPORARILY DISABLED - testing WOOF issue
// add_filter('woocommerce_variable_price_html', 'show_from_price_for_variable_products', 10, 2);
function show_from_price_for_variable_products($price, $product) {
	// Only apply in shop/archive (not on single product page)
	// Use wp_doing_ajax() to handle WOOF AJAX filtering correctly
	if (is_product() && !wp_doing_ajax()) {
		return $price;
	}

	$prices = $product->get_variation_prices(true);

	if (empty($prices['price'])) {
		return $price;
	}

	$min_price = current($prices['price']);
	$max_price = end($prices['price']);

	// Only change if there's actually a range
	if ($min_price !== $max_price) {
		return '<span class="price-from">From </span>' . wc_price($min_price, array('decimals' => 0));
	}

	return $price;
}

// Mobile class for btContentHolder
add_action('wp_footer', 'add_mobile_content_holder_class');
function add_mobile_content_holder_class()
{
	if (function_exists('is_woocommerce') && is_woocommerce() && wp_is_mobile()) {
		?>
		<script>
        document.querySelector('.btContentHolder')?.classList.add('btContentHolder-mobile');
		</script>
		<?php
	}
}

// Title "On sale"
add_action('wp_footer', 'add_onsale_filter_title');
function add_onsale_filter_title()
{
	if (!is_shop() && !is_product_category() && !is_product_tag()) {
		return;
	}
	$lang = get_locale();

	if (strpos($lang, 'en') !== false) {
		$title_text = 'Products on sale';
	} else {
		$title_text = 'Produtos em promoção';
	}
	?>
	<script>
      jQuery(function ($) {
          var titleText = '<?php echo esc_js($title_text); ?>';

          function addSaleTitle() {
              var $container = $('.woof_checkbox_sales_container');
              if ($container.length && !$container.prev('.woof_onsale_title').length) {
                  $container.before('<h4 class="woof_onsale_title">' + titleText + '</h4>');
              }
          }

          addSaleTitle();
          $(document).on('woof_ajax_done', function () {
              addSaleTitle();
          });
      });
	</script>
	<?php
}


// Secondary menu
add_action('after_setup_theme', 'medigreen_child_register_menus', 99);

function medigreen_child_register_menus()
{
	register_nav_menus(array(
		'secondary' => __('Secondary Menu')
	));
}


/**
 * Post time reading
 */
function get_reading_time($post_id)
{
	$content = get_post_field('post_content', $post_id);
	$content = strip_tags($content);
	$word_count = str_word_count($content);
	$minutes = ceil($word_count / 200);
	return max(1, $minutes);
}


add_filter('bt_bb_latest_posts_output', function ($output, $atts) {
	$output = str_replace(
		['<h5 class="bt_bb_latest_posts_item_title">', '</h5>'],
		['<h3 class="bt_bb_latest_posts_item_title">', '</h3>'],
		$output
	);

	$dom = new DOMDocument();
	libxml_use_internal_errors(true);
	$dom->loadHTML('<?xml encoding="UTF-8">' . $output, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
	libxml_clear_errors();

	$xpath = new DOMXPath($dom);
	$items = $xpath->query("//*[contains(@class, 'bt_bb_latest_posts_item')]");

	foreach ($items as $item) {
		$title_link = $xpath->query(".//h3[contains(@class, 'bt_bb_latest_posts_item_title')]/a", $item)->item(0);
		if (!$title_link) continue;

		$url = $title_link->getAttribute('href');
		$post_id = url_to_postid($url);

		$links = $xpath->query(".//a", $item);
		foreach ($links as $link) {
			$text = $dom->createTextNode($link->textContent);
			$link->parentNode->replaceChild($text, $link);
		}

		if ($post_id) {
			$reading_time = get_reading_time($post_id);
			$meta = $xpath->query(".//*[contains(@class, 'bt_bb_latest_posts_item_meta')]", $item)->item(0);
			if ($meta) {
				$span = $dom->createElement('span', $reading_time . ' min.');
				$span->setAttribute('class', 'bt_bb_reading_time');
				// Ищем дату и вставляем перед ней
				$date_span = $xpath->query(".//*[contains(@class, 'bt_bb_latest_posts_item_date')]", $meta)->item(0);
				if ($date_span) {
					$meta->insertBefore($span, $date_span);
				} else {
					$meta->appendChild($span);
				}
			}
		}

		$wrapper = $dom->createElement('a');
		$wrapper->setAttribute('href', $url);
		$wrapper->setAttribute('class', 'bt_bb_latest_posts_item_link');

		while ($item->firstChild) {
			$wrapper->appendChild($item->firstChild);
		}
		$item->appendChild($wrapper);
	}

	$output = $dom->saveHTML();
	$output = str_replace('<?xml encoding="UTF-8">', '', $output);

	return $output;
}, 10, 2);


// lang switcher
function shortcode_lang_slugs()
{
	if (!function_exists('pll_the_languages')) {
		return '';
	}
	$args = [
		'raw' => 1,
		'hide_if_empty' => 0,
		'hide_if_no_translation' => 0,
	];

	$languages = pll_the_languages($args);
	$links = [];

	if (!empty($languages)) {
		foreach ($languages as $lang) {
			$url = $lang['url'];
			$active_class = $lang['current_lang'] ? 'active-lang' : '';
			$links[] = sprintf(
				'<a href="%s" class="%s">%s</a>',
				esc_url($url),
				esc_attr($active_class),
				esc_html($lang['slug'])
			);
		}
	}
	return '<div class="lang-switcher-slugs">' . implode(' / ', $links) . '</div>';
}

add_shortcode('lang_slugs', 'shortcode_lang_slugs');

// hide sidebare on product page
add_filter('boldthemes_extra_class', function ($extra_class) {
	if (function_exists('is_product') && is_product()) {
		BoldThemesFramework::$has_sidebar = false;
		$extra_class = array_filter($extra_class, function ($class) {
			return strpos($class, 'btWithSidebar') === false;
		});
		$extra_class[] = 'btNoSidebar';
	}
	return $extra_class;
});

// display Flavors on product page
add_action('woocommerce_before_add_to_cart_form', 'display_products_by_flavor');
function display_products_by_flavor()
{
	global $product;

	$taxonomy = 'pa_flavors';
	$terms = wc_get_product_terms($product->get_id(), $taxonomy, array('fields' => 'all'));

	if (empty($terms)) {
		return;
	}
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
	echo '<div> <span style="font-weight: 600;">Flowers:</span> "' . esc_html($flavor_title) . '"</div>';
	echo '<ul class="linked-products-list">';

	$image = $product->get_image('thumbnail');
	$title = $product->get_name();
	$link = get_permalink($product->get_id());

	echo '<li class="linked-product-item current-flavor">';
	echo '<span title="' . esc_attr($title) . '">' . $image . '</span>';
	echo '</li>';

	while ($related_products->have_posts()) {
		$related_products->the_post();
		$related_product = wc_get_product(get_the_ID());

		$image = $related_product->get_image('thumbnail');
		$title = $related_product->get_name();
		$link = get_permalink();

		echo '<li class="linked-product-item">';
		echo '<a href="' . esc_url($link) . '" title="' . esc_attr($title) . '">';
		echo $image;
		echo '</a>';
		echo '</li>';
	}

	wp_reset_postdata();

	echo '</ul>';
	echo '</div>';
}


add_action('woocommerce_single_product_summary', 'custom_stock_status', 6);

function custom_stock_status()
{
	global $product;

	if ($product->is_in_stock()) {
		echo '<div class="stock-status in-stock">In stock</div>';
	} else {
		echo '<div class="stock-status out-of-stock">Out of stock</div>';
	}
}

// Display price after stock status
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
add_action('woocommerce_single_product_summary', 'custom_variation_price_display', 7);

function custom_variation_price_display()
{
	global $product;

	echo '<div class="variation-price-display variation-price-display-main">';

	if ($product->is_type('variable')) {
		// For variable products - get default variation price or range
		$prices = $product->get_variation_prices(true);

		if (!empty($prices['price'])) {
			$min_price = current($prices['price']);
			$max_price = end($prices['price']);
			$min_reg_price = current($prices['regular_price']);
			$max_reg_price = end($prices['regular_price']);

			if ($min_price !== $max_price) {
				echo '<span class="price">' . wc_format_price_range($min_price, $max_price) . '</span>';
			} elseif ($product->is_on_sale() && $min_reg_price !== $min_price) {
				echo '<span class="price"><del>' . wc_price($min_reg_price) . '</del> <ins>' . wc_price($min_price) . '</ins></span>';
			} else {
				echo '<span class="price">' . wc_price($min_price) . '</span>';
			}
		}
	} else {
		// For simple products
		if ($product->is_on_sale()) {
			echo '<span class="price"><del>' . wc_price($product->get_regular_price()) . '</del> <ins>' . wc_price($product->get_sale_price()) . '</ins></span>';
		} else {
			echo '<span class="price">' . wc_price($product->get_price()) . '</span>';
		}
	}

	echo '</div>';
}


// Move categories after add to cart
add_action('woocommerce_after_add_to_cart_form', 'display_product_categories_after_cart', 10);
function display_product_categories_after_cart()
{
	global $product;

	$categories = get_the_terms($product->get_id(), 'product_cat');

	if (!empty($categories)) {
		echo '<div class="product-categories"><span class="product-categories-title">Category:</span> ';
		foreach ($categories as $category) {
			echo '<a href="' . get_term_link($category) . '">' . esc_html($category->name) . '</a>';
			if ($category !== end($categories)) {
				echo ', ';
			}
		}
		echo '</div>';
	}
}


// Add price to variations

// Sort variations by menu_order (drag-and-drop order in admin)
add_filter('woocommerce_variation_prices', 'sort_variation_prices_by_menu_order', 10, 3);
function sort_variation_prices_by_menu_order($prices_array, $product, $for_display)
{
	$variation_ids = $product->get_children();

	// Get variations with their menu_order
	$variations_order = array();
	foreach ($variation_ids as $variation_id) {
		$variation = wc_get_product($variation_id);
		if ($variation) {
			$variations_order[$variation_id] = $variation->get_menu_order();
		}
	}

	// Sort by menu_order
	asort($variations_order);
	$sorted_ids = array_keys($variations_order);

	// Reorder all price arrays
	foreach ($prices_array as $price_type => $prices) {
		$sorted_prices = array();
		foreach ($sorted_ids as $id) {
			if (isset($prices[$id])) {
				$sorted_prices[$id] = $prices[$id];
			}
		}
		$prices_array[$price_type] = $sorted_prices;
	}

	return $prices_array;
}

// Sort available variations by menu_order
add_filter('woocommerce_available_variation', 'add_menu_order_to_variation', 10, 3);
function add_menu_order_to_variation($data, $product, $variation)
{
	$data['menu_order'] = $variation->get_menu_order();
	return $data;
}

// Add variation prices to attribute labels
add_action('wp_footer', 'add_variation_prices_to_labels');
function add_variation_prices_to_labels()
{
	if (!is_product()) return;

	global $product;
	if (!$product || !$product->is_type('variable')) return;
	?>
	<script>
      jQuery(function ($) {
          var $form = $('form.variations_form');
          if (!$form.length) return;

          var variations = $form.data('product_variations');
          if (!variations) return;

          // Sort variations by menu_order
          variations.sort(function (a, b) {
              return (a.menu_order || 0) - (b.menu_order || 0);
          });

          // Build price map: attribute_value => {price, order}
          var priceMap = {};

          variations.forEach(function (variation, index) {
              for (var attr in variation.attributes) {
                  var value = variation.attributes[attr];
                  if (value && !priceMap[value]) {
                      priceMap[value] = {
                          price: variation.display_price,
                          order: variation.menu_order || index
                      };
                  }
              }
          });

          // Reorder swatches by menu_order
          $('.variable-items-wrapper').each(function () {
              var $wrapper = $(this);
              var $items = $wrapper.find('.variable-item');

              $items.sort(function (a, b) {
                  var aVal = $(a).data('value') || $(a).attr('data-value');
                  var bVal = $(b).data('value') || $(b).attr('data-value');
                  var aOrder = priceMap[aVal] ? priceMap[aVal].order : 999;
                  var bOrder = priceMap[bVal] ? priceMap[bVal].order : 999;
                  return aOrder - bOrder;
              });

              $wrapper.append($items);
          });

          // Update swatches/buttons with prices
          $('.variable-items-wrapper .variable-item, .variations .value label, .wcvaswatches label').each(function () {
              var $item = $(this);
              var attrValue = $item.data('value') || $item.attr('data-value') || $item.find('input').val();

              if (attrValue && priceMap[attrValue] !== undefined) {
                  var price = priceMap[attrValue].price;
                  var formattedPrice = '<?php echo html_entity_decode(get_woocommerce_currency_symbol()); ?>' + parseFloat(price).toFixed(2);

                  // Find text element
                  var $text = $item.find('.variable-item-span-text, .variable-item-contents, span:not(.price-label)').first();
                  if (!$text.length) $text = $item;

                  var currentText = $text.text().trim();
                  if (currentText.indexOf('<?php echo get_woocommerce_currency_symbol(); ?>') === -1) {
                      $text.html('<strong>' + formattedPrice + '</strong> - ' + currentText);
                  }
              }
          });
      });
	</script>
	<?php
}

// breadcrumbs
function breadcrumbs()
{
    $site_url = '/';
    $home_name = 'Home';
    $shop_name = 'Catalog';

    if (!is_front_page()) {
        if (!is_home()) {
            echo '<div itemscope="" itemtype="http://schema.org/BreadcrumbList" class="breadcrumbs"><span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . $site_url . '">' . $home_name . '</a><meta itemprop="position" content="1" /><meta itemprop="name" content="' . $home_name . '" /></span> ';

            if (is_product()) { // WooCommerce product
                echo '<span>/</span> ';
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . get_permalink(wc_get_page_id('shop')) . '">' . $shop_name . '</a><meta itemprop="position" content="2" /><meta itemprop="name" content="' . $shop_name . '" /></span> ';
                $terms = get_the_terms(get_the_ID(), 'product_cat');
                if ($terms && !is_wp_error($terms)) {
                    $term = $terms[0];
                    echo '<span>/</span> ';
                    echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . get_term_link($term) . '">' . $term->name . '</a><meta itemprop="position" content="3" /><meta itemprop="name" content="' . $term->name . '" /></span> ';
                }
                echo '<span>/</span> ';
                echo '<span class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">' . get_the_title() . '<meta itemprop="item" content="' . get_the_permalink() . '"><meta itemprop="name" content="' . get_the_title() . '" /><meta itemprop="position" content="4" /></span>';

            } elseif (is_shop()) { // WooCommerce shop
                echo '<span>/</span> ';
                echo '<span class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">' . $shop_name . '<meta itemprop="item" content="' . get_permalink(wc_get_page_id('shop')) . '"><meta itemprop="name" content="' . $shop_name . '" /><meta itemprop="position" content="2" /></span>';

            } elseif (is_product_category()) { // WooCommerce category
                $term = get_queried_object();
                echo '<span>/</span> ';
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . get_permalink(wc_get_page_id('shop')) . '">' . $shop_name . '</a><meta itemprop="position" content="2" /><meta itemprop="name" content="' . $shop_name . '" /></span> ';
                echo '<span>/</span> ';
                echo '<span class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">' . $term->name . '<meta itemprop="item" content="' . get_term_link($term) . '"><meta itemprop="name" content="' . $term->name . '" /><meta itemprop="position" content="3" /></span>';

            } elseif (is_single()) { // posts
                echo '<span>/</span> ';
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . site_url('/blog') . '/">Blog</a><meta itemprop="position" content="2" /><meta itemprop="name" content="Blog" /></span> ';
                echo '<span>/</span> ';
                echo '<span class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">' . get_the_title() . '<meta itemprop="item" content="' . get_the_permalink() . '"><meta itemprop="name" content="' . get_the_title() . '" /><meta itemprop="position" content="3" /></span>';

            } elseif (is_category()) { // tag pages
                $tag = get_queried_object();
                echo '<span>/</span> ';
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . site_url('/blog') . '/">Blog</a><meta itemprop="position" content="2" /><meta itemprop="name" content="Blog" /></span> ';
                echo '<span>/</span> ';

                $pageNum = (get_query_var('paged')) ? get_query_var('paged') : 1;

                if ($pageNum > 1) {
                    echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . get_tag_link($tag) . '">' . $tag->name . '</a><meta itemprop="name" content="' . $tag->name . '" /><meta itemprop="position" content="3" /></span> ';
                    echo '<span class="divider">/</span> ';
                    echo '<span class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">Page ' . $pageNum . '<meta itemprop="name" content="Page ' . $pageNum . '" /><meta itemprop="position" content="4" /></span>';
                } else {
                    echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">' . $tag->name . '<meta itemprop="item" content="' . get_tag_link($tag) . '"><meta itemprop="name" content="' . $tag->name . '" /><meta itemprop="position" content="3" /></span>';
                }
            } elseif (is_page()) { // pages
                $post = get_post();
                $pos = 0;
                global $wp_query;
                $object = $wp_query->get_queried_object();
                $parent_id  = $object->post_parent;
                $depth = 0;
                while ($parent_id > 0) {
                    $page = get_page($parent_id);
                    $parent_id = $page->post_parent;
                    $depth++;
                }

                if ($post->post_parent) {
                    $parent_id = $post->post_parent;
                    $breadcrumbs = array();

                    while ($parent_id) {
                        $pos++;
                        $page = get_post($parent_id);
                        $crumb_title = get_the_title($page->ID);

                        $breadcrumbs[] = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . get_permalink($page->ID) . '">' . $crumb_title . '</a><meta itemprop="name" content="' . $crumb_title . '" /><meta itemprop="position" content="' . ($depth + 2 - $pos) . '" /></span>';
                        $parent_id = $page->post_parent;
                    }

                    $breadcrumbs = array_reverse($breadcrumbs);

                    foreach ($breadcrumbs as $crumb) {
                        echo $crumb;
                    }
                }

                $page_title = get_the_title();
                echo '<span>/</span> ';
                echo '<span class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">' . $page_title . '<meta itemprop="item" content="' . get_the_permalink() . '"><meta itemprop="name" content="' . $page_title . '" /><meta itemprop="position" content="' . ($depth + 2) . '" /></span>';
            } elseif (is_404()) { // 404 page
                echo '<span>/</span> ';
                echo '<span class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">Page not found<meta itemprop="name" content="404" /><meta itemprop="position" content="2" /></span>';
            } elseif (is_search()) { // search results
                echo '<span>/</span> ';
                echo '<span class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">Search results<meta itemprop="item" content="' . get_search_link() . '"><meta itemprop="name" content="Search results" /><meta itemprop="position" content="2" /></span>';
            } elseif(is_tax('strain')){
                echo '<span class="divider">/</span> ';
                echo '<span class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . site_url('/strains') . '/">Strains</a><meta itemprop="name" content="Strains" /><meta itemprop="position" content="2" /></span> ';
                $tag = get_queried_object();
                $pageNum = (get_query_var('paged')) ? get_query_var('paged') : 1;
                if ($pageNum > 1) {
                    echo '<span class="divider">/</span> ';
                    echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . get_tag_link($tag) . '">' . $tag->name . '</a><meta itemprop="item" content="' . get_tag_link($tag) . '"><meta itemprop="name" content="' . $tag->name . '" /><meta itemprop="position" content="3" /></span> ';
                    echo '<span class="divider">/</span> ';
                    echo '<span class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">Page ' . $pageNum . '<meta itemprop="name" content="Page ' . $pageNum . '" /><meta itemprop="position" content="4" /></span>';
                } else {
                    echo '<span class="divider">/</span> ';
                    echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">' . $tag->name . '<meta itemprop="item" content="' . get_tag_link($tag) . '"><meta itemprop="name" content="' . $tag->name . '" /><meta itemprop="position" content="3" /></span>';
                }
            }


            echo '</div>';
        } else {
            echo '<div itemscope="" itemtype="http://schema.org/BreadcrumbList" class="breadcrumbs"><span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . $site_url . '">' . $home_name . '</a><meta itemprop="name" content="' . $home_name . '" /><meta itemprop="position" content="1" /></span> ';
            $pageNum = (get_query_var('paged')) ? get_query_var('paged') : 1;

            if ($pageNum > 1) {
                echo '<span>/</span> ';
                echo '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . get_post_type_archive_link('post') . '">Blog</a><meta itemprop="name" content="Blog" /><meta itemprop="position" content="2" /></span> ';
                echo '<span>/</span> ';
                echo '<span class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">Page ' . $pageNum . '<meta itemprop="item" content="' . get_post_type_archive_link('post') . 'page/' . $pageNum . '/" /><meta itemprop="name" content="Page ' . $pageNum . '" /><meta itemprop="position" content="3" /></span>';
            } else {
                echo '<span>/</span> ';
                echo '<span class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">Blog<meta itemprop="item" content="' . get_post_type_archive_link('post') . '" /><meta itemprop="name" content="Blog" /><meta itemprop="position" content="2" /></span>';
            }

            echo '</div>';
        }
    }
}

function breadcrumbs_shortcode() {
    ob_start();
    breadcrumbs();
    return ob_get_clean();
}
add_shortcode('breadcrumbs', 'breadcrumbs_shortcode');

