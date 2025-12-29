<?php

class bt_bb_google_maps_location extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'latitude'  			=> '',
			'longitude' 			=> '',
			'icon'      			=> '',
			'background_image'      => '',
			'lazy_load'  			=> 'no'
		) ), $atts, $this->shortcode ) );

		 $class_master = 'bt_bb_map_location';
		
		$class = array( $this->shortcode, $class_master );
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
		
		if ( $content == '' ) {
			$class[] = $this->shortcode . '_without_content';
			$class[] = $class_master . '_without_content';
		}
		
		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr( $el_id ) . '"';
		}

		$background_data_attr = '';
		
		if ( $background_image != '' ) {
			$background_image = wp_get_attachment_image_src( $background_image, 'full' );
			$background_image_url = $background_image[0];
			if ( $lazy_load == 'yes' ) {
				$blank_image_src = BT_BB_Root::$path . 'img/blank.gif';
				$el_style .= 'background-image:url(\'' . $blank_image_src . '\');';
				$background_data_attr .= ' data-background_image_src=\'' . $background_image_url . '\'';
				$class[] = 'btLazyLoadBackground';
			} else {
				$el_style .= 'background-image:url(\'' . $background_image_url . '\');';
			}
				
			$class[] = 'bt_bb_google_maps_background_image';
		}

		$style_attr = '';

		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}

		if ( $icon != '' ) {
			$icon = wp_get_attachment_image_src( $icon, 'full' );
			$icon = $icon[0];
		}
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '" ' . $style_attr . $background_data_attr . ' data-lat="' . esc_attr( $latitude ) . '" data-lng="' . esc_attr( $longitude ) . '" data-icon="' . esc_attr( $icon ) . '"><div class="bt_bb_google_maps_location_elements">' . ( do_shortcode( $content ) ) . '</div></div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Google Maps Location', 'medigreen' ), 'description' => esc_html__( 'Google Maps location', 'medigreen' ), 'container' => 'vertical', 'accept' => array( 'bt_bb_headline' => true, 'bt_bb_text' => true, 'bt_bb_button' => true, 'bt_bb_icon' => true, 'bt_bb_service' => true, 'bt_bb_service_icon' => true, 'bt_bb_separator' => true ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'latitude', 'type' => 'textfield', 'heading' => esc_html__( 'Latitude', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'longitude', 'type' => 'textfield', 'heading' => esc_html__( 'Longitude', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'icon', 'type' => 'attach_image', 'heading' => esc_html__( 'Icon', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'background_image', 'type' => 'attach_image',  'preview' => true, 'heading' => esc_html__( 'Background image', 'medigreen' ) ),
				array( 'param_name' => 'lazy_load', 'type' => 'dropdown', 'default' => 'yes', 'heading' => esc_html__( 'Lazy load this image', 'medigreen' ),
					'value' => array(
						esc_html__( 'No', 'medigreen' )  => 'no',
						esc_html__( 'Yes', 'medigreen' ) => 'yes'
					)
				)
			)
		) );
	}
}