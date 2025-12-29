<?php

class bt_bb_progress_bar extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'text'        		=> '',
			'percentage'        => '',
			'color_scheme' 		=> '',
			'size'        		=> '',
			'align'        		=> '',
			'style'        		=> '',
			'shape'        		=> ''
		) ), $atts, $this->shortcode ) );

		$class = array( $this->shortcode );

		if ( $text == '' ) {
			$text = $percentage . "%";
		}
		
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
		if ( $color_scheme_colors ) $el_style .= '; --progress-bar-primary-color:' . $color_scheme_colors[0] . '; --progress-bar-secondary-color:' . $color_scheme_colors[1] . ';';
		if ( $color_scheme != '' ) $class[] = $this->prefix . 'color_scheme_' .  $color_scheme_id;
		
		$this->responsive_data_override_class(
			$class, $data_override_class,
			array(
				'prefix' => $this->prefix,
				'param' => 'align',
				'value' => $align
			)
		);
		
		$this->responsive_data_override_class(
			$class, $data_override_class,
			array(
				'prefix' => $this->prefix,
				'param' => 'size',
				'value' => $size
			)
		);

		if ( $style != '' ) {
			$class[] = $this->prefix . 'style' . '_' . $style;
		}		

		if ( $shape != '' ) {
			$class[] = $this->prefix . 'shape' . '_' . $shape;
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		$content = do_shortcode( $content );

		$output = '';

		$output .= '<div' . $id_attr . ' class="' . esc_attr( implode( ' ', $class ) ) . '"' . $style_attr . ' data-bt-override-class="' . htmlspecialchars( json_encode( $data_override_class, JSON_FORCE_OBJECT ), ENT_QUOTES, 'UTF-8' ) . '">
						<div class="bt_bb_progress_bar_text"><span>' . wp_kses_post( $text ) . '</span></div>
						<div class="bt_bb_progress_bar_content">
							<div class="bt_bb_progress_bar_bg"><div class="bt_bb_progress_bar_inner animate" style="' . esc_attr( 'width:' . $percentage . '%' ) . '"></div></div>
						</div>
					</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {
		
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();
		
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Progress bar', 'medigreen' ), 'description' => esc_html__( 'Progress bar', 'medigreen' ), 'container' => 'vertical', 'accept' => false, 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'percentage', 'type' => 'textfield', 'heading' => esc_html__( 'Percentage', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'text', 'type' => 'textfield', 'heading' => esc_html__( 'Text', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'size', 'type' => 'dropdown', 'heading' => esc_html__( 'Size', 'medigreen' ), 'preview' => true,
					 'responsive_override' => true, 'value' => array(
						esc_html__( 'Normal', 'medigreen' ) 	=> 'normal',
						esc_html__( 'Small', 'medigreen' ) 	=> 'small'
					)
				),
				array( 'param_name' => 'align', 'type' => 'dropdown', 'heading' => esc_html__( 'Align', 'medigreen' ), 'preview' => true,
					 'responsive_override' => true, 'value' => array(
						esc_html__( 'Inherit', 'medigreen' ) 		=> 'inherit',
						esc_html__( 'Left', 'medigreen' ) 		=> 'left',
						esc_html__( 'Right', 'medigreen' ) 		=> 'right',
						esc_html__( 'Center', 'medigreen' ) 		=> 'center'
					)
				),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Color scheme', 'medigreen' ), 'value' => $color_scheme_arr, 'preview' => true, 'group' => esc_html__( 'Design', 'medigreen' ) ),
				array( 'param_name' => 'style', 'type' => 'dropdown', 'heading' => esc_html__( 'Style', 'medigreen' ), 'preview' => true, 'group' => esc_html__( 'Design', 'medigreen' ),
					'value' => array(
						esc_html__( 'Filled', 'medigreen' ) 	=> 'filled',
						esc_html__( 'Line', 'medigreen' ) 	=> 'line'
					)
				),
				array( 'param_name' => 'shape', 'type' => 'dropdown', 'heading' => esc_html__( 'Shape', 'medigreen' ), 'group' => esc_html__( 'Design', 'medigreen' ),
					'value' => array(
						esc_html__( 'Square', 'medigreen' ) 	=> 'square',
						esc_html__( 'Rounded', 'medigreen' ) 	=> 'rounded',
					)
				)
			)
		) );
	}
}