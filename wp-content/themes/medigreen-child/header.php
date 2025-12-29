<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php boldthemes_theme_data(); ?>>

<head>

    <?php

    boldthemes_set_override();
    global $boldthemes_page_options;
    boldthemes_header_init();
    boldthemes_header_meta();

    $body_style = '';

    $page_background = boldthemes_get_option('page_background');
    if ($page_background) {
        if (is_numeric($page_background)) {
            $page_background = wp_get_attachment_image_src($page_background, 'full');
            $page_background = $page_background[0];
        }
        $body_style = ' style="background-image:url(' . $page_background . ')"';
    }

    $header_extra_class = '';

    if (boldthemes_get_option('boxed_menu')) {
        $header_extra_class .= 'gutter ';
    }

    /* Page overlay */

    wp_register_script('bt-js-overlay-script', '');
    wp_enqueue_script('bt-js-overlay-script');

    $overlay_page_slug = boldthemes_get_option('overlay_slug');

    if ($overlay_page_slug != '') {
        $overlay_page_expiry = boldthemes_get_option('overlay_expiry');
        $overlay_page_expiry = intval($overlay_page_expiry) > 0 ? $overlay_page_expiry : '365';
        $page_id = boldthemes_get_id_by_slug($overlay_page_slug);

        if (is_multisite()) {
            $site = get_blog_details();
            $site_path = $site->path;
        } else {
            $site_path = '/';
        }

        if (!is_null($page_id)) {
            $page = get_post($page_id);
            $content = $page->post_content;
            $content = apply_filters('the_content', $content);
            $content = do_shortcode($content);
            $content = str_replace(array("\r\n", "\n", "\r"), ' ', $content);
            if (is_user_logged_in())
                $content = preg_replace('/data-edit_url="(.*?)"/s', 'data-edit_url="' . get_edit_post_link($page_id, '') . '"', $content);
            $content = '<div class="btOverlay" id="btOverlay" data-btoverlay_expiry = "' . $overlay_page_expiry . '" data-btoverlay_site_path = "' . $site_path . '"><div class="btOverlayInner">' . str_replace(']]>', ']]&gt;', $content) . '</div></div>';
            wp_add_inline_script('bt-js-overlay-script', '	btOverlayContent = "' . htmlentities($content) . '";');
        }
    } else {
        wp_add_inline_script('bt-js-overlay-script', '	btOverlayContent = "";');
    }

    wp_head(); ?>

</head>

<body <?php body_class(); ?> <?php echo wp_kses_post($body_style); ?>>
    <?php

    echo boldthemes_preloader_html();

    ?>

    <div class="btPageWrap" id="top">

        <div class="btVerticalHeaderTop">
            <?php if (has_nav_menu('primary')) { ?>
                <div class="btVerticalMenuTrigger">
                    <?php echo boldthemes_get_icon_html(array("icon" => "fa_f0c9", "url" => "#")); ?></div>
            <?php } ?>
            <div class="btLogoArea">
                <div class="logo">
                    <span>
                        <?php boldthemes_logo('header'); ?>
                    </span>
                </div><!-- /logo -->
            </div><!-- /btLogoArea -->


<div class="btVerticalHeaderTop_mob" style="align-items: center; gap: 20px; margin-left: auto;width: fit-content;height: 40px;">

    <div class="custom-search">
        <?php echo do_shortcode('[fibosearch]'); ?>
    </div>

    <?php if (class_exists('WooCommerce')): ?>
        <a class="header-custom-cart" data-ico-fa="" href="<?php echo wc_get_cart_url(); ?>"
            style="text-decoration: none; font-size: 18px;">
	        <?php
	        $count = WC()->cart->get_cart_contents_count();
	        if ( $count > 0 ) : ?>
		        <span class="count"><?php echo $count; ?></span>
	        <?php endif; ?>
        </a>
    <?php endif; ?>

      <a href="http://now420/shop/" target="_self" class="btButtonWidgetLink"><span class="btButtonWidgetText">Order tracking</span></a>

</div>

        </div>
        <header class="mainHeader btClear <?php echo esc_attr($header_extra_class); ?>">
            <div class="mainHeaderInner">
                <?php echo boldthemes_top_bar_html('top'); ?>
                <div class="btLogoArea menuHolder btClear">
                    <div class="port">
                        <?php if (has_nav_menu('primary')) { ?>
                            <div class="btHorizontalMenuTrigger">
                                &nbsp;<?php echo boldthemes_get_icon_html(array("icon" => "fa_f0c9", "url" => "#")); ?>
                            </div>
                        <?php } ?>
                        <div class="logo">
                            <span>
                                <?php boldthemes_logo('header'); ?>
                            </span>
                        </div><!-- /logo -->
                        <?php
                        $menu_type = boldthemes_get_option('menu_type');
                        if ($menu_type == 'horizontal-below-right' || $menu_type == 'horizontal-below-center' || $menu_type == 'horizontal-below-left' || $menu_type == 'vertical-left' || $menu_type == 'vertical-right') {
                            echo boldthemes_top_bar_html('logo');
                            echo '</div><!-- /port --></div><!-- /menuHolder -->';
                            echo '<div class="btBelowLogoArea btClear"><div class="port">';
                        }
                        ?>
                        <div class="menuPort">
                            <?php echo boldthemes_top_bar_html('menu'); ?>
                            <nav>
                                <?php boldthemes_nav_menu(); ?>
                            </nav>
                        </div><!-- .menuPort -->
                    </div><!-- /port -->
                </div><!-- /menuHolder / btBelowLogoArea -->
            </div><!-- / inner header for scrolling -->

            <!-- Secondary Menu Bar -->
            <div class="btSecondaryMenuBar">
                <div class="port">
                    <nav class="btSecondaryNav">
                        <?php
                        if ( has_nav_menu( 'secondary' ) ) {
                            wp_nav_menu( array(
                                'theme_location' => 'secondary',
                                'container' => false,
                                'depth' => 1
                            ));
                        }
                        ?>
                    </nav>
                </div>
            </div>

        </header><!-- /.mainHeader -->

        <div class="btContentWrap btClear">
            <?php
            $hide_headline = boldthemes_get_option('hide_headline');
            if (((!$hide_headline && !is_404()) || is_search())) {
                boldthemes_header_headline(array('breadcrumbs' => true));
            }
            ?>
            <?php if (BoldThemesFramework::$page_for_header_id != '' && !is_search()) { ?>
                <?php
                $content = get_post(BoldThemesFramework::$page_for_header_id);
                $top_content = !is_null($content) ? $content->post_content : '';
                if ($top_content != '') {
                    //$top_content = apply_filters( 'the_content', $top_content );
                    $top_content = do_shortcode($top_content);
                    $top_content = preg_replace('/data-edit_url="(.*?)"/s', 'data-edit_url="' . get_edit_post_link(BoldThemesFramework::$page_for_header_id, '') . '"', $top_content);
                    echo '<div class = "btBlogHeaderContent">' . str_replace(']]>', ']]&gt;', $top_content) . '</div>';
                }

                ?>
            <?php } ?>
            <div class="btContentHolder">

                <div class="btContent">
