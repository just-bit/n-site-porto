<?php

class bt_bb_working_hours extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'wh_content'	=> ''
		) ), $atts, $this->shortcode ) );

		$class = array( $this->shortcode );

		if ( $el_class != '' ) {
			$class[] = $el_class;
		}

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr( $el_id ) . '"';
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}

		$class = apply_filters( $this->shortcode . '_class', $class, $atts );
		
		
		$output_inner = '';
		$items_arr = preg_split( '/$\R?^/m', $wh_content );
		
		foreach ( $items_arr as $item ) {
			$output_inner .= '<div class="' . esc_attr( $this->shortcode ) . '_row">';
			$item_arr = explode( ';', $item );
			$output_inner .= '<div class="' . esc_attr( $this->shortcode ) . '_title">' . wp_kses_post( $item_arr[0] ) . '</div>';
			unset( $item_arr[0] );
		
			foreach ( $item_arr as $inner_item ) {
				$output_inner .= '<div class="' . esc_attr( $this->shortcode ) . '_content">' . wp_kses_post( $inner_item ). '</div>';
			}

			$output_inner .= '</div>';
		}
		
		$output = '';
		$output .= '<div class="' . esc_attr( $this->shortcode ) . '_inner ">';
			$output .= $output_inner;
		$output .= '</div>';
		
		
		$output = '<div' . $id_attr . ' class="' . esc_attr( implode( ' ', $class ) ) . '"' . $style_attr . '>' . $output . '</div>';

		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );
			
		return $output;

	}

	function map_shortcode() {

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Working Hours', 'medigreen' ), 'description' => esc_html__( 'Working hours list', 'medigreen' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'wh_content', 'type' => 'textarea', 'heading' => esc_html__( 'Text', 'medigreen' ), 'description' => __( 'Type working day separated with ; and than working hours (eg. Monday-Friday;8:00 AM - 4:30 PM). In order to show multiple days, separate sentences by new lines.', 'medigreen' ) )
			)
		) );
	}
}