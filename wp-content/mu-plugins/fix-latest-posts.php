<?php
/**
 * Plugin Name: Fix bt_bb_latest_posts warning
 */
add_filter('wp_get_attachment_image_src', function($image, $attachment_id, $size) {
    return $image ?: [''];
}, 10, 3);