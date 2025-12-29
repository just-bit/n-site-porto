<?php
/*add_action('wp_enqueue_scripts', 'medigreen_child_enqueue_styles');
function medigreen_child_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'));
    wp_enqueue_script('medigreen-child-script', get_stylesheet_directory_uri() . '/script.js', array('jquery'), '1.0.0', true);
}*/

function my_theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_parent_theme_file_uri( 'style.css' ) );
    wp_enqueue_style( 'child-style',
        get_theme_file_uri( 'style.css' ),
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
    wp_enqueue_script('medigreen-child-script', get_stylesheet_directory_uri() . '/script.js', array('jquery'), '1.0.0', true);
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );



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


add_filter('bt_bb_latest_posts_output', function($output, $atts) {
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
function shortcode_lang_slugs() {
    if ( ! function_exists( 'pll_the_languages' ) ) {
        return '';
    }
    $args = [
        'raw'                    => 1,
        'hide_if_empty'          => 0,
        'hide_if_no_translation' => 0,
    ];

    $languages = pll_the_languages( $args );
    $links     = [];

    if ( ! empty( $languages ) ) {
        foreach ( $languages as $lang ) {
            $url = $lang['url'];
            $active_class = $lang['current_lang'] ? 'active-lang' : '';
            $links[] = sprintf(
                '<a href="%s" class="%s">%s</a>',
                esc_url( $url ),
                esc_attr( $active_class ),
                esc_html( $lang['slug'] )
            );
        }
    }
    return '<div class="lang-switcher-slugs">' . implode( ' / ', $links ) . '</div>';
}
add_shortcode( 'lang_slugs', 'shortcode_lang_slugs' );

// hide sidebare on product page
add_filter( 'boldthemes_extra_class', function( $extra_class ) {
    if ( function_exists( 'is_product' ) && is_product() ) {
        BoldThemesFramework::$has_sidebar = false;
        $extra_class = array_filter( $extra_class, function( $class ) {
            return strpos( $class, 'btWithSidebar' ) === false;
        });
        $extra_class[] = 'btNoSidebar';
    }
    return $extra_class;
});

// display Flavors on product page
add_action( 'woocommerce_before_add_to_cart_form', 'display_products_by_flavor' );
function display_products_by_flavor() {
    global $product;
    
    $taxonomy = 'pa_flavors';
    $terms = wc_get_product_terms( $product->get_id(), $taxonomy, array( 'fields' => 'all' ) );
    
    if ( empty( $terms ) ) {
        return;
    }
    
    // Получаем названия флейворов
    $term_ids = wp_list_pluck( $terms, 'term_id' );
    $term_names = wp_list_pluck( $terms, 'name' );
    $flavor_title = implode( ', ', $term_names );
    
    // Получаем товары с таким же атрибутом (без текущего)
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 10,
        'post__not_in'   => array( $product->get_id() ),
        'tax_query'      => array(
            array(
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $term_ids,
            ),
        ),
    );
    
    $related_products = new WP_Query( $args );
    
    echo '<div class="linked-products flavors-products-list">';
    echo '<h3> Flowers: "' . esc_html( $flavor_title ) . '"</h3>';
    echo '<ul class="linked-products-list">';
    
    // Сначала выводим текущий товар
    $image = $product->get_image( 'thumbnail' );
    $title = $product->get_name();
    $link  = get_permalink( $product->get_id() );
    
    echo '<li class="linked-product-item current-flavor">';
    echo '<span title="' . esc_attr( $title ) . '">' . $image . '</span>';
    echo '</li>';
    
    // Затем остальные товары
    while ( $related_products->have_posts() ) {
        $related_products->the_post();
        $related_product = wc_get_product( get_the_ID() );
        
        $image = $related_product->get_image( 'thumbnail' );
        $title = $related_product->get_name();
        $link  = get_permalink();
        
        echo '<li class="linked-product-item">';
        echo '<a href="' . esc_url( $link ) . '" title="' . esc_attr( $title ) . '">';
        echo $image;
        echo '</a>';
        echo '</li>';
    }
    
    wp_reset_postdata();
    
    echo '</ul>';
    echo '</div>';
}