<?php

class bt_bb_single_event extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts', array(
			'event_day'        		=> '',
			'event_month'        	=> '',
			'event_title'        	=> '',
			'event_image'        	=> '',
			'event_description'     => ''
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
		
		$content = do_shortcode( $content );
	
		$output = '';

		$output .= '<div class="' . esc_attr( $this->shortcode ) . '">';
			if ( $event_image != '' ) $output .=  '<div class="' . esc_attr( $this->shortcode . '_content_image') . '">' . do_shortcode( '[bt_bb_image image="' . esc_attr( $event_image  ) . '" size="medium" ignore_fe_editor="true"]' ) . '</div>';

			$output .= '<div class="' . esc_attr( $this->shortcode . '_content' ) . '">';
				$output .= '<div class="' . esc_attr( $this->shortcode . '_date' ) . '">';
					if ( $event_month != '' ) $output .= '<div class="' . esc_attr( $this->shortcode . '_date_month' ) . '">' . wp_kses_post( $event_month ) . '</div>';
					if ( $event_day != '' ) $output .= '<div class="' . esc_attr( $this->shortcode . '_date_day' ) . '">' . wp_kses_post( $event_day ) . '</div>';
				$output .= '</div>';

				$output .= '<div class="' . esc_attr( $this->shortcode . '_details' ) . '">';
					if ( $event_title != '' ) $output .= '<div class="' . esc_attr( $this->shortcode . '_content_title' ) . '">' . wp_kses_post( $event_title ) . '</div>';
					if ( $event_description != '' ) $output .= '<div class="' . esc_attr( $this->shortcode . '_content_description' ) . '">' . wp_kses_post( nl2br( $event_description ) ) . '</div>';
					if ( $content != '' ) $output .= '<div class="' . esc_attr( $this->shortcode . '_content_inner' ) . '">' . ( $content ) . '</div>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output = '<div' . $id_attr . ' class="' . esc_attr( implode( ' ', $class ) ) . '"' . $style_attr . '>' . ( $output ) . '</div>';

		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {
		
		bt_bb_map( $this->shortcode, array( 'name' => __( 'Single Event', 'medigreen' ), 'description' => __( 'Single event with description & image', 'medigreen' ), 'container' => 'vertical', 'accept' => array( 'bt_bb_button' => true, 'bt_bb_icon' => true, 'bt_bb_separator' => true, 'bt_bb_text' => true, 'bt_bb_headline' => true ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				
				array( 'param_name' => 'event_month', 'type' => 'textfield', 'heading' => __( 'Month', 'medigreen' ) ),
				array( 'param_name' => 'event_day', 'type' => 'textfield', 'heading' => __( 'Day', 'medigreen' ) ),
				array( 'param_name' => 'event_title', 'type' => 'textfield', 'heading' => __( 'Title', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'event_description', 'type' => 'textarea', 'heading' => __( 'Description', 'medigreen' ) ),
				array( 'param_name' => 'event_image', 'type' => 'attach_image', 'heading' => __( 'Image', 'medigreen' ) 
				)
			)
		) );
	}
}