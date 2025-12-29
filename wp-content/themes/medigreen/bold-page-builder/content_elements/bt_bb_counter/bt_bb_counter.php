<?php

class bt_bb_counter extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'number'   		=> '',
			'text'   		=> '',
			'size'     		=> '',
			'color_scheme' 	=> ''
		) ), $atts, $this->shortcode ) );
		
		$class = array();
		
		$class[] = 'bt_bb_counter_holder';

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr( $el_id ) . '"';
		}
		
		$this->responsive_data_override_class(
			$class, $data_override_class,
			array(
				'prefix' => $this->prefix,
				'param' => 'size',
				'value' => $size
			)
		);

		$color_scheme_id = NULL;
		if ( is_numeric ( $color_scheme ) ) {
			$color_scheme_id = $color_scheme;
		} else if ( $color_scheme != '' ) {
			$color_scheme_id = bt_bb_get_color_scheme_id( $color_scheme );
		}
		$color_scheme_colors = bt_bb_get_color_scheme_colors_by_id( $color_scheme_id - 1 );
		if ( $color_scheme_colors ) $el_style .= '; --counter-primary-color:' . $color_scheme_colors[0] . '; --counter-secondary-color:' . $color_scheme_colors[1] . ';';
		if ( $color_scheme != '' ) $class[] = $this->prefix . 'color_scheme_' .  $color_scheme_id;	

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}

		$class = apply_filters( $this->shortcode . '_class', $class, $atts );
		$class_attr = implode( ' ', $class );
		
		if ( $el_class != '' ) {
			$class_attr = $class_attr . ' ' . $el_class;
		}

		$output = '';
		$output .= '<div' . $id_attr . ' class="' . esc_attr( $class_attr ) . '"' . $style_attr . ' data-bt-override-class="' . htmlspecialchars( json_encode( $data_override_class, JSON_FORCE_OBJECT ), ENT_QUOTES, 'UTF-8' ) . '">';
			$output .= '<span class="bt_bb_counter animate" data-digit-length="' . strlen( $number ) . '">';			
				for ( $i = 0; $i < strlen( $number ); $i++ ) {
					
					$output .= '<span class="onedigit p' . ( strlen( $number ) - $i ) . ' d' . $number[ $i ] . '" data-digit="' . esc_attr( $number[ $i ] ) . '">';
					
						if ( ctype_digit( $number[ $i ] ) ) {
							for ( $j = 0; $j <= 9; $j++ ) {
								$output .= '<span class="n' . $j . '">' . $j . '</span>';
							}
							$output .= '<span class="n0">0</span>';				
						} else {
							$output .= '<span class="t">' . $number[ $i ] . '</span>';	
						}
					
					$output .= '</span>';
				}			
			$output .= '</span>';
			$output .= '<span class="bt_bb_counter_text">' . wp_kses_post( nl2br( $text ) ) . '</span>';
		$output .= '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );
			
		return $output;
	}

	function map_shortcode() {

		$color_scheme_arr = bt_bb_get_color_scheme_param_array();

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Counter', 'medigreen' ), 'description' => esc_html__( 'Animated counter', 'medigreen' ),  
			'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'number', 'type' => 'textfield', 'heading' => esc_html__( 'Number', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'text', 'type' => 'textfield', 'heading' => esc_html__( 'Text', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'size', 'default' => 'small', 'type' => 'dropdown', 'heading' => esc_html__( 'Size', 'medigreen' ), 'preview' => true,
					 'responsive_override' => true, 'value' => array(
						esc_html__( 'Extra small', 'medigreen' ) 	=> 'xsmall',
						esc_html__( 'Small', 'medigreen' ) 		=> 'small',
						esc_html__( 'Normal', 'medigreen' ) 		=> 'normal',
						esc_html__( 'Large', 'medigreen' ) 		=> 'large',
						esc_html__( 'Extra large', 'medigreen' ) 	=> 'xlarge'
				) ),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Color scheme', 'medigreen' ), 'value' => $color_scheme_arr, 'preview' => true )
				)
		) );

	}
}