<?php

class bt_bb_price_list extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'title'        => '',
			'currency'     => '',
			'price'        => '',
			'price_text'   => '',
			'subtitle'     => '',		
			'items'        => '',
			'image'  	   => '',
			'color_scheme' => ''
		) ), $atts, $this->shortcode ) );

		$class = array( $this->shortcode );

		if ( $el_class != '' ) {
			$class[] = $el_class;
		}

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr( $el_id ) . '"';
		}		

		$color_scheme_id = NULL;
		if ( is_numeric ( $color_scheme ) ) {
			$color_scheme_id = $color_scheme;
		} else if ( $color_scheme != '' ) {
			$color_scheme_id = bt_bb_get_color_scheme_id( $color_scheme );
		}
		$color_scheme_colors = bt_bb_get_color_scheme_colors_by_id( $color_scheme_id - 1 );
		if ( $color_scheme_colors ) $el_style .= '; --price-list-primary-color:' . $color_scheme_colors[0] . '; --price-list-secondary-color:' . $color_scheme_colors[1] . ';';
		if ( $color_scheme != '' ) $class[] = $this->prefix . 'color_scheme_' .  $color_scheme_id;

		$bg_style = '';
		$size = '';
		if ( $image != '' && is_numeric( $image ) ) {
			$post_image = get_post( $image );
			if ( $post_image == '' ) return;
			$size = " boldthemes_large_rectangle";
			$image = wp_get_attachment_image_src( $image, $size );
			$image = $image[0];
			
			$class[] = "btHasBgImage";
			$bg_style = "style = \"background-image:url('" . $image . "')\"";
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		$output = '<div class="' . esc_attr( $this->shortcode ) . '_content" ' . $bg_style . '>';
			$output .= '<div class="' . esc_attr( $this->shortcode ) . '_title">' . $title . '</div>';
			$output .= '<div class="' . esc_attr( $this->shortcode ) . '_price"><span class="' . esc_attr( $this->shortcode ) . '_currency">' . $currency . '</span><span class="' . esc_attr( $this->shortcode ) . '_amount">' . $price . '</span><span class="' . esc_attr( $this->shortcode ) . '_price_text">' . $price_text . '</span></div>';
			$output .= '<div class="' . esc_attr( $this->shortcode ) . '_subtitle">' . $subtitle . '</div>';
		$output .= '</div>';

		if ( $items != '' ) {
			$items_arr = preg_split( '/$\R?^/m', $items );
			$output .= '<ul>';
				foreach ( $items_arr as $item ) {
					if ( $item != '' ){
						$li_class	=	substr($item, 0, 1) == '+' ? 'included' : 'excluded';					
						$item		=	substr($item, 0, 1) == '+' ? ltrim($item, '+')   :  $item;

						$output .= '<li class="' .  esc_attr( $li_class ) . '">' .  wp_kses_post( $item ) . '</li>';
					}
				}
			$output .= '</ul>';
		}

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>' . $output . '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {

		$color_scheme_arr = bt_bb_get_color_scheme_param_array();			
		
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Price List', 'medigreen' ), 'description' => esc_html__( 'List of items with total price', 'medigreen' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'title', 'type' => 'textfield', 'heading' => esc_html__( 'Title', 'medigreen' ), 'preview' => true ),
				
				array( 'param_name' => 'currency', 'type' => 'textfield', 'heading' => esc_html__( 'Currency', 'medigreen' ), 'description' => __( 'Currency will display next to price value - on the left side.', 'medigreen' ) ),
				array( 'param_name' => 'price', 'type' => 'textfield', 'heading' => esc_html__( 'Price', 'medigreen' ) ),
				array( 'param_name' => 'price_text', 'type' => 'textfield', 'heading' => esc_html__( 'Price text', 'medigreen' ), 'description' => __( 'Price text will display next to price value - on the right side.', 'medigreen' ) ),
				array( 'param_name' => 'subtitle', 'type' => 'textfield', 'heading' => esc_html__( 'Subtitle', 'medigreen' ) ),
				array( 'param_name' => 'items', 'type' => 'textarea', 'heading' => esc_html__( 'Items', 'medigreen' ), 'description' => __( 'Type sentences separated by new line. In order to show what is included in price add + before text (ex. +Free drinks).', 'medigreen' ) ),
				array( 'param_name' => 'image', 'type' => 'attach_image', 'heading' => esc_html__( 'Background image', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Color scheme', 'medigreen' ), 'value' => $color_scheme_arr, 'preview' => true ),			
			)
		) );
	}
}