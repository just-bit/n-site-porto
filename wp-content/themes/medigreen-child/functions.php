<?php

// Remove decimals from prices
add_filter('wc_get_price_decimals', function() {
    return 0;
});


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

// Remove sale badge from catalog
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);

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
                  var formattedPrice = '<?php echo html_entity_decode(get_woocommerce_currency_symbol()); ?>' + parseFloat(price).toFixed(0);

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
    $home_name = 'Weed online in Portugal';
    $shop_name = 'Catalog Weed Products';

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

// Change "Weight" to "Weight:" in variations
add_filter('woocommerce_attribute_label', 'custom_attribute_label', 10, 3);
function custom_attribute_label($label, $name, $product) {
	if ($name === 'pa_weight' || strtolower($label) === 'weight') {
		return 'Weight:';
	}
	return $label;
}

// tabs description
add_filter( 'woocommerce_product_tabs', 'change_description_tab_callback', 98 );

function change_description_tab_callback( $tabs ) {
    if ( isset( $tabs['description'] ) ) {

        $tabs['description']['callback'] = 'my_custom_description_content';
    }
    return $tabs;
}

function my_custom_description_content() {
    $heading = apply_filters( 'woocommerce_product_description_heading', __( 'Description', 'woocommerce' ) );

    if ( $heading ) {
        echo '<strong class="description-heading">' . esc_html( $heading ) . '</strong><br><br>';
    }

    the_content();
}

add_filter( 'woocommerce_product_tabs', 'remove_additional_tab', 98 );

function remove_additional_tab( $tabs ) {
    unset( $tabs['additional_information'] );

    return $tabs;
}

// Remove meta from product page
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// Display category reviews in catalog
function display_category_reviews_slider() {

    // Get current category
    $category = get_queried_object();

    // Get product IDs from current category
    if ( is_product_category() ) {
        $product_ids = get_posts( array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $category->term_id,
                ),
            ),
        ) );
    } else {
        // Shop page - get all products
        $product_ids = get_posts( array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ) );
    }

    if ( empty( $product_ids ) ) {
        return;
    }

    // Get reviews for these products
    $reviews = get_comments( array(
        'post__in'   => $product_ids,
        'status'     => 'approve',
        'type'       => 'review',
        'number'     => 50,
        'orderby'    => 'comment_date',
        'order'      => 'DESC',
    ) );

    if ( empty( $reviews ) ) {
        return;
    }

    // Calculate average rating
    $total_rating = 0;
    $rating_count = 0;
    foreach ( $reviews as $review ) {
        $rating = intval( get_comment_meta( $review->comment_ID, 'rating', true ) );
        if ( $rating > 0 ) {
            $total_rating += $rating;
            $rating_count++;
        }
    }
    $average_rating = $rating_count > 0 ? $total_rating / $rating_count : 0;
    ?>

    <div id="category-reviews" class="category-reviews-section">
        <div class="reviews-section-header">
            <h2 class="reviews-title"><?php esc_html_e( 'Customer Reviews', 'medigreen' ); ?></h2>
            <div class="reviews-summary">
                <div class="reviews-stars">
                    <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                        <span class="star <?php echo $i <= round( $average_rating ) ? 'filled' : ''; ?>"></span>
                    <?php endfor; ?>
                </div>
                <span class="reviews-rating">(<?php echo number_format( $average_rating, 1 ); ?>)</span>
                <span class="reviews-count"><?php printf( esc_html__( 'based on %s reviews', 'medigreen' ), count( $reviews ) ); ?></span>
            </div>
        </div>

        <div class="reviews-cards-wrapper">
            <button type="button" class="reviews-nav-prev" aria-label="Previous"></button>
            <div class="reviews-cards-slider category-reviews-slider">
                <?php foreach ( $reviews as $review ) :
                    $rating = intval( get_comment_meta( $review->comment_ID, 'rating', true ) );
                    $product_name = get_the_title( $review->comment_post_ID );
                    $product_url = get_permalink( $review->comment_post_ID );
                ?>
                <div class="review-card">
                    <div class="review-card-inner">
                        <div class="review-card-header">
                            <span class="review-author"><?php echo esc_html( $review->comment_author ); ?></span>
                        </div>
                        <a href="<?php echo esc_url( $product_url ); ?>" class="review-card-product"><?php echo esc_html( $product_name ); ?></a>
                        <div class="review-card-content">
                            <?php echo wpautop( $review->comment_content ); ?>
                        </div>
                        <div class="review-card-footer">
                            <?php if ( $rating > 0 ) : ?>
                            <div class="review-stars">
                                <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                    <span class="star <?php echo $i <= $rating ? 'filled' : ''; ?>"></span>
                                <?php endfor; ?>
                            </div>
                            <?php endif; ?>
                            <span class="review-date"><?php echo date_i18n( 'd.m.Y', strtotime( $review->comment_date ) ); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="reviews-nav-next" aria-label="Next"></button>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var $slider = $('.category-reviews-slider');
        var $wrapper = $slider.closest('.reviews-cards-wrapper');
        var $prevBtn = $wrapper.find('.reviews-nav-prev');
        var $nextBtn = $wrapper.find('.reviews-nav-next');

        if ($slider.length && !$slider.hasClass('slick-initialized')) {
            $slider.slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                infinite: false,
                arrows: false,
                dots: false,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                            dots: true
                        }
                    }
                ]
            });

            $prevBtn.on('click', function() {
                $slider.slick('slickPrev');
            });

            $nextBtn.on('click', function() {
                $slider.slick('slickNext');
            });

            function updateButtons() {
                var currentSlide = $slider.slick('slickCurrentSlide');
                var slideCount = $slider.slick('getSlick').slideCount;
                var slidesToShow = $slider.slick('getSlick').options.slidesToShow;

                $prevBtn.toggleClass('slick-disabled', currentSlide === 0);
                $nextBtn.toggleClass('slick-disabled', currentSlide >= slideCount - slidesToShow);
            }

            $slider.on('afterChange', updateButtons);
            updateButtons();
        }
    });
    </script>
    <?php
}

