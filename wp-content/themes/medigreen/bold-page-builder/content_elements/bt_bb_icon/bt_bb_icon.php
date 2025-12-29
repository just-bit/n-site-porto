<?php

class bt_bb_icon extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'icon'         => '',
			'text'         => '',
			'url'          => '',
			'url_title'    => '',
			'target'       => '',
			'color_scheme' => '',
			'style'        => '',
			'size'         => '',
			'shape'        => '',
			'align'        => ''
		) ), $atts, $this->shortcode ) );

		$class = array( $this->shortcode );
		$data_override_class = array();

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
		if ( $color_scheme_colors ) $el_style .= '; --icon-primary-color:' . $color_scheme_colors[0] . '; --icon-secondary-color:' . $color_scheme_colors[1] . ';';
		if ( $color_scheme != '' ) $class[] = $this->prefix . 'color_scheme_' .  $color_scheme_id;

		if ( $style != '' ) {
			$class[] = $this->prefix . 'style' . '_' . $style;
		}
		
		if ( method_exists( get_parent_class( $this ), 'responsive_override_class' ) ) {
			$this->responsive_data_override_class(
				$class, $data_override_class,
				array(
					'prefix' => $this->prefix,
					'param' => 'size',
					'value' => $size
				)
			);
		} else {
			if ( $size != '' ) {
				$class[] = $this->prefix . 'size' . '_' . $size;
			}
		}
		
		if ( $shape != '' ) {
			$class[] = $this->prefix . 'shape' . '_' . $shape;
		}
		
		if ( method_exists( get_parent_class( $this ), 'responsive_override_class' ) ) {
			$this->responsive_data_override_class(
				$class, $data_override_class,
				array(
					'prefix' => $this->prefix,
					'param' => 'align',
					'value' => $align
				)
			);
		} else {
			if ( $align != '' ) {
				$class[] = $this->prefix . 'align' . '_' . $align;
			}
		}

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}

		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		$output = $this->get_html( $icon, $text, $url, $url_title, $target );
		
		

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . ' data-bt-override-class="' . htmlspecialchars( json_encode( $data_override_class, JSON_FORCE_OBJECT ), ENT_QUOTES, 'UTF-8' ) . '">' . $output . '</div>';

		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	static function get_html( $icon, $text = '', $url = '', $url_title = '', $target = '' ) {

		$icon_set = substr( $icon, 0, -5 );
		$icon = substr( $icon, -4 );

		$link = bt_bb_get_url( $url );

		if ( $text != '' ) {
			if ( $url_title == '' ) $url_title = strip_tags($text);
			$text = '<span class="bt_bb_icon_text">' . $text . '</span>';
		}
		
		$url_title_attr = '';
		
		if ( $url_title != '' ) {
			$url_title_attr = ' title="' . esc_url_raw( $url_title ) . '"';
		}
		
		if ( $link == '' ) {
			$ico_tag = 'span' . ' ';
			$ico_tag_end = 'span';	
		} else {
			$target_attr = 'target="_self"';
			if ( $target != '' ) {
				$target_attr = ' ' . 'target="' . ( $target ) . '"';
			}
			$ico_tag = 'a href="' . esc_attr( $link ) . '"' . ' ' . $target_attr . ' ' . $url_title_attr . ' ';
			$ico_tag_end = 'a';
		}

		return '<' . $ico_tag . ' data-ico-' . esc_attr( $icon_set ) . '="&#x' . esc_attr( $icon ) . ';" class="bt_bb_icon_holder"></' . $ico_tag_end . '>' . wp_kses_post( $text ) . '';
	}

	function map_shortcode() {

		$color_scheme_arr = bt_bb_get_color_scheme_param_array();

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Icon', 'medigreen' ), 'description' => esc_html__( 'Single icon with link', 'medigreen' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'icon', 'type' => 'iconpicker', 'heading' => esc_html__( 'Icon', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'text', 'type' => 'textfield', 'heading' => esc_html__( 'Text', 'medigreen' ) ),
				array( 'param_name' => 'url', 'type' => 'textfield', 'heading' => esc_html__( 'URL', 'medigreen' ) ),
				array( 'param_name' => 'url_title', 'type' => 'textfield', 'heading' => esc_html__( 'Mouse hover title', 'medigreen' ) ),
				array( 'param_name' => 'target', 'type' => 'dropdown', 'heading' => esc_html__( 'Target', 'medigreen' ),
					'value' => array(
						esc_html__( 'Self (open in same tab)', 'medigreen' ) => '_self',
						esc_html__( 'Blank (open in new tab)', 'medigreen' ) => '_blank',
					)
				),
				array( 'param_name' => 'align', 'type' => 'dropdown', 'heading' => esc_html__( 'Alignment', 'medigreen' ), 'responsive_override' => true,
					'value' => array(
						esc_html__( 'Inherit', 'medigreen' ) 	=> 'inherit',
						esc_html__( 'Left', 'medigreen' ) 	=> 'left',
						esc_html__( 'Right', 'medigreen' ) 	=> 'right'
					)
				),
				array( 'param_name' => 'size', 'type' => 'dropdown', 'default' => 'small', 'heading' => esc_html__( 'Size', 'medigreen' ), 'preview' => true, 'responsive_override' => true,
					'value' => array(
						esc_html__( 'Extra small', 'medigreen' ) 	=> 'xsmall',
						esc_html__( 'Small', 'medigreen' ) 		=> 'small',
						esc_html__( 'Normal', 'medigreen' ) 		=> 'normal',
						esc_html__( 'Large', 'medigreen' ) 		=> 'large',
						esc_html__( 'Extra large', 'medigreen' ) 	=> 'xlarge'
					)
				),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Color scheme', 'medigreen' ), 'value' => $color_scheme_arr, 'preview' => true, 'group' => esc_html__( 'Design', 'medigreen' ) ),
				array( 'param_name' => 'style', 'type' => 'dropdown', 'heading' => esc_html__( 'Style', 'medigreen' ), 'preview' => true, 'group' => esc_html__( 'Design', 'medigreen' ),
					'value' => array(
						esc_html__( 'Outline', 'medigreen' ) 						=> 'outline',
						esc_html__( 'Filled', 'medigreen' ) 						=> 'filled',
						esc_html__( 'Filled + Transparent Border', 'medigreen' ) 	=> 'transparent_border',
						esc_html__( 'Filled + Solid Border', 'medigreen' ) 		=> 'solid_border',
						esc_html__( 'Borderless', 'medigreen' ) 					=> 'borderless'
					)
				),
				array( 'param_name' => 'shape', 'type' => 'dropdown', 'heading' => esc_html__( 'Shape', 'medigreen' ), 'group' => esc_html__( 'Design', 'medigreen' ),
					'value' => array(
						esc_html__( 'Circle', 'medigreen' ) 			=> 'circle',
						esc_html__( 'Square', 'medigreen' ) 			=> 'square',
						esc_html__( 'Rounded Square', 'medigreen' ) 	=> 'round'
					)
				)
			)
		) );
	}
}