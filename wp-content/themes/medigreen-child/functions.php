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