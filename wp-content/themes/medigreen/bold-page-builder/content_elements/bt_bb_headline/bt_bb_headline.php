<?php

class bt_bb_headline extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'ai_prompt'             => '',
			'headline'      		=> '',
			'html_tag'      		=> '',
			'font'          		=> '',
			'font_subset'   		=> '',
			'size'     				=> '',
			'font_size'     		=> '',
			'font_weight'  			=> '',
			'font_style'    		=> '',
			'color_scheme'  		=> '',
			'color'         		=> '',
			'supertitle_position'   => '',
			'image'  				=> '',
			'dash'          		=> '',
			'align'         		=> '',
			'url'           		=> '',
			'target'        		=> '',
			'superheadline' 		=> '',
			'subheadline'   		=> ''
		) ), $atts, $this->shortcode ) );

		$superheadline = html_entity_decode( $superheadline, ENT_QUOTES, 'UTF-8' );
		$subheadline = html_entity_decode( $subheadline, ENT_QUOTES, 'UTF-8' );
		$headline = html_entity_decode( $headline, ENT_QUOTES, 'UTF-8' );

		if ( $font != '' && $font != 'inherit' ) {
			require_once( dirname(__FILE__) . '/../../../../../plugins/bold-page-builder/content_elements_misc/misc.php' );
			bt_bb_enqueue_google_font( $font, $font_subset );
		}

		$class = array( $this->shortcode );
		$data_override_class = array();
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}

		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr( $el_id ) . '"';
		}
		
		$html_tag_style = "";
		$html_tag_style_arr = array();
		if ( $font != '' && $font != 'inherit' ) {
			$el_style = $el_style . ';' . 'font-family:\'' . urldecode( $font ) . '\'';
			$html_tag_style_arr[] = 'font-family:\'' . urldecode( $font ) . '\'';
		}
		if ( $font_size != '' ) {
			$html_tag_style_arr[] = 'font-size:' . $font_size  ;
		}
		if ( count( $html_tag_style_arr ) > 0 ) {
			$html_tag_style = ' style="' . implode( '; ', $html_tag_style_arr ) . '"';
		}
		
		$h_span_bg_style = '';
		if ( $image != '' && is_numeric( $image ) ) {
			$post_image = get_post( $image );
			if ( $post_image == '' ) return;
			$image = wp_get_attachment_image_src( $image, $size );
			$image = $image[0];
			$class[] = "btHasBgImage";
			$h_span_bg_style = "style = \"background-image:url('" . $image . "')\"";
		}

		if ( $font_weight != '' ) {
			$class[] = $this->prefix . 'font_weight_' . $font_weight ;
		}
		
		$color_scheme_id = NULL;
		if ( is_numeric ( $color_scheme ) ) {
			$color_scheme_id = $color_scheme;
		} else if ( $color_scheme != '' ) {
			$color_scheme_id = bt_bb_get_color_scheme_id( $color_scheme );
		}
		$color_scheme_colors = bt_bb_get_color_scheme_colors_by_id( $color_scheme_id - 1 );
		if ( $color_scheme_colors ) $el_style .= '; --headline-primary-color:' . $color_scheme_colors[0] . '; --headline-secondary-color:' . $color_scheme_colors[1] . ';';
		if ( $color_scheme != '' ) $class[] = $this->prefix . 'color_scheme_' .  $color_scheme_id;


		if ( $color != '' ) {
			$el_style = $el_style . ';' . 'color:' . $color . ';border-color:' . $color . ';';
		}

		if ( $dash != '' ) {
			$class[] = $this->prefix . 'dash' . '_' . $dash;
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
		
		if ( $font_style != '' ) {
			$class[] = $this->prefix . 'style' . '_' . $font_style;
		}
		
		if ( $target == '' ) {
			$target = '_self';
		}

		$superheadline_inside = '';
		$superheadline_outside = '';
		
		if ( $superheadline != '' ) {
			$class[] = $this->prefix . 'superheadline';
			if ( $supertitle_position == 'outside' ) { 
				$superheadline_outside = '<span class="' . esc_attr( $this->shortcode ) . '_superheadline">' . $superheadline . '</span>';
			} else {
				$superheadline_inside = '<span class="' . esc_attr( $this->shortcode ) . '_superheadline">' . $superheadline . '</span>';
			}
			
		}
		
		if ( $subheadline != '' ) {
			$class[] = $this->prefix . 'subheadline';
			$subheadline = '<div class="' . esc_attr( $this->shortcode ) . '_subheadline">' . $subheadline . '</div>';
			$subheadline = nl2br( $subheadline );
		}
		

		$style_attr = '';
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
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
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );
		
		if ( $headline != '' ) {
			if ( $url != '' ) {
				$url_title = strip_tags( str_replace( array("\n", "\r"), ' ', $headline ) );
				$link = bt_bb_get_url( $url );
				$headline = '<a href="' . esc_url( $link ) . '" target="' . esc_attr( $target ) . '" title="' . esc_attr( $url_title )  . '">' . $headline . '</a>';
			}		
			$headline = '<span class="' . esc_attr( $this->shortcode ) . '_content"><span ' . $h_span_bg_style . '>' . $headline . '</span></span>';			
		}
		
		$headline = nl2br( $headline );

		$output = '<header' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . ' data-bt-override-class="' . htmlspecialchars( json_encode( $data_override_class, JSON_FORCE_OBJECT ), ENT_QUOTES, 'UTF-8' ) . '">';
		if ( $superheadline_outside != '' ) $output .= '<div class="' . esc_attr( $this->shortcode . '_superheadline_outside' ) . '">' . $superheadline_outside . '</div>';
		if ( $headline != '' || $superheadline_inside != '' ) $output .= '<' . $html_tag . $html_tag_style . '>' . $superheadline_inside . $headline . '</' . $html_tag . '>';
		$output .= $subheadline . '</header>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {

		require_once( dirname(__FILE__) . '/../../../../../plugins/bold-page-builder/content_elements_misc/fonts.php' );
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();	

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Headline', 'medigreen' ), 'description' => esc_html__( 'Headline with custom Google fonts (and AI help)', 'medigreen' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode, 'highlight' => true,
			'params' => array(
				array(
					'param_name' => 'ai_prompt',
					'type' => 'ai_prompt',
					'target' =>
						array(
							'superheadline' => array( 'alias' => 'supertitle', 'title' => esc_html__( 'Superheadline', 'medigreen' ) ),
							'headline' => array( 'alias' => 'title', 'title' => esc_html__( 'Headline', 'medigreen' ) ),
							'subheadline' => array( 'alias' => 'subtitle', 'title' => esc_html__( 'Subheadline', 'medigreen' ) ),
						),
					'system_prompt' => 'You are a copywriter and your goal is to help users generate website content. Based on the user prompt generate supertitle, title and subtitle for the website page.',
				),
				array( 'param_name' => 'superheadline', 'type' => 'textfield', 'heading' => esc_html__( 'Superheadline', 'medigreen' ) ),
				array( 'param_name' => 'headline', 'type' => 'textarea', 'heading' => esc_html__( 'Headline', 'medigreen' ), 'preview' => true, 'preview_strong' => true ),
				array( 'param_name' => 'subheadline', 'type' => 'textarea', 'heading' => esc_html__( 'Subheadline', 'medigreen' ) ),
				array( 'param_name' => 'html_tag', 'type' => 'dropdown', 'heading' => esc_html__( 'HTML tag', 'medigreen' ), 'preview' => true,
					'value' => array(
						esc_html__( 'h1', 'medigreen' ) => 'h1',
						esc_html__( 'h2', 'medigreen' ) => 'h2',
						esc_html__( 'h3', 'medigreen' ) => 'h3',
						esc_html__( 'h4', 'medigreen' ) => 'h4',
						esc_html__( 'h5', 'medigreen' ) => 'h5',
						esc_html__( 'h6', 'medigreen' ) => 'h6'
				) ),
				array( 'param_name' => 'size', 'type' => 'dropdown', 'heading' => esc_html__( 'Size', 'medigreen' ), 'description' => esc_html__( 'Predefined heading sizes, independent of html tag', 'medigreen' ), 'responsive_override' => true,
					'value' => array(
						esc_html__( 'Inherit', 'medigreen' ) 			=> 'inherit',
						esc_html__( 'Extra Small', 'medigreen' ) 		=> 'extrasmall',
						esc_html__( 'Small', 'medigreen' ) 			=> 'small',
						esc_html__( 'Medium', 'medigreen' ) 			=> 'medium',
						esc_html__( 'Normal', 'medigreen' ) 			=> 'normal',
						esc_html__( 'Large', 'medigreen' ) 			=> 'large',
						esc_html__( 'Extra Large', 'medigreen' ) 		=> 'extralarge',
						esc_html__( 'Huge', 'medigreen' ) 			=> 'huge',
						esc_html__( 'Extra Huge', 'medigreen' ) 		=> 'extrahuge'
					)
				),				
				array( 'param_name' => 'align', 'type' => 'dropdown', 'heading' => esc_html__( 'Alignment', 'medigreen' ), 'responsive_override' => true,
					'value' => array(
						esc_html__( 'Inherit', 'medigreen' ) => 'inherit',
						esc_html__( 'Center', 'medigreen' ) => 'center',
						esc_html__( 'Left', 'medigreen' ) => 'left',
						esc_html__( 'Right', 'medigreen' ) => 'right'
					)
				),
				array( 'param_name' => 'dash', 'type' => 'dropdown', 'heading' => esc_html__( 'Dash', 'medigreen' ), 'group' => esc_html__( 'Design', 'medigreen' ),
					'value' => array(
						esc_html__( 'None', 'medigreen' ) => 'none',
						esc_html__( 'Top', 'medigreen' ) => 'top',
						esc_html__( 'Bottom', 'medigreen' ) => 'bottom',
						esc_html__( 'Top and bottom', 'medigreen' ) => 'top_bottom'
					)
				),
				array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Color scheme', 'medigreen' ), 'group' => esc_html__( 'Design', 'medigreen' ), 'value' => $color_scheme_arr, 'preview' => true ),
				array( 'param_name' => 'color', 'type' => 'colorpicker', 'heading' => esc_html__( 'Color', 'medigreen' ), 'group' => esc_html__( 'Design', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'supertitle_position', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'medigreen' ) => 'outside' ), 'heading' => esc_html__( 'Put supertitle outside H tag', 'medigreen' ), 'group' => esc_html__( 'Design', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'image', 'type' => 'attach_image', 'heading' => esc_html__( 'Background image', 'medigreen' ), 'preview' => true, 'group' => esc_html__( 'Font', 'medigreen' ) ),
				array( 'param_name' => 'font', 'type' => 'dropdown', 'heading' => esc_html__( 'Font', 'medigreen' ), 'group' => esc_html__( 'Font', 'medigreen' ), 'preview' => true,
					'value' => array( esc_html__( 'Inherit', 'medigreen' ) => 'inherit' ) + $font_arr
				),
				array( 'param_name' => 'font_subset', 'type' => 'textfield', 'heading' => esc_html__( 'Font subset', 'medigreen' ), 'group' => esc_html__( 'Font', 'medigreen' ), 'value' => 'latin,latin-ext', 'description' => 'E.g. latin,latin-ext,cyrillic,cyrillic-ext' ),
				array( 'param_name' => 'font_size', 'type' => 'textfield', 'heading' => esc_html__( 'Custom font size', 'medigreen' ), 'group' => esc_html__( 'Font', 'medigreen' ), 'description' => 'E.g. 20px or 1.5rem' ),
				array( 'param_name' => 'font_weight', 'type' => 'dropdown', 'heading' => esc_html__( 'Font weight', 'medigreen' ), 'preview' => true, 'group' => esc_html__( 'Font', 'medigreen' ),
					'value' => array(
						esc_html__( 'Default', 'medigreen' ) 		=> '',
						esc_html__( 'Thin', 'medigreen' ) 		=> 'thin',
						esc_html__( 'Lighter', 'medigreen' ) 		=> 'lighter',
						esc_html__( 'Light', 'medigreen' ) 		=> 'light',
						esc_html__( 'Normal', 'medigreen' ) 		=> 'normal',
						esc_html__( 'Medium', 'medigreen' ) 		=> 'medium',
						esc_html__( 'Semi Bold', 'medigreen' ) 	=> 'semi-bold',
						esc_html__( 'Bold', 'medigreen' ) 		=> 'bold',
						esc_html__( 'Bolder', 'medigreen' ) 		=> 'bolder',
						esc_html__( 'Black', 'medigreen' ) 		=> 'black'
					)
				),
				array( 'param_name' => 'font_style', 'type' => 'dropdown', 'heading' => esc_html__( 'Font style', 'medigreen' ), 'group' => esc_html__( 'Font', 'medigreen' ),
					'value' => array(
						esc_html__( 'Default', 'medigreen' ) => '',
						esc_html__( 'Italic', 'medigreen' ) => 'italic'
					)
				),
				array( 'param_name' => 'url', 'type' => 'textfield', 'heading' => esc_html__( 'URL', 'medigreen' ), 'group' => esc_html__( 'URL', 'medigreen' ) ),
				array( 'param_name' => 'target', 'type' => 'dropdown', 'heading' => esc_html__( 'Target', 'medigreen' ), 'group' => esc_html__( 'URL', 'medigreen' ),
					'value' => array(
						esc_html__( 'Self (open in same tab)', 'medigreen' ) => '_self',
						esc_html__( 'Blank (open in new tab)', 'medigreen' ) => '_blank'
					)
				)
			)
		) );
	}
}