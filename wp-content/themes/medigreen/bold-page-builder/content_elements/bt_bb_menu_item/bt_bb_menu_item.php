<?php

class bt_bb_menu_item extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'menu_item_image'      					=> '',
			'menu_item_supertitle'        			=> '',
			'menu_item_title'        				=> '',
			'menu_item_url'        					=> '',
			'menu_item_url_title'        			=> '',
			'menu_item_details'      				=> '',
			'menu_item_price'      					=> '',
			'color_scheme'      					=> ''
		) ), $atts, $this->shortcode ) );

		$class = array( $this->shortcode );

		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
		
		$color_scheme_id = NULL;
		if ( is_numeric ( $color_scheme ) ) {
			$color_scheme_id = $color_scheme;
		} else if ( $color_scheme != '' ) {
			$color_scheme_id = bt_bb_get_color_scheme_id( $color_scheme );
		}
		$color_scheme_colors = bt_bb_get_color_scheme_colors_by_id( $color_scheme_id - 1 );
		if ( $color_scheme_colors ) $el_style .= '; --menu-item-primary-color:' . $color_scheme_colors[0] . '; --menu-item-secondary-color:' . $color_scheme_colors[1] . ';';
		if ( $color_scheme != '' ) $class[] = $this->prefix . 'color_scheme_' .  $color_scheme_id;

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr( $el_id ) . '"';
		}

		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}

		
		if ( $menu_item_image != '' ) {
			$post_image = get_post( $menu_item_image );
			if ( $post_image == '' ) return;
		
			$image = wp_get_attachment_image_src( $menu_item_image, "boldthemes_small_square" );
			$caption = $post_image->post_excerpt;
			
			$image = $image[0];
			if ( $caption == '' ) {
				$caption = $menu_item_title;
			}
			$menu_item_image = '<img src="' . esc_attr( $image ) . '" alt="' . esc_attr( $caption ) . '" title="' . esc_attr( $caption ) . '" >';
		}
		
		$output = '<div class="' . esc_attr( implode( ' ', $class ) ) . '"' . $style_attr . ' ' . $id_attr . '>';
			
			// IMAGE
			if ( $menu_item_image != '' ) {
				$output .= '<div class="' . esc_attr( $this->shortcode . '_image' ) . '">';
					if ( $menu_item_image != '' ) $output .= $menu_item_image;
				$output .= '</div>';
			}

			// CONTENT
			$output .= '<div class="' . esc_attr( $this->shortcode . '_content' ) . '">';
				if ( $menu_item_supertitle != '' ) $output .= '<span class="' . esc_attr( $this->shortcode . '_supertitle' ) . '">' . wp_kses_post( $menu_item_supertitle ) . '</span>';
				if ( $menu_item_title != '' ) $output .= '<span class="' . esc_attr( $this->shortcode . '_title' ) . '">' . wp_kses_post( $menu_item_title ) . ''  . '</span>';
			$output .= '</div>';


			// DETAILS
			$menu_item_details_arr = array();
			if ( $menu_item_details != '' ) {
				$menu_item_details_arr = explode( ';', $menu_item_details );   
			}

			// PRICES
			$menu_item_price_arr = array();
			if ( $menu_item_price != '' ) {
				$menu_item_price_arr   = explode( ';', $menu_item_price );   
			}
			
			if ( !empty($menu_item_details_arr) ){
				$p = 0;
				foreach ( $menu_item_details_arr as $menu_item_details ){
					$output .= '<div class="' . esc_attr( $this->shortcode . '_price_details' ) . '">';
						if ( $menu_item_details != '' ) $output .= '<span class="' . esc_attr( $this->shortcode . '_details' ) . '">' . wp_kses_post( $menu_item_details ) . '</span>';	
						if ( isset($menu_item_price_arr[$p]) &&  $menu_item_price_arr[$p] != '' ) $output .= '<span class="' . esc_attr( $this->shortcode . '_price' ) . '">' . wp_kses_post( $menu_item_price_arr[$p] ) . '</span>';
					$output .= '</div>';
					$p++;
				}
			}


			if ( $menu_item_url != '' && $menu_item_url_title != '' ) {
				$link = bt_bb_get_url( $menu_item_url );
				$button_color_scheme = $color_scheme == '' ? 'accent-dark-skin' : $color_scheme;
				$output .= '<span class="' . esc_attr( $this->shortcode . '_button' ) . '">';
				$output .= '<span class="' . esc_attr( $this->shortcode . '_button_inner' ) . '">';
					// $output .= wp_kses_post( $menu_item_url_title );
					$output .= do_shortcode( '[bt_bb_button text="' . esc_attr( $menu_item_url_title ) . '" url="' . esc_attr( $link ) . '" style="outline" size="small" width="fulla" color_scheme="' . $button_color_scheme . '" ignore_fe_editor="true"]' );;
				$output .= '</spaalign-content: center;n>';
			}
			
		$output .= '</div>';

		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {
		
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();
		
		bt_bb_map( $this->shortcode, array( 'name' => __( 'Menu Item', 'medigreen' ), 'description' => __( 'Menu item with variations and prices', 'medigreen' ), 'container' => 'vertical', 'accept' => array( 'bt_bb_button' => true, 'bt_bb_icon' => true, 'bt_bb_separator' => true, 'bt_bb_text' => true, 'bt_bb_headline' => true ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'menu_item_image', 'type' => 'attach_image', 'heading' => __( 'Image', 'medigreen' ), 'preview' => true ),	
				array( 'param_name' => 'menu_item_supertitle', 'type' => 'textfield', 'heading' => __( 'Supertitle', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'menu_item_title', 'type' => 'textfield', 'heading' => __( 'Title', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'menu_item_details', 'type' => 'textfield', 'heading' => __( 'Variations', 'medigreen' ), 'description' => __( 'Type values separated by ; related to entered prices below (ex. 10g;25g;30g)', 'medigreen' )),
				array( 'param_name' => 'menu_item_price', 'type' => 'textfield', 'heading' => __( 'Price', 'medigreen' ), 'description' => __( 'Type prices separated by ; (ex. $40;$75;$99)', 'medigreen' )),			
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => __( 'Color scheme', 'medigreen' ), 'value' => $color_scheme_arr, 'preview' => true ),
				array( 'param_name' => 'menu_item_url', 'type' => 'textfield', 'heading' => __( 'Button URL', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'menu_item_url_title', 'type' => 'textfield', 'heading' => __( 'Button title', 'medigreen' ), 'preview' => true )
			)
		) );
	}
}