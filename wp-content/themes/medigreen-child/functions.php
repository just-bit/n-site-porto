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

// Mobile class for btContentHolder
add_action('wp_footer', 'add_mobile_content_holder_class');
function add_mobile_content_holder_class() {
    if ( function_exists('is_woocommerce') && is_woocommerce() && wp_is_mobile() ) {
        ?>
        <script>
        document.querySelector('.btContentHolder')?.classList.add('btContentHolder-mobile');
        </script>
        <?php
    }
}

// Title "On sale"
add_action('wp_footer', 'add_onsale_filter_title');
function add_onsale_filter_title() {
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    ?>
    <script>
    jQuery(function($) {
        var $container = $('.woof_checkbox_sales_container');
        if ($container.length && !$container.prev('.woof_onsale_title').length) {
            $container.before('<h4 class="woof_onsale_title">Products with promotions</h4>');
        }
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
function sort_variation_prices_by_menu_order($prices_array, $product, $for_display) {
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
function add_menu_order_to_variation($data, $product, $variation) {
    $data['menu_order'] = $variation->get_menu_order();
    return $data;
}

// Add variation prices to attribute labels
add_action('wp_footer', 'add_variation_prices_to_labels');
function add_variation_prices_to_labels() {
    if (!is_product()) return;
    
    global $product;
    if (!$product || !$product->is_type('variable')) return;
    ?>
    <script>
    jQuery(function($) {
        var $form = $('form.variations_form');
        if (!$form.length) return;
        
        var variations = $form.data('product_variations');
        if (!variations) return;
        
        // Sort variations by menu_order
        variations.sort(function(a, b) {
            return (a.menu_order || 0) - (b.menu_order || 0);
        });
        
        // Build price map: attribute_value => {price, order}
        var priceMap = {};
        
        variations.forEach(function(variation, index) {
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
        $('.variable-items-wrapper').each(function() {
            var $wrapper = $(this);
            var $items = $wrapper.find('.variable-item');
            
            $items.sort(function(a, b) {
                var aVal = $(a).data('value') || $(a).attr('data-value');
                var bVal = $(b).data('value') || $(b).attr('data-value');
                var aOrder = priceMap[aVal] ? priceMap[aVal].order : 999;
                var bOrder = priceMap[bVal] ? priceMap[bVal].order : 999;
                return aOrder - bOrder;
            });
            
            $wrapper.append($items);
        });
        
        // Update swatches/buttons with prices
        $('.variable-items-wrapper .variable-item, .variations .value label, .wcvaswatches label').each(function() {
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