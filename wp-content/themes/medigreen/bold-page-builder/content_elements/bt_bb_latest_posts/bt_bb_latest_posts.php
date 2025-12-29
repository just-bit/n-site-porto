<?php

class bt_bb_latest_posts extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'rows'				=> '',
			'columns'			=> '',
			'gap'				=> '',
			'size'   			=> '',
			'image_position'	=> '',
			'category'			=> '',
			'target'			=> '',
			'image_shape'		=> '',
			'show_category'		=> '',
			'show_date'			=> '',
			'show_author'		=> '',
			'show_comments'		=> '',
			'show_excerpt'		=> '',
			'shadow'			=> '',
			'lazy_load'  		=> 'no'
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
		
		if ( $columns != '' ) {
			$class[] = $this->prefix . 'columns' . '_' . $columns;
		}

		if ( $columns == '1' ) {
			$class[] = "btOneColumn";
		}
		
		if ( $gap != '' ) {
			$class[] = $this->prefix . 'gap' . '_' . $gap;
		}

		if ( $shadow != '' ) {
			$class[] = $this->prefix . 'shadow' . '_' . $shadow;
		}

		if ( $image_position != '' ) {
			$class[] = $this->prefix . 'image_position' . '_' . $image_position;
		}
		
		if ( $image_shape != '' ) {
			$class[] = $this->prefix . 'image_shape' . '_' . $image_shape;
		}
		
		$class = apply_filters( $this->shortcode . '_class', $class, $atts );
		
		$number = $rows * $columns;
		
		$posts = bt_bb_get_posts( $number, 0, $category );
		
		$output = '';

		foreach( $posts as $post_item ) {

			$output .= '<div class="' . esc_attr( $this->shortcode . '_item' ) . '">';
				$post_thumbnail_id = get_post_thumbnail_id( $post_item['ID'] );

				if ( $post_thumbnail_id != '' ) {
					$img = wp_get_attachment_image_src( $post_thumbnail_id, $size );
					if ( $lazy_load == 'yes' ) {
						$img_src = BT_BB_Root::$path . 'img/blank.gif';
						$img_class = 'btLazyLoadImage';
						$data_img = ' data-image_src="' . esc_attr( $img[0] ) . '"';
					} else {
						$img_src = $img[0];
						$img_class = '';
						$data_img = '';
					}
					$output .= '<div class="' . esc_attr( $this->shortcode ) . '_item_image"><img src="' . esc_url_raw( $img_src ) . '" alt="' . esc_attr( $post_item['title'] ) . '" title="' . esc_attr( $post_item['title'] ) . '" class="' . esc_attr( $img_class ) . '" ' . $data_img .  '"></div>';
				}

				$output .= '<div class="' . esc_attr( $this->shortcode . '_item_content' ) . '">';
				
					if ( $show_category == 'show_category' ) {
						$output .= '<div class="' . esc_attr( $this->shortcode . '_item_category' ) . '">';
							$output .= $post_item['category_list'];
						$output .= '</div>';
					}

					$output .= '<h5 class="' . esc_attr( $this->shortcode . '_item_title' ) . '">';
						$output .= '<a href="' . esc_url_raw( $post_item['permalink'] ) . '" target="' . esc_attr( $target ) . '">' . $post_item['title'] . '</a>';
					$output .= '</h5>';

					if ( $show_date == 'show_date' || $show_author == 'show_author' || $show_author == 'show_comments' ) {
				
						$meta_output = '<div class="' . esc_attr( $this->shortcode ) . '_item_meta">';

							if ( $show_author == 'show_author' ) {
								$meta_output .= '<span class="' . esc_attr( $this->shortcode . '_item_author' ) . '">';
									$meta_output .= esc_html__( 'by', 'medigreen' ) . ' ' . $post_item['author'];
								$meta_output .= '</span>';
							}

							if ( $show_date == 'show_date' ) {
								$meta_output .= '<span class="' . esc_attr( $this->shortcode . '_item_date' ) . '">';
									$meta_output .= get_the_date( '', $post_item['ID'] );
								$meta_output .= '</span>';
							}

							if ( $show_comments == 'show_comments' && $post_item['comments'] != '' ) {
								$meta_output .= '<span class="' . esc_attr( $this->shortcode . '_item_comments' ) . '">';
									$meta_output .= $post_item['comments'];
								$meta_output .= '</span>';
							}
				
						$meta_output .= '</div>';
		
						$output .= $meta_output;
		
					}
					
					if ( $show_excerpt == 'show_excerpt' ) {
						$output .= '<div class="' . esc_attr( $this->shortcode . '_item_excerpt' ) . '">';
							$output .= $post_item['excerpt'];
						$output .= '</div>';
					}
				$output .= '</div>';
				
			$output .= '</div>';
		}

		$output = '<div' . $id_attr . ' class="' . esc_attr( implode( ' ', $class ) ) . '"' . $style_attr . '>' . $output . '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );

		return $output;

	}

	function map_shortcode() {

		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Latest Posts', 'medigreen' ), 'description' => esc_html__( 'List of latest posts', 'medigreen' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'rows', 'type' => 'textfield', 'value' => '1', 'heading' => esc_html__( 'Rows', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'columns', 'type' => 'dropdown', 'value' => '3', 'heading' => esc_html__( 'Columns', 'medigreen' ), 'preview' => true,
					'value' => array(
						esc_html__( '1', 'medigreen' ) => '1',
						esc_html__( '2', 'medigreen' ) => '2',
						esc_html__( '3', 'medigreen' ) => '3',
						esc_html__( '4', 'medigreen' ) => '4',
						esc_html__( '6', 'medigreen' ) => '6'
					)
				),
				array( 'param_name' => 'gap', 'type' => 'dropdown', 'default' => 'normal', 'heading' => esc_html__( 'Gap', 'medigreen' ), 'preview' => true,
					'value' => array(
						esc_html__( 'No gap', 'medigreen' ) 	=> 'no_gap',
						esc_html__( 'Small', 'medigreen' ) 	=> 'small',
						esc_html__( 'Normal', 'medigreen' ) 	=> 'normal',
						esc_html__( 'Large', 'medigreen' ) 	=> 'large'
					)
				),
				array( 'param_name' => 'size', 'type' => 'dropdown', 'heading' => esc_html__( 'Size', 'medigreen' ), 'preview' => true,
					'value' => bt_bb_get_image_sizes()
				),
				array( 'param_name' => 'image_position', 'type' => 'dropdown', 'heading' => __( 'Featured image position', 'medigreen' ), 'group' => __( 'General', 'medigreen' ), 'description' => esc_html__( 'Choose 1 column for layout "image on side"', 'medigreen' ), 'preview' => true,
					'value' => array(
						esc_html__( 'On top', 'medigreen' ) 	=> '',
						esc_html__( 'On side', 'medigreen' ) 	=> 'on_side'
					)
				),		
				array( 'param_name' => 'category', 'type' => 'textfield', 'heading' => esc_html__( 'Category', 'medigreen' ), 'description' => esc_html__( 'Enter category slug or leave empty to show all', 'medigreen' ), 'preview' => true ),
				array( 'param_name' => 'target', 'type' => 'dropdown', 'heading' => esc_html__( 'Target', 'medigreen' ),
					'value' => array(
						esc_html__( 'Self (open in same tab)', 'medigreen' ) => '_self',
						esc_html__( 'Blank (open in new tab)', 'medigreen' ) => '_blank',
					)
				),
				array( 'param_name' => 'image_shape', 'type' => 'dropdown', 'heading' => esc_html__( 'Image shape', 'medigreen' ),
					'value' => array(
						esc_html__( 'Square', 'medigreen' ) 	=> 'square',
						esc_html__( 'Rounded', 'medigreen' ) 	=> 'rounded',
						esc_html__( 'Round', 'medigreen' ) 	=> 'round'
					)
				),
				array( 'param_name' => 'show_category', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'medigreen' ) => 'show_category' ), 'heading' => esc_html__( 'Show category', 'medigreen' ), 'preview' => true
				),
				array( 'param_name' => 'show_date', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'medigreen' ) => 'show_date' ), 'heading' => esc_html__( 'Show date', 'medigreen' ), 'preview' => true
				),
				array( 'param_name' => 'show_author', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'medigreen' ) => 'show_author' ), 'heading' => esc_html__( 'Show author', 'medigreen' ), 'preview' => true
				),
				array( 'param_name' => 'show_comments', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'medigreen' ) => 'show_comments' ), 'heading' => esc_html__( 'Show number of comments', 'medigreen' ), 'preview' => true
				),
				array( 'param_name' => 'show_excerpt', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'medigreen' ) => 'show_excerpt' ), 'heading' => esc_html__( 'Show excerpt', 'medigreen' ), 'preview' => true
				),
				array( 'param_name' => 'shadow', 'type' => 'dropdown', 'heading' => esc_html__( 'Shadow', 'medigreen' ),
					'value' => array(
						esc_html__( 'Visible', 'medigreen' ) 				=> '',
						esc_html__( 'Visible on hover', 'medigreen' ) 	=> 'on_hover',
						esc_html__( 'Hidden', 'medigreen' ) 				=> 'hidden'
					)
				),
				array( 'param_name' => 'lazy_load', 'type' => 'dropdown', 'default' => 'yes', 'heading' => esc_html__( 'Lazy load images', 'medigreen' ),
					'value' => array(
						esc_html__( 'No', 'medigreen' ) => 'no',
						esc_html__( 'Yes', 'medigreen' ) => 'yes'
					)
				)
			)
		) );
	} 
}