// Fix wpautop for wpcr shortcode
function wpcr_shortcode_fix( $content ) {
    // Remove <p> tags around the shortcode
    $content = preg_replace( '/<p>\s*(\[wpcr_reviews_slider[^\]]*\])\s*<\/p>/i', '$1', $content );
    return $content;
}
add_filter( 'the_content', 'wpcr_shortcode_fix', 9 );

// Display WP Customer Reviews in slider format
function display_wpcr_reviews_slider( $atts = array() ) {
    // Remove wpautop temporarily
    remove_filter( 'the_content', 'wpautop' );

    $atts = shortcode_atts( array(
        'limit' => 50,
        'title' => __( 'Customer Reviews', 'medigreen' ),
    ), $atts );

    // Get reviews from WP Customer Reviews plugin
    $reviews = get_posts( array(
        'post_type'      => 'wpcr3_review',
        'post_status'    => 'publish',
        'posts_per_page' => intval( $atts['limit'] ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );

    if ( empty( $reviews ) ) {
        return '';
    }

    // Calculate average rating
    $total_rating = 0;
    $rating_count = 0;
    foreach ( $reviews as $review ) {
        $rating = intval( get_post_meta( $review->ID, 'wpcr3_review_rating', true ) );
        if ( $rating > 0 ) {
            $total_rating += $rating;
            $rating_count++;
        }
    }
    $average_rating = $rating_count > 0 ? $total_rating / $rating_count : 0;

    ob_start();
    ?>
    <div id="wpcr-reviews">
        <div class="reviews-section-header">
            <div class="reviews-summary">
                <div class="reviews-stars">
                    <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                        <span class="star <?php echo $i <= round( $average_rating ) ? 'filled' : ''; ?>"></span>
                    <?php endfor; ?>
                </div>
                <span class="reviews-rating">(<?php echo number_format( $average_rating, 1 ); ?>)</span>
                <span class="reviews-count"><?php printf( esc_html__( 'based on %s reviews', 'medigreen' ), count( $reviews ) ); ?></span>
            </div>
            <a href="<?php echo site_url('/reviews/'); ?>" class="reviews-all-link">All reviews</a>
        </div>

        <div class="reviews-cards-wrapper">
            <button type="button" class="reviews-nav-prev wpcr-nav-prev" aria-label="Previous"></button>
            <div class="reviews-cards-slider wpcr-slider">
                <?php foreach ( $reviews as $review ) :
                    $rating = intval( get_post_meta( $review->ID, 'wpcr3_review_rating', true ) );
                    $author_name = get_post_meta( $review->ID, 'wpcr3_review_name', true );
                    if ( empty( $author_name ) ) {
                        $author_name = $review->post_title;
                    }
                    $review_title = get_post_meta( $review->ID, 'wpcr3_review_title', true );
                    $review_text = $review->post_content;
                    $review_date = get_the_date( 'M j, Y', $review->ID );
                ?>
                <div class="review-card">
                    <div class="review-card-inner">
                        <div class="review-card-header">
                            <span class="review-author"><?php echo esc_html( $author_name ); ?></span>
                        </div>
                        <?php if ( ! empty( $review_title ) ) : ?>
                        <div class="review-card-title"><?php echo esc_html( $review_title ); ?></div>
                        <?php endif; ?>
                        <div class="review-card-content">
                            <?php echo wp_kses_post( nl2br( $review_text ) ); ?>
                        </div>
                        <div class="review-card-footer">
                            <?php if ( $rating > 0 ) : ?>
                            <div class="review-stars">
                                <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                    <span class="star <?php echo $i <= $rating ? 'filled' : ''; ?>"></span>
                                <?php endfor; ?>
                            </div>
                            <?php endif; ?>
                            <span class="review-date"><?php echo esc_html( $review_date ); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="reviews-nav-next wpcr-nav-next" aria-label="Next"></button>
        </div>
    </div>

<?php
    $output = ob_get_clean();

    // Remove empty p tags and restore wpautop
    $output = preg_replace( '/<p>\s*<\/p>/', '', $output );
    $output = preg_replace( '/<p><\/p>/', '', $output );
    $output = str_replace( array( '<p>', '</p>' ), '', $output );

    // Restore wpautop
    add_filter( 'the_content', 'wpautop' );

    // Add inline script to footer
    if ( ! has_action( 'wp_footer', 'wpcr_slider_inline_script' ) ) {
        add_action( 'wp_footer', 'wpcr_slider_inline_script', 99 );
    }

    return $output;
}

function wpcr_slider_inline_script() {
    ?>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
    <script>
    (function($) {
        function initWpcrSlider() {
            var $slider = $('#wpcr-reviews .wpcr-slider');
            var $prevBtn = $('#wpcr-reviews .wpcr-nav-prev');
            var $nextBtn = $('#wpcr-reviews .wpcr-nav-next');

            // Remove empty p tags before init
            $slider.children('p').each(function() {
                if ($(this).find('.review-card').length) {
                    $(this).replaceWith($(this).contents());
                } else if ($.trim($(this).text()) === '') {
                    $(this).remove();
                }
            });
            $slider.children('br').remove();

            if ($slider.length && !$slider.hasClass('slick-initialized') && typeof $.fn.slick !== 'undefined') {
                $slider.slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    infinite: false,
                    arrows: false,
                    dots: false,
                    responsive: [
                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 3
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1,
                                dots: true
                            }
                        }
                    ]
                });

                $prevBtn.on('click', function() {
                    $slider.slick('slickPrev');
                });

                $nextBtn.on('click', function() {
                    $slider.slick('slickNext');
                });

                function updateButtons() {
                    var currentSlide = $slider.slick('slickCurrentSlide');
                    var slideCount = $slider.slick('getSlick').slideCount;
                    var slidesToShow = $slider.slick('getSlick').options.slidesToShow;

                    $prevBtn.toggleClass('slick-disabled', currentSlide === 0);
                    $nextBtn.toggleClass('slick-disabled', currentSlide >= slideCount - slidesToShow);
                }

                $slider.on('afterChange', updateButtons);
                updateButtons();
            }
        }

        $(document).ready(function() {
            setTimeout(initWpcrSlider, 100);
        });

        $(window).on('load', function() {
            initWpcrSlider();
        });
    })(jQuery);
    </script>
    <?php
}
add_shortcode( 'wpcr_reviews_slider', 'display_wpcr_reviews_slider' );

