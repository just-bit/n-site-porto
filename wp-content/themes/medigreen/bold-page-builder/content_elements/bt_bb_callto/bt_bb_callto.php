<?php

class bt_bb_callto extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'icon'         => '',
			'title'        => '',
			'subtitle'     => '',
			'url'          => '',
			'target'       => '',
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
		if ( $color_scheme_colors ) $el_style .= '; --callto-primary-color:' . $color_scheme_colors[0] . '; --callto-secondary-color:' . $color_scheme_colors[1] . ';';
		if ( $color_scheme != '' ) $class[] = $this->prefix . 'color_scheme_' .  $color_scheme_id;

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}
		
		if ( $target == '' ) {
			$target = '_self';
		}

		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		$output = $this->get_html( $icon, $title, $subtitle, $url, $target );

		$output = '<div' . $id_attr . ' class="' . esc_attr( implode( ' ', $class ) ) . '"' . $style_attr . '>' . ( $output ) . '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function get_html( $icon, $title, $subtitle, $url, $target ) {

		if ( $url == '' ) {
			$url = '#';
		}

		// TITLE
		$content_output = '';
		$content_output = '<div class="' . esc_attr( $this->shortcode . '_content' ) . '"><div class="' . esc_attr( $this->shortcode . '_title' ) . '">' . wp_kses_post( $title ) . '</div><div class="' . esc_attr( $this->shortcode . '_subtitle' ) . '">' . wp_kses_post( nl2br( $subtitle ) ) . '</div></div>';


		// ICON
		$icon_output = '';
		$icon_output = '<div class="' . esc_attr( $this->shortcode . '_icon' ) . '"></div>';
		


		// URL
		$link = bt_bb_get_url( $url );


		// ELEMENT
		$output = '<a href="' . esc_url( $link ) . '" target="' . esc_attr( $target ) . '" class="' . esc_attr( $this->shortcode . '_box' ) . '" title="' . esc_attr( $title ) . '">';
			if ( $icon == '' ) {
				$output .= $content_output . $icon_output;
			} else {
				$output .= bt_bb_icon::get_html( $icon, '', '', '' ) . $content_output . $icon_output;
			}
		$output .= '</a>';

		return $output;
	}

	function map_shortcode() {

		$color_scheme_arr = bt_bb_get_color_scheme_param_array();

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Call To Action', 'medigreen' ), 'description' => esc_html__( 'Icon with title and link', 'medigreen' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'icon', 'type' => 'iconpicker', 'heading' => esc_html__( 'Icon', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'title', 'type' => 'textfield', 'heading' => esc_html__( 'Title', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'subtitle', 'type' => 'textfield', 'heading' => esc_html__( 'Subtitle', 'medigreen' ) ),
				array( 'param_name' => 'url', 'type' => 'textfield', 'heading' => esc_html__( 'URL', 'medigreen' ) ),
				array( 'param_name' => 'target', 'type' => 'dropdown', 'heading' => esc_html__( 'Target', 'medigreen' ),
					'value' => array(
						esc_html__( 'Self (open in same tab)', 'medigreen' ) => '_self',
						esc_html__( 'Blank (open in new tab)', 'medigreen' ) => '_blank',
					)
				),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Color scheme', 'medigreen' ), 'value' => $color_scheme_arr, 'preview' => true )
			) 
		) );
	}
}