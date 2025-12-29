<?php

class bt_bb_service extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'ai_prompt'    		=> '',
			'icon'         		=> '',
			'title'        		=> '',
			'html_tag'     		=> 'div',
			'text'         		=> '',
			'url'          		=> '',
			'target'       		=> '',
			'color_scheme' 		=> '',
			'style'        		=> '',
			'size'         		=> '',
			'shape'        		=> '',
			'align'        		=> '',
			'supertitle'   		=> '',
			'title_size'   		=> '',
			'supertitle_size'   => ''
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
		if ( $color_scheme_colors ) $el_style .= '; --service-primary-color:' . $color_scheme_colors[0] . '; --service-secondary-color:' . $color_scheme_colors[1] . ';';
		if ( $color_scheme != '' ) $class[] = $this->prefix . 'color_scheme_' .  $color_scheme_id;		

		if ( $style != '' ) {
			$class[] = $this->prefix . 'style' . '_' . $style;
		}

		if ( $title == '' ) {
			$class[] = "btNoTitle";
		}

		if ( $text == '' ) {
			$class[] = "btNoText";
		}

		if ( $title_size != '' ) {
			$class[] = $this->prefix . 'title_size' . '_' . $title_size;
		}

		if ( $supertitle_size != '' ) {
			$class[] = $this->prefix . 'supertitle_size' . '_' . $supertitle_size;
		}

		$this->responsive_data_override_class(
			$class, $data_override_class,
			array(
				'prefix' => $this->prefix,
				'param' => 'size',
				'value' => $size
			)
		);

		if ( $shape != '' ) {
			$class[] = $this->prefix . 'shape' . '_' . $shape;
		}

		$this->responsive_data_override_class(
			$class, $data_override_class,
			array(
				'prefix' => $this->prefix,
				'param' => 'align',
				'value' => $align
			)
		);
		
		$icon_html = bt_bb_icon::get_html( $icon, '', $url, $title, $target );


		$link = bt_bb_get_url( $url );


		$title_content = $title;

		if ( $link != '' ) {
			$title_content = '<a href="' . esc_url( $link ) . '" target="' . esc_attr( $target ) . '">' . $title . '</a>';
		}
		
		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		$output = '';

		if ( $icon != '' ) $output = $icon_html;
		
		$output .= '<div class="' . esc_attr( $this->shortcode ) . '_content">';
			if ( $supertitle != '' ) $output .= '<div class="' . esc_attr( $this->shortcode . '_content_supertitle' ) . '">' . wp_kses_post( nl2br( $supertitle ) ) . '</div>';
			if ( $title != '' ) $output .= '<'. $html_tag . ' class="' . esc_attr( $this->shortcode . '_content_title' ) . '">' .  $title_content  . '</'. $html_tag . '>';
			if ( $text != '' ) $output .= '<div class="' . esc_attr( $this->shortcode . '_content_text' ) . '">' . wp_kses_post( nl2br( $text ) ) . '</div>';
		$output .= '</div>';

		$output = '<div' . $id_attr . ' class="' . esc_attr( implode( ' ', $class ) ) . '"' . $style_attr . ' data-bt-override-class="' . htmlspecialchars( json_encode( $data_override_class, JSON_FORCE_OBJECT ), ENT_QUOTES, 'UTF-8' ) . '">' . ( $output ) . '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {

		$color_scheme_arr = bt_bb_get_color_scheme_param_array();

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Service', 'medigreen' ), 'description' => esc_html__( 'Icon with text (and AI help)', 'medigreen' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array(
					'param_name' => 'ai_prompt',
					'type' => 'ai_prompt',
					'target' =>
						array(
							'title' => array( 'alias' => 'title', 'title' => esc_html__( 'Title', 'medigreen' ) ),
							'text' => array( 'alias' => 'text', 'title' => esc_html__( 'Text', 'medigreen' ) ),
							'supertitle' => array( 'alias' => 'supertitle', 'title' => esc_html__( 'Supertitle', 'medigreen' ) ),
						),
					'system_prompt' => 'You are a copywriter and your GOAL is to help users generate website content. Based on the user prompt generate title and text for the website page.',
				),
				array( 'param_name' => 'icon', 'type' => 'iconpicker', 'heading' => esc_html__( 'Icon', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'supertitle', 'type' => 'textfield', 'heading' => esc_html__( 'Supertitle', 'medigreen' ) ),
				array( 'param_name' => 'title', 'type' => 'textfield', 'heading' => esc_html__( 'Title', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'html_tag', 'type' => 'dropdown', 'heading' => esc_html__( 'Title HTML tag', 'medigreen' ), 'preview' => true, 'default' => 'div',
					'value' => array(
						esc_html__( 'div', 'medigreen' ) => 'div',
						esc_html__( 'h1', 'medigreen' ) 	=> 'h1',
						esc_html__( 'h2', 'medigreen' ) 	=> 'h2',
						esc_html__( 'h3', 'medigreen' ) 	=> 'h3',
						esc_html__( 'h4', 'medigreen' ) 	=> 'h4',
						esc_html__( 'h5', 'medigreen' ) 	=> 'h5',
						esc_html__( 'h6', 'medigreen' ) 	=> 'h6'
				) ),
				array( 'param_name' => 'text', 'type' => 'textarea', 'heading' => esc_html__( 'Text', 'medigreen' ) ),
				array( 'param_name' => 'url', 'type' => 'textfield', 'heading' => esc_html__( 'URL', 'medigreen' ) ),
				array( 'param_name' => 'target', 'type' => 'dropdown', 'heading' => esc_html__( 'Target', 'medigreen' ),
					'value' => array(
						esc_html__( 'Self (open in same tab)', 'medigreen' ) => '_self',
						esc_html__( 'Blank (open in new tab)', 'medigreen' ) => '_blank',
					)
				),
				array( 'param_name' => 'size', 'type' => 'dropdown', 'heading' => esc_html__( 'Icon size', 'medigreen' ), 'preview' => true, 'group' => esc_html__( 'Design', 'medigreen' ), 'responsive_override' => true,
					'value' => array(
						esc_html__( 'Extra Small', 'medigreen' ) 	=> 'xsmall',
						esc_html__( 'Small', 'medigreen' ) 		=> 'small',
						esc_html__( 'Normal', 'medigreen' ) 		=> 'normal',
						esc_html__( 'Large', 'medigreen' ) 		=> 'large',
						esc_html__( 'Extra Large', 'medigreen' ) 	=> 'xlarge'
					)
				),
				array( 'param_name' => 'align', 'type' => 'dropdown', 'heading' => esc_html__( 'Icon alignment', 'medigreen' ), 'group' => esc_html__( 'Design', 'medigreen' ), 'responsive_override' => true,
					'value' => array(
						esc_html__( 'Inherit', 'medigreen' ) 	=> 'inherit',
						esc_html__( 'Left', 'medigreen' ) 	=> 'left',
						esc_html__( 'Right', 'medigreen' ) 	=> 'right'
					)
				),
				array( 'param_name' => 'title_size', 'type' => 'dropdown', 'heading' => esc_html__( 'Title size', 'medigreen' ), 'preview' => true, 'group' => esc_html__( 'Design', 'medigreen' ),
					'value' => array(
						esc_html__( 'Small', 'medigreen' ) 		=> 'small',
						esc_html__( 'Normal', 'medigreen' )	 	=> 'normal',
						esc_html__( 'Large', 'medigreen' ) 		=> 'large'
					)
				),
				array( 'param_name' => 'supertitle_size', 'type' => 'dropdown', 'heading' => esc_html__( 'Supertitle size', 'medigreen' ), 'preview' => true, 'group' => esc_html__( 'Design', 'medigreen' ),
					'value' => array(
						esc_html__( 'Default', 'medigreen' ) 		=> '',
						esc_html__( 'Small', 'medigreen' ) 		=> 'small',
						esc_html__( 'Normal', 'medigreen' )	 	=> 'normal',
						esc_html__( 'Large', 'medigreen' ) 		=> 'large'
					)
				),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Color scheme', 'medigreen' ), 'value' => $color_scheme_arr, 'preview' => true, 'group' => esc_html__( 'Design', 'medigreen' ) ),
				array( 'param_name' => 'style', 'type' => 'dropdown', 'heading' => esc_html__( 'Icon style', 'medigreen' ), 'preview' => true, 'group' => esc_html__( 'Design', 'medigreen' ),
					'value' => array(
						esc_html__( 'Outline', 'medigreen' ) 						=> 'outline',
						esc_html__( 'Filled', 'medigreen' ) 						=> 'filled',
						esc_html__( 'Filled + Transparent Border', 'medigreen' ) 	=> 'transparent_border',
						esc_html__( 'Filled + Solid Border', 'medigreen' ) 		=> 'solid_border',
						esc_html__( 'Borderless', 'medigreen' ) 					=> 'borderless'
					)
				),
				array( 'param_name' => 'shape', 'type' => 'dropdown', 'heading' => esc_html__( 'Icon shape', 'medigreen' ), 'group' => esc_html__( 'Design', 'medigreen' ),
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