// Fix WPCR3 AJAX pagination nesting bug
add_action('wp_footer', 'fix_wpcr3_ajax_pagination', 999);
function fix_wpcr3_ajax_pagination() {
    ?>
    <script>
    jQuery(function($) {
        // Override pagination click handler
        $(document).off('click', '.wpcr3_pagination .wpcr3_a');
        $('.wpcr3_respond_1').off('click', '.wpcr3_pagination .wpcr3_a');

        $(document).on('click', '.wpcr3_pagination .wpcr3_a', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var t = $(this);
            if (t.hasClass('wpcr3_disabled')) return false;

            var pager = t.closest('.wpcr3_pagination');
            var parent = t.closest('.wpcr3_respond_1');
            var reviews = parent.find('.wpcr3_reviews_holder').first();
            var page = t.attr('data-page');
            var pageOpts = pager.attr('data-page-opts');
            var on_postid = parent.attr('data-on-postid');

            var ajaxData = { ajaxAct2: 'pager', on_postid: on_postid, page: page, pageOpts: pageOpts };

            wpcr3.ajaxPost(parent, ajaxData, function(err, rtn) {
                if (err) return;

                // Parse response and extract only reviews and pagination
                var $response = $('<div>').html(rtn.output);
                var $newReviews = $response.find('.wpcr3_review_item');
                var $newPagination = $response.find('.wpcr3_pagination').last();

                // If no review items found, try getting from reviews_holder
                if ($newReviews.length === 0) {
                    var $holder = $response.find('.wpcr3_reviews_holder').last();
                    if ($holder.length) {
                        $newReviews = $holder.children('.wpcr3_review_item');
                    }
                }

                // Update reviews
                if ($newReviews.length > 0) {
                    reviews.empty().append($newReviews.clone());
                } else {
                    // Fallback: get innermost reviews_holder content
                    var $innerHolder = $response.find('.wpcr3_reviews_holder').last();
                    if ($innerHolder.length) {
                        reviews.html($innerHolder.html());
                    }
                }

                // Update pagination
                if ($newPagination.length) {
                    pager.replaceWith($newPagination.clone());
                }

                $('html,body').animate({
                    scrollTop: reviews.offset().top - 100
                });
            });

            return false;
        });
    });

    jQuery(document).on('ready ajaxComplete', function() {
        jQuery('.wpcr3_review_date').each(function() {
            var text = jQuery(this).text().trim();
            if (text.indexOf(',') > -1) {
                var date = new Date(text);
                if (!isNaN(date.getTime())) {
                    var d = ("0" + date.getDate()).slice(-2);
                    var m = ("0" + (date.getMonth() + 1)).slice(-2);
                    var y = date.getFullYear();
                    jQuery(this).text(d + '.' + m + '.' + y);
                }
            }
        });
    });

    // Toggle WPCR3 review form by #review-form button
    jQuery(document).on('click', '#review-form, [href="#review-form"], [data-mfp-src="#reviews-popup"]', function(e) {
        e.preventDefault();
        var $form = jQuery('.wpcr3_respond_2');
        if ($form.length) {
            $form.slideToggle(400, function() {
                if ($form.is(':visible')) {
                    jQuery('html, body').animate({
                        scrollTop: $form.offset().top - 100
                    }, 400);
                }
            });
        }
        return false;
    });
    </script>
    <?php
}

add_filter('wpcr3_format_date', function($date, $timestamp) {
    return date('d.m.Y', $timestamp);
}, 10, 2);

// Shortcode for WPCR rating stars
function wpcr_rating_shortcode($atts) {
    $atts = shortcode_atts(array(
        'text' => 'based on %s reviews',
    ), $atts);

    // Get all published reviews
    $reviews = get_posts(array(
        'post_type'      => 'wpcr3_review',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ));

    if (empty($reviews)) {
        return '';
    }

    // Calculate average rating
    $total_rating = 0;
    $rating_count = 0;
    foreach ($reviews as $review_id) {
        $rating = intval(get_post_meta($review_id, 'wpcr3_review_rating', true));
        if ($rating > 0) {
            $total_rating += $rating;
            $rating_count++;
        }
    }

    if ($rating_count === 0) {
        return '';
    }

    $average_rating = round($total_rating / $rating_count);
    $reviews_count = count($reviews);

    ob_start();
    ?>
    <div class="rating-stars-wrapper">
        <div class="rating-stars">
            <?php for ($i = 1; $i <= 5; $i++) : ?>
                <span class="rating-star <?php echo $i <= $average_rating ? 'active' : ''; ?>"></span>
            <?php endfor; ?>
        </div>
        <div><span style="font-weight: 600; margin-right: 5px;">(<?php echo $average_rating; ?>)</span> <?php printf(esc_html($atts['text']), $reviews_count); ?></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('wpcr_rating', 'wpcr_rating_shortcode');

// Create Strain Taxonomy
add_action( 'init', 'create_strain_taxonomy' );
function create_strain_taxonomy() {
    $args = array(
        'labels' => [
            'name' => 'Strains',
            'singular_name' => 'Strain',
            'search_items' => 'Search Strains',
            'all_items' => 'All Strains',
            'edit_item' => 'Edit Strain',
            'update_item' => 'Update Strain',
            'add_new_item' => 'Add New Strain',
            'new_item_name' => 'New Strain Name',
            'menu_name' => 'Strains',
        ],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite' => [ 'slug' => 'strains' ],
    );

    register_taxonomy( 'strain', 'product', $args);
}

add_action( 'pre_get_posts', 'canna_pre_get_strains');

function canna_pre_get_strains($query){
    if( !is_admin()) {
        if ($query->is_main_query() && $query->is_tax('strain')) {
            $query->set('posts_per_page', 16);
        }
    }
}

// Force custom template for strain taxonomy (override WooCommerce)
add_filter('template_include', 'strain_taxonomy_template', 999);
function strain_taxonomy_template($template) {
    if (is_tax('strain')) {
        $custom_template = get_stylesheet_directory() . '/taxonomy-strain.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    return $template;
}

// Отключить сайдбар для страниц таксономии strain
add_filter('is_active_sidebar', 'strain_disable_sidebar', 10, 2);
function strain_disable_sidebar($is_active_sidebar, $index) {
    if (is_tax('strain')) {
        return false;
    }
    return $is_active_sidebar;
}

add_filter('xmlrpc_enabled', '__return_false');

// Shortcode для вывода списка strains
function strains_list_shortcode($atts) {
    $atts = shortcode_atts(array(
        'columns' => 4,
    ), $atts);

    $terms = get_terms(array(
        'taxonomy'   => 'strain',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ));

    if (empty($terms) || is_wp_error($terms)) {
        return '<p>No strains found.</p>';
    }

    ob_start();
    ?>
    <div class="strains-grid" style="--strains-columns: <?= intval($atts['columns']) ?>;">
        <?php foreach ($terms as $term): ?>
            <?php
            $image = get_field('image', 'strain_' . $term->term_id);
            $thc = get_field('thc', 'strain_' . $term->term_id);
            $types = get_field('type', 'strain_' . $term->term_id);
            $effects = get_field('effects', 'strain_' . $term->term_id);
            $cbd = get_field('cbd', 'strain_' . $term->term_id);
            $thcpercentage = get_field('thc_percentage', 'strain_' . $term->term_id);
            ?>
            <div class="strain-card">
                <a href="<?= esc_url(get_term_link($term)) ?>" class="strain-card__link">
                    <div class="strain-card__header">
                        <?php if ($image): ?>
                        <div class="strain-card__image">
                            <img src="<?= esc_url($image) ?>" alt="<?= esc_attr($term->name) ?>" loading="lazy">
                        </div>
                        <?php endif; ?>
                        <h2 class="strain-card__title">
                            <?= esc_html($term->name) ?>
                        </h2>
                    </div>

                    <div class="strain-card__content">
                        <ul class="strain-card__meta">
                            <?php if ($thc && is_array($thc)): ?>
                            <li>
                                <svg class="strain-card__icon" width="18" height="18" viewBox="0 0 21 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.1296 14.995C12.1999 14.995 13.8783 13.3166 13.8783 11.2463C13.8783 9.17591 12.1999 7.49756 10.1296 7.49756C8.05921 7.49756 6.38086 9.17591 6.38086 11.2463C6.38086 13.3166 8.05921 14.995 10.1296 14.995Z" fill="#6E1F8C" fill-opacity="0.1"/>
                                    <path d="M19.7351 5.90382C18.8388 4.41259 16.9502 3.69209 14.6035 3.73408C14.397 3.29399 14.1589 2.86941 13.8912 2.46364C14.1374 2.17309 14.2632 1.79946 14.243 1.41918C14.2228 1.03891 14.058 0.680731 13.7824 0.417924C13.5068 0.155118 13.1412 0.00754573 12.7604 0.00539582C12.3796 0.0032459 12.0124 0.146681 11.7338 0.406359C11.2392 0.144588 10.689 0.00523942 10.1294 0C8.39451 0 6.77019 1.36753 5.64521 3.75507C4.90734 3.73567 4.16999 3.80974 3.45072 3.97549C3.23272 3.6858 2.91686 3.48522 2.56196 3.41112C2.20706 3.33702 1.83732 3.39445 1.52161 3.57271C1.20591 3.75097 0.965765 4.03791 0.845908 4.38008C0.726051 4.72225 0.734656 5.09632 0.870118 5.43261C0.741494 5.57886 0.625847 5.73602 0.524488 5.90232C0.0657667 6.7046 -0.0955032 7.64288 0.0690218 8.55228L0.809102 8.43928C0.988127 9.37207 1.35826 10.2577 1.89622 11.0405L1.26869 11.4529C0.671561 10.5804 0.262763 9.59317 0.0683594 8.55399" fill="#6E1F8C"/>
                                </svg>
                                <span><strong>THC:</strong>
                                    <?php foreach ($thc as $i => $t): ?>
                                        <?= esc_html(is_object($t) ? $t->name : $t) ?><?= $i < count($thc) - 1 ? ', ' : '' ?>
                                    <?php endforeach; ?>
                                </span>
                            </li>
                            <?php endif; ?>

                            <?php if ($types && is_array($types)): ?>
                            <li>
                                <svg class="strain-card__icon" width="18" height="18" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.665 8.33399C19.6787 10.3022 19.4132 12.2624 18.8763 14.156C18.8164 14.3587 18.7094 14.5443 18.5641 14.6978C18.4189 14.8513 18.2394 14.9683 18.0404 15.0393C15.4515 15.9191 12.7329 16.3566 9.99875 16.3336C7.26456 16.3566 4.54602 15.9191 1.95713 15.0393C1.75809 14.9683 1.57864 14.8513 1.43337 14.6978C1.28811 14.5443 1.18114 14.3587 1.12117 14.156C0.58435 12.2624 0.318829 10.3022 0.332536 8.33399C0.318829 6.36577 0.58435 4.40557 1.12117 2.51192C1.18114 2.30928 1.28811 2.12366 1.43337 1.97017C1.57864 1.81668 1.75809 1.69966 1.95713 1.62863C4.54602 0.748892 7.26456 0.311351 9.99875 0.334358C12.7329 0.311351 15.4515 0.748892 18.0404 1.62863C18.2394 1.69966 18.4189 1.81668 18.5641 1.97017C18.7094 2.12366 18.8164 2.30928 18.8763 2.51192C19.4132 4.40557 19.6787 6.36577 19.665 8.33399Z" fill="#6E1F8C" fill-opacity="0.1"/>
                                    <path d="M8.33441 12.6669C8.20309 12.667 8.07304 12.6412 7.95172 12.5909C7.8304 12.5407 7.7202 12.4669 7.62745 12.374L5.62754 10.3741C5.53203 10.2818 5.45586 10.1715 5.40345 10.0495C5.35104 9.92747 5.32346 9.79626 5.3223 9.66349C5.32115 9.53071 5.34645 9.39904 5.39673 9.27615C5.44701 9.15326 5.52126 9.04161 5.61515 8.94773C5.70903 8.85384 5.82068 8.77959 5.94357 8.72931C6.06646 8.67903 6.19814 8.65373 6.33091 8.65488C6.46368 8.65604 6.5949 8.68362 6.71689 8.73603C6.83889 8.78843 6.94923 8.86461 7.04147 8.96012L8.25575 10.1747L13.2222 4.03835C13.389 3.8322 13.631 3.70077 13.8947 3.67299C14.1585 3.6452 14.4225 3.72333 14.6286 3.89019C14.8348 4.05705 14.9662 4.29896 14.994 4.56272C15.0218 4.82648 14.9436 5.09047 14.7768 5.29662L9.11038 12.2963C9.02244 12.4053 8.91259 12.4946 8.78793 12.5585C8.66328 12.6223 8.52659 12.6593 8.38674 12.6669H8.33441Z" fill="#6E1F8C"/>
                                </svg>
                                <span><strong>Type:</strong>
                                    <?php foreach ($types as $i => $t): ?>
                                        <?= esc_html(is_object($t) ? $t->name : $t) ?><?= $i < count($types) - 1 ? ', ' : '' ?>
                                    <?php endforeach; ?>
                                </span>
                            </li>
                            <?php endif; ?>

                            <?php if ($effects && is_array($effects)): ?>
                            <li>
                                <svg class="strain-card__icon" width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.0022 8.90026C12.0102 10.1924 11.8464 11.48 11.5153 12.729C11.2323 13.9015 10.7249 15.008 10.0211 15.9875C9.31863 15.0056 8.81035 13.8985 8.52362 12.7257C8.18136 11.4792 8.00529 10.1929 8 8.90026C8.05735 5.9837 8.68623 3.10672 9.85102 0.432248C9.86366 0.402927 9.8846 0.377948 9.91128 0.360398C9.93795 0.342849 9.96918 0.333496 10.0011 0.333496C10.033 0.333496 10.0643 0.342849 10.0909 0.360398C10.1176 0.377948 10.1386 0.402927 10.1512 0.432248C11.316 3.10672 11.9449 5.9837 12.0022 8.90026Z" fill="#6E1F8C" fill-opacity="0.1"/>
                                    <path d="M9.98095 15.987C9.82086 15.8403 8.60019 14.773 6.33227 14.6729C5.68286 14.2861 5.07098 13.8395 4.50459 13.3389C2.74973 11.701 1.33627 9.73216 0.34563 7.54568C0.333034 7.5161 0.329079 7.48356 0.334222 7.45183C0.339366 7.42009 0.353397 7.39047 0.37469 7.36638C0.395983 7.3423 0.423666 7.32474 0.45453 7.31574C0.485394 7.30675 0.518176 7.30668 0.549075 7.31556C2.82491 8.06388 4.93071 9.25392 6.74583 10.8175C7.38948 11.3903 7.97182 12.0286 8.48346 12.7219C8.76978 13.8958 9.27808 15.0041 9.98095 15.987Z" fill="#6E1F8C"/>
                                </svg>
                                <span><strong>Effects:</strong>
                                    <?php foreach ($effects as $i => $t): ?>
                                        <?= esc_html(is_object($t) ? $t->name : $t) ?><?= $i < count($effects) - 1 ? ', ' : '' ?>
                                    <?php endforeach; ?>
                                </span>
                            </li>
                            <?php endif; ?>
                        </ul>

                        <div class="strain-card__scales">
                            <?php
                            $cbd_val = $cbd && isset($cbd[0]) ? $cbd[0]->name : '';
                            $thc_val = $thcpercentage && isset($thcpercentage[0]) ? $thcpercentage[0]->name : '';
                            ?>
                            <?php if ($cbd_val): ?>
                            <div class="strain-card__scale">
                                <div class="strain-card__scale-labels">
                                    <span>Calming</span>
                                    <span>Energizing</span>
                                </div>
                                <div class="strain-card__scale-bar">
                                    <span style="width: <?= esc_attr($cbd_val) ?>;"></span>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($thc_val): ?>
                            <div class="strain-card__scale">
                                <div class="strain-card__scale-labels">
                                    <span>Low THC</span>
                                    <span>High THC</span>
                                </div>
                                <div class="strain-card__scale-bar">
                                    <span style="width: <?= esc_attr($thc_val) ?>;"></span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('strains_list', 'strains_list_shortcode');

// Shortcode для вывода одного strain
function strain_single_shortcode($atts) {
    $atts = shortcode_atts(array(
        'slug' => '',
        'id' => '',
    ), $atts);

    $strain = null;

    // Get strain by id or slug
    if (!empty($atts['id'])) {
        $strain = get_term(intval($atts['id']), 'strain');
    } elseif (!empty($atts['slug'])) {
        $strain = get_term_by('slug', $atts['slug'], 'strain');
    } else {
        // Try to get from URL
        $url_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $parts = explode('/', $url_path);
        if (count($parts) >= 2 && $parts[0] === 'strains') {
            $strain = get_term_by('slug', $parts[1], 'strain');
        }
    }

    if (!$strain || is_wp_error($strain)) {
        return '<p>Strain not found.</p>';
    }

    // ACF fields
    $image = get_field('image', 'strain_' . $strain->term_id);
    $thc = get_field('thc', 'strain_' . $strain->term_id);
    $types = get_field('type', 'strain_' . $strain->term_id);
    $effects = get_field('effects', 'strain_' . $strain->term_id);
    $cbd = get_field('cbd', 'strain_' . $strain->term_id);
    $thcpercentage = get_field('thc_percentage', 'strain_' . $strain->term_id);

    // Scale values
    $cbd_val = $cbd && isset($cbd[0]) && is_object($cbd[0]) ? $cbd[0]->name : '';
    $thc_val = $thcpercentage && isset($thcpercentage[0]) && is_object($thcpercentage[0]) ? $thcpercentage[0]->name : '';

    ob_start();
    ?>
    <div class="strain-single">
        <div class="strain-single__header">
            <h1 class="strain-single__title"><?= esc_html($strain->name) ?></h1>
            <?php if ($strain->description): ?>
            <div class="strain-single__description">
                <?= wpautop(esc_html($strain->description)) ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="strain-single__content">
            <div class="strain-single__info">
                <?php if ($image): ?>
                <div class="strain-single__image">
                    <img src="<?= esc_url($image) ?>" alt="<?= esc_attr($strain->name) ?>">
                </div>
                <?php endif; ?>

                <div class="strain-single__details">
                    <ul class="strain-single__meta">
                        <?php if ($thc && is_array($thc)): ?>
                        <li>
                            <svg class="strain-single__icon" width="21" height="23" viewBox="0 0 21 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.1296 14.995C12.1999 14.995 13.8783 13.3166 13.8783 11.2463C13.8783 9.17591 12.1999 7.49756 10.1296 7.49756C8.05921 7.49756 6.38086 9.17591 6.38086 11.2463C6.38086 13.3166 8.05921 14.995 10.1296 14.995Z" fill="#6E1F8C" fill-opacity="0.1"/>
                                <path d="M19.7351 5.90382C18.8388 4.41259 16.9502 3.69209 14.6035 3.73408C14.397 3.29399 14.1589 2.86941 13.8912 2.46364C14.1374 2.17309 14.2632 1.79946 14.243 1.41918C14.2228 1.03891 14.058 0.680731 13.7824 0.417924C13.5068 0.155118 13.1412 0.00754573 12.7604 0.00539582C12.3796 0.0032459 12.0124 0.146681 11.7338 0.406359C11.2392 0.144588 10.689 0.00523942 10.1294 0C8.39451 0 6.77019 1.36753 5.64521 3.75507C4.90734 3.73567 4.16999 3.80974 3.45072 3.97549C3.23272 3.6858 2.91686 3.48522 2.56196 3.41112C2.20706 3.33702 1.83732 3.39445 1.52161 3.57271C1.20591 3.75097 0.965765 4.03791 0.845908 4.38008C0.726051 4.72225 0.734656 5.09632 0.870118 5.43261C0.741494 5.57886 0.625847 5.73602 0.524488 5.90232C0.0657667 6.7046 -0.0955032 7.64288 0.0690218 8.55228L0.809102 8.43928C0.988127 9.37207 1.35826 10.2577 1.89622 11.0405L1.26869 11.4529C0.671561 10.5804 0.262763 9.59317 0.0683594 8.55399" fill="#6E1F8C"/>
                            </svg>
                            <span>
                                <strong>THC:</strong>
                                <?php foreach ($thc as $i => $t): ?>
                                    <?= esc_html(is_object($t) ? $t->name : $t) ?><?= $i < count($thc) - 1 ? ', ' : '' ?>
                                <?php endforeach; ?>
                            </span>
                        </li>
                        <?php endif; ?>

                        <?php if ($types && is_array($types)): ?>
                        <li>
                            <svg class="strain-single__icon" width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.665 8.33399C19.6787 10.3022 19.4132 12.2624 18.8763 14.156C18.8164 14.3587 18.7094 14.5443 18.5641 14.6978C18.4189 14.8513 18.2394 14.9683 18.0404 15.0393C15.4515 15.9191 12.7329 16.3566 9.99875 16.3336C7.26456 16.3566 4.54602 15.9191 1.95713 15.0393C1.75809 14.9683 1.57864 14.8513 1.43337 14.6978C1.28811 14.5443 1.18114 14.3587 1.12117 14.156C0.58435 12.2624 0.318829 10.3022 0.332536 8.33399C0.318829 6.36577 0.58435 4.40557 1.12117 2.51192C1.18114 2.30928 1.28811 2.12366 1.43337 1.97017C1.57864 1.81668 1.75809 1.69966 1.95713 1.62863C4.54602 0.748892 7.26456 0.311351 9.99875 0.334358C12.7329 0.311351 15.4515 0.748892 18.0404 1.62863C18.2394 1.69966 18.4189 1.81668 18.5641 1.97017C18.7094 2.12366 18.8164 2.30928 18.8763 2.51192C19.4132 4.40557 19.6787 6.36577 19.665 8.33399Z" fill="#6E1F8C" fill-opacity="0.1"/>
                                <path d="M8.33441 12.6669C8.20309 12.667 8.07304 12.6412 7.95172 12.5909C7.8304 12.5407 7.7202 12.4669 7.62745 12.374L5.62754 10.3741C5.53203 10.2818 5.45586 10.1715 5.40345 10.0495C5.35104 9.92747 5.32346 9.79626 5.3223 9.66349C5.32115 9.53071 5.34645 9.39904 5.39673 9.27615C5.44701 9.15326 5.52126 9.04161 5.61515 8.94773C5.70903 8.85384 5.82068 8.77959 5.94357 8.72931C6.06646 8.67903 6.19814 8.65373 6.33091 8.65488C6.46368 8.65604 6.5949 8.68362 6.71689 8.73603C6.83889 8.78843 6.94923 8.86461 7.04147 8.96012L8.25575 10.1747L13.2222 4.03835C13.389 3.8322 13.631 3.70077 13.8947 3.67299C14.1585 3.6452 14.4225 3.72333 14.6286 3.89019C14.8348 4.05705 14.9662 4.29896 14.994 4.56272C15.0218 4.82648 14.9436 5.09047 14.7768 5.29662L9.11038 12.2963C9.02244 12.4053 8.91259 12.4946 8.78793 12.5585C8.66328 12.6223 8.52659 12.6593 8.38674 12.6669H8.33441Z" fill="#6E1F8C"/>
                            </svg>
                            <span>
                                <strong>Type:</strong>
                                <?php foreach ($types as $i => $t): ?>
                                    <?= esc_html(is_object($t) ? $t->name : $t) ?><?= $i < count($types) - 1 ? ', ' : '' ?>
                                <?php endforeach; ?>
                            </span>
                        </li>
                        <?php endif; ?>

                        <?php if ($effects && is_array($effects)): ?>
                        <li>
                            <svg class="strain-single__icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.0022 8.90026C12.0102 10.1924 11.8464 11.48 11.5153 12.729C11.2323 13.9015 10.7249 15.008 10.0211 15.9875C9.31863 15.0056 8.81035 13.8985 8.52362 12.7257C8.18136 11.4792 8.00529 10.1929 8 8.90026C8.05735 5.9837 8.68623 3.10672 9.85102 0.432248C9.86366 0.402927 9.8846 0.377948 9.91128 0.360398C9.93795 0.342849 9.96918 0.333496 10.0011 0.333496C10.033 0.333496 10.0643 0.342849 10.0909 0.360398C10.1176 0.377948 10.1386 0.402927 10.1512 0.432248C11.316 3.10672 11.9449 5.9837 12.0022 8.90026Z" fill="#6E1F8C" fill-opacity="0.1"/>
                                <path d="M9.98095 15.987C9.82086 15.8403 8.60019 14.773 6.33227 14.6729C5.68286 14.2861 5.07098 13.8395 4.50459 13.3389C2.74973 11.701 1.33627 9.73216 0.34563 7.54568C0.333034 7.5161 0.329079 7.48356 0.334222 7.45183C0.339366 7.42009 0.353397 7.39047 0.37469 7.36638C0.395983 7.3423 0.423666 7.32474 0.45453 7.31574C0.485394 7.30675 0.518176 7.30668 0.549075 7.31556C2.82491 8.06388 4.93071 9.25392 6.74583 10.8175C7.38948 11.3903 7.97182 12.0286 8.48346 12.7219C8.76978 13.8958 9.27808 15.0041 9.98095 15.987Z" fill="#6E1F8C"/>
                            </svg>
                            <span>
                                <strong>Effects:</strong>
                                <?php foreach ($effects as $i => $t): ?>
                                    <?= esc_html(is_object($t) ? $t->name : $t) ?><?= $i < count($effects) - 1 ? ', ' : '' ?>
                                <?php endforeach; ?>
                            </span>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <div class="strain-single__scales">
                        <?php if ($cbd_val): ?>
                        <div class="strain-single__scale">
                            <div class="strain-single__scale-labels">
                                <span>Calming</span>
                                <span>Energizing</span>
                            </div>
                            <div class="strain-single__scale-bar">
                                <span style="width: <?= esc_attr($cbd_val) ?>;"></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($thc_val): ?>
                        <div class="strain-single__scale">
                            <div class="strain-single__scale-labels">
                                <span>Low THC</span>
                                <span>High THC</span>
                            </div>
                            <div class="strain-single__scale-bar">
                                <span style="width: <?= esc_attr($thc_val) ?>;"></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('strain_single', 'strain_single_shortcode');

// Shortcode для вывода продуктов strain
function strain_products_shortcode($atts) {
    $atts = shortcode_atts(array(
        'slug' => '',
        'id' => '',
        'limit' => 16,
    ), $atts);

    $strain = null;

    if (!empty($atts['id'])) {
        $strain = get_term(intval($atts['id']), 'strain');
    } elseif (!empty($atts['slug'])) {
        $strain = get_term_by('slug', $atts['slug'], 'strain');
    } else {
        $url_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $parts = explode('/', $url_path);
        if (count($parts) >= 2 && $parts[0] === 'strains') {
            $strain = get_term_by('slug', $parts[1], 'strain');
        }
    }

    if (!$strain || is_wp_error($strain)) {
        return '';
    }

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => intval($atts['limit']),
        'tax_query' => array(
            array(
                'taxonomy' => 'strain',
                'field' => 'term_id',
                'terms' => $strain->term_id,
            ),
        ),
    );

    $products = new WP_Query($args);

    if (!$products->have_posts()) {
        return '';
    }

    ob_start();
    ?>
    <div class="strain-products">
        <h2 class="strain-products__title"><?= esc_html($strain->name) ?> Products</h2>
        <div class="strain-products__grid">
            <?php
            while ($products->have_posts()): $products->the_post();
                wc_get_template_part('content', 'product');
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('strain_products', 'strain_products_shortcode');


// strain header widgets disable
add_filter('sidebars_widgets', 'strain_disable_sidebar_widgets', 10, 1);
function strain_disable_sidebar_widgets($sidebars_widgets) {
    if (is_tax('strain')) {
     
        $header_sidebars = array('header_left_widgets', 'header_right_widgets', 'header_menu_widgets', 'header_logo_widgets');
        foreach ($sidebars_widgets as $sidebar_id => $widgets) {
            if (!in_array($sidebar_id, $header_sidebars)) {
                $sidebars_widgets[$sidebar_id] = false; // Отключаем сайдбары контента
            }
        }
    }
    return $sidebars_widgets;
}

remove_filter('is_active_sidebar', 'strain_disable_sidebar', 